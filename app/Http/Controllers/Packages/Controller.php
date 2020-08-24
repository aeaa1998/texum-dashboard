<?php

namespace App\Http\Controllers\Packages;

use App\Models\Client;
use App\Models\Locker;
use App\Models\Lot;
use App\Models\Package;
use App\Models\PackageStatus;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    public function index()
    {
        #if (Gate::forUser($user)->allows('view-packages')) {
        $payload = Package::with([
            'lot.client', 'status',
            'lastRecord.newLocker'
        ])
            ->when(request()->has('letter'), function ($query) {
                return $query->whereHas('lastRecord.newLocker', function ($queryScoped) {
                    $queryString = request()->get('letter');
                    $queryScoped->where('letter', $queryString);
                });
            })
            ->when(request()->has('row'), function ($query) {
                return $query->whereHas('lastRecord.newLocker', function ($queryScoped) {
                    $queryString = request()->get('row');
                    $queryScoped->where('row', $queryString);
                });
            })
            ->when(request()->has('column'), function ($query) {
                return $query->whereHas('lastRecord.newLocker', function ($queryScoped) {
                    $queryString = request()->get('column');
                    $queryScoped->where('column', $queryString);
                });
            })
            ->when(request()->has('bar_code'), function ($query) {
                $queryString = request()->get('bar_code');
                return $query->where('bar_code', 'like', "%{$queryString}%");
            })
            ->when(request()->has('status_id'), function ($query) {
                return $query->whereHas('status', function ($queryStatus) {
                    $statusId = request()->get('status_id');
                    return $queryStatus->where('id', $statusId);
                });
            })
            ->when(request()->has('lot_id'), function ($query) {
                return $query->whereHas('lot', function ($queryLot) {
                    $lotId = request()->get('lot_id');
                    return $queryLot->where('id', $lotId);
                });
            })
            ->when(request()->has('query') && request()->get('query') != '', function ($query) {
                $query->where(function ($scoped) {
                    $queryString = request()->get('query');
                    $scoped->where('bar_code', 'like', "%{$queryString}%")
                        ->orWhereHas('lot.client', function ($clientQuery) use ($queryString) {
                            $clientQuery->where('name', 'like', "%$queryString%");
                        });
                });
            })
            ->paginate(10)
            ->appends(['dataOnly' => 'true']);

        collect($payload->items())->transform(function ($package) {
            $package->client = $package->lot->client;
            $package->currentLocker = $package->lastRecord->newLocker;
            return $package;
        });

        if (request()->has('dataOnly')) {
            return response()->json(['payload' => $payload]);
        } else {
            $packageStatuses = PackageStatus::all();
            $clients = Client::all();
            $lots = Lot::with('client')->whereHas('packages')->get();
            $lockers = Locker::all();
            $letters = $lockers->pluck('letter');
            $rows = $lockers->pluck('row');
            $columns = $lockers->pluck('column');
            return view('dashboard.packages.general')->with([
                'payload' => json_encode($payload),
                'statuses' => $packageStatuses,
                'lots' => $lots,
                'letters' => $letters,
                'rows' => $rows,
                'columns' => $columns,
                'clients' => $clients
            ]);
        }
        #}
    }

    public function store(Request $request)
    {
        $request->validate([
            'bar_code'      => 'required|string',
            'column'   => 'required',
            'letter'   => 'required',
            'row'   => 'required',
            'lot_id'  => 'required|exists:App\Models\Lot,id',
        ]);
        $userId = Auth::user()->id;
        $package = new Package();
        $package->status_id = 3;
        $package->lot_id = $request->lot_id;
        $package->bar_code = $request->bar_code;
        $package->save();
        $locker = Locker::where('column', $request->column)
            ->where('letter', $request->letter)
            ->where('row', $request->row)
            ->first();
        if (!$locker) {
            return response('No existe dicho locker.', 498);
        }
        if ($locker->capacity == 0) {
            return response('El locker ya no tiene capacidad.', 499);
        }
        $locker->capacity -= 1;
        $locker->save();
        $record = new Record();
        $record->package_id = $package->id;
        $record->user_id = $userId;
        $record->new_locker = $locker->id;
        $record->save();
        return response()->json(["message" => "Creado exitosamente"]);
    }

    public function update(Request $request, $packageId)
    {
        $request->validate([
            'bar_code'      => 'required|string',
            'lot_id'  => 'required|exists:App\Models\Lot,id',
        ]);
        $package = Package::find($packageId);
        $package->bar_code = $request->bar_code;
        $package->lot_id = $request->lot_id;
        $package->save();
        $package = $package = Package::with([
            'lot.client', 'status',
            'lastRecord.newLocker'
        ])->find($packageId);
        $package->client = $package->lot->client;
        $package->currentLocker = $package->lastRecord->newLocker;
        return response()->json(["package" => $package]);
    }

    public function packagesByLocker(Request $request)
    {
        $packages = Package::with('lot')->when(request()->has('letter'), function ($query) {
            return $query->whereHas('lastRecord.newLocker', function ($queryScoped) {
                $queryString = request()->get('letter');
                $queryScoped->where('letter', $queryString);
            });
        })
            ->when(request()->has('row'), function ($query) {
                return $query->whereHas('lastRecord.newLocker', function ($queryScoped) {
                    $queryString = request()->get('row');
                    $queryScoped->where('row', $queryString);
                });
            })
            ->when(request()->has('column'), function ($query) {
                return $query->whereHas('lastRecord.newLocker', function ($queryScoped) {
                    $queryString = request()->get('column');
                    $queryScoped->where('column', $queryString);
                });
            })
            ->whereDoesntHave('requests', function ($query) {
                $query->where('status_id', 2);
            })
            ->get();
        return $packages;
    }
}

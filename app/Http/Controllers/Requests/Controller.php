<?php

namespace App\Http\Controllers\Requests;

use App\Models\Locker;
use App\Models\Lot;
use App\Models\PackageRequest;
use App\Models\Record;
use App\Models\RequestStatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{

    public function index()
    {
        $statusId = request()->has('status_id') ? request()->get('status_id') : 2;
        $payload = PackageRequest::with(['user.worker',  'oldLocker', 'newLocker', 'package.lot.client', 'status'])
            ->when(request()->has('old_letter'), function ($query) {
                $query->whereHas('oldLocker', function ($scopedQuery) {
                    $queryString = request()->get('old_letter');
                    $scopedQuery->where('letter', $queryString);
                });
            })
            ->where('status_id', $statusId)
            ->when(request()->has('old_row'), function ($query) {
                $query->whereHas('oldLocker', function ($scopedQuery) {
                    $queryString = request()->get('old_row');
                    $scopedQuery->where('row', $queryString);
                });
            })
            ->when(request()->has('old_column'), function ($query) {
                $query->whereHas('oldLocker', function ($scopedQuery) {
                    $queryString = request()->get('old_column');
                    $scopedQuery->where('column', $queryString);
                });
            })
            ->when(request()->has('new_letter'), function ($query) {
                $query->whereHas('newLocker', function ($scopedQuery) {
                    $queryString = request()->get('new_letter');
                    $scopedQuery->where('letter', $queryString);
                });
            })
            ->when(request()->has('new_row'), function ($query) {
                $query->whereHas('newLocker', function ($scopedQuery) {
                    $queryString = request()->get('new_row');
                    $scopedQuery->where('row', $queryString);
                });
            })
            ->when(request()->has('new_column'), function ($query) {
                $query->whereHas('newLocker', function ($scopedQuery) {
                    $queryString = request()->get('new_column');
                    $scopedQuery->where('column', $queryString);
                });
            })
            ->when(request()->has('bar_code'), function ($query) {
                $query->whereHas('package', function ($scopedQuery) {
                    $queryString = request()->get('bar_code');
                    return $scopedQuery->where('bar_code', 'like', "%{$queryString}%");
                });
            })
            ->when(request()->has('date_from'), function ($query) {
                $dateFrom = request()->get('date_from');
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when(request()->has('date_to'), function ($query) {
                $date = request()->get('date_to');
                $query->whereDate('created_at', '<=', $date);
            })
            ->when(request()->has('lot_id'), function ($query) {
                return $query->whereHas('package.lot', function ($queryLot) {
                    $lotId = request()->get('lot_id');
                    return $queryLot->where('id', $lotId);
                });
            })
            ->when(request()->has('query') && request()->get('query') != '', function ($query) {
                $query->whereHas('package', function ($packageQuery) {
                    $packageQuery->where(function ($scoped) {
                        $queryString = request()->get('query');
                        $scoped->where('bar_code', 'like', "%{$queryString}%")
                            ->orWhereHas('lot.client', function ($clientQuery) use ($queryString) {
                                $clientQuery->where('name', 'like', "%$queryString%");
                            });
                    });
                });
            })->paginate(10)
            ->appends(['dataOnly' => 'true']);

        collect($payload->items())->transform(function ($record) {
            $record->package->client = $record->package->lot->client;
            return $record;
        });

        if (request()->has('dataOnly')) {
            return response()->json(['payload' => $payload]);
        } else {
            $statuses = RequestStatus::all();
            $lots = Lot::with('client')->whereHas('packages')->get();
            $lockers = Locker::all();
            $letters = $lockers->pluck('letter');
            $rows = $lockers->pluck('row');
            $columns = $lockers->pluck('column');
            return view('dashboard.requests.general')->with([
                'payload' => json_encode($payload),
                'statuses' => $statuses,
                'lots' => $lots,
                'letters' => $letters,
                'rows' => $rows,
                'columns' => $columns,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $packageRequest = PackageRequest::find($id);
        if ($request->accepted == 0) {
            $packageRequest->status_id = 3;
        } else {
            $newLocker = Locker::find($packageRequest->new_locker);
            if ($newLocker->capacity > 0) {
                $packageRequest->status_id = 1;
                $record = new Record();
                $record->package_id = $packageRequest->package_id;
                $record->user_id = Auth::user()->id;
                $record->old_locker = $packageRequest->old_locker;
                $record->new_locker = $packageRequest->new_locker;
                $record->save();
                $newLocker->capacity -= 1;
                $newLocker->save();
                $oldLocker = Locker::find($packageRequest->old_locker);
                $oldLocker->capacity += 1;
                $oldLocker->save();
            } else {
                return response('El nuevo locker ya no tiene capacidad para almacenar el paquete.', 498);
            }
        }
        $packageRequest->save();
        $packageRequest = PackageRequest::with(['user.worker',  'oldLocker', 'newLocker', 'package.lot.client', 'status'])->find($id);
        return response()->json(['packageRequest' => $packageRequest]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id'   => 'required|exists:App\Models\Package,id',
            'old_locker_row'  => 'required',
            'old_locker_column'  => 'required',
            'old_locker_letter'  => 'required',
            'new_locker_row'  => 'required',
            'new_locker_column'  => 'required',
            'new_locker_letter'  => 'required',
        ]);
        $oldLocker = Locker::where('column',  $request->old_locker_column)
            ->where('row', $request->old_locker_row)
            ->where('letter', $request->old_locker_letter)
            ->first();
        $newLocker = Locker::where('column',  $request->new_locker_column)
            ->where('row', $request->new_locker_row)
            ->where('letter', $request->new_locker_letter)
            ->first();
        if (!$oldLocker) {
            return response()->json(['message' => 'El locker origen no existe'], 405);
        } else if ($oldLocker->capacity <= 0) {
            return response()->json(['message' => 'El locker origen no tiene espacio disponible'], 405);
        }
        if (!$newLocker) {
            return response()->json(['message' => 'El locker destino no existe'], 405);
        } else if ($newLocker->capacity <= 0) {
            return response()->json(['message' => 'El locker destino no tiene espacio disponible'], 405);
        }
        $packageRequest = new PackageRequest();
        $packageRequest->user_id = Auth::user()->id;
        $packageRequest->package_id = $request->package_id;
        $packageRequest->old_locker = $oldLocker->id;
        $packageRequest->new_locker = $newLocker->id;
        $packageRequest->status_id = 2;
        $packageRequest->save();
        return $packageRequest;
    }
}

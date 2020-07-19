<?php

namespace App\Http\Controllers\Packages;

use App\Models\Locker;
use App\Models\Lot;
use App\Models\Package;
use App\Models\PackageStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    public function index()
    {
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
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Records;

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
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    public function index()
    {
        $ids = [];
        if (request()->has('just_last_records')) {
            $ids = Package::with('lastRecord:id')->get()->pluck('lastRecord')
                ->map(function ($record) {
                    return $record->id;
                })
                ->toArray();
        }
        $payload = Record::with(['user.worker',  'oldLocker', 'newLocker', 'package.lot.client'])
            ->when(request()->has('old_letter'), function ($query) {
                $query->whereHas('oldLocker', function ($scopedQuery) {
                    $queryString = request()->get('old_letter');
                    $scopedQuery->where('letter', $queryString);
                });
            })
            ->when(request()->has('just_last_records'),  function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })
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
            })
            ->paginate(10)
            ->appends(['dataOnly' => 'true']);
        collect($payload->items())->transform(function ($record) {
            $record->package->client = $record->package->lot->client;
            return $record;
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
            return view('dashboard.records.general')->with([
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

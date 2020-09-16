<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Worker;
use Carbon\Carbon;

class WorkerController extends Controller
{
  // Obtener todos los Trabajadores
  public function index()
  {
    $verified = request()->has('verified') ? request()->get('verified') : null;

    $payload = User::with('worker')
      ->whereHas('worker')
      ->when($verified, function ($query) use ($verified) {
        $query->when($verified == 1, function ($vq) {
          $vq->whereNotNull('verified_at');
        })
          ->when($verified == 2, function ($vq) {
            $vq->whereNull('verified_at');
          });
      })
      ->when(request()->has('queryString'), function ($query) {
        $query->where(function ($scoped) {
          $queryString = request()->get('queryString');
          $scoped
            ->whereHas('worker', function ($workerScope) use ($queryString) {
              $workerScope->where('first_name', 'like', "%{$queryString}%")
                ->orWhere('last_name', 'like', "%{$queryString}%");
            })
            ->orWhere('email', 'like', "%$queryString%");
        });
      })
      ->when(request()->has('firstName'), function ($query) {
        $firstName = request()->get('firstName');
        $query->whereHas('worker', fn ($workerQuery) => $workerQuery->where('first_name', 'like', "%{$firstName}%"));
      })
      ->when(request()->has('createdFrom'), function ($query) {
        $date = request()->get('createdFrom');
        $query->where('created_at', '>=', $date);
      })
      ->when(request()->has('createdTo'), function ($query) {
        $date = request()->get('createdTo');
        $query->where('created_at', '<=', $date);
      })
      ->when(request()->has('lastName'), function ($query) {
        $lastName = request()->get('lastName');
        $query->whereHas('worker', fn ($workerQuery) => $workerQuery->where('last_name', 'like', "%{$lastName}%"));
      })
      ->orderBy('id')
      ->paginate(10)
      ->appends(['dataOnly' => 'true']);
    collect($payload->items())->transform(function ($user) {
      $user->first_name = $user->worker->first_name;
      $user->last_name = $user->worker->last_name;
      unset($user->worker);
      return $user;
    });
    if (request()->has('dataOnly')) {
      return response()->json(['payload' => $payload]);
    }
    return view('dashboard.workers')->with(['payload' => json_encode($payload)]);
  }

  public function processWorker(Request $request, $userId)
  {
    $request->validate([
      'accepted' => 'required|integer'
    ]);
    $user = User::find($userId);
    if ($request->accepted == 1) {
      $user->verified_at = Carbon::now();
      $user->save();
      return response()->json(['message' => 'Se verifico el usuario de manera correcta']);
    } else {
      Worker::where('user_id', $userId)->delete();
      Record::where('user_id', $userId)->delete();
      $user->delete();
      return response()->json(['message' => 'Se rechazo el usuario de manera correcta']);
    }
  }
}

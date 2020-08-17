<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use Carbon\Carbon;

class WorkerController extends Controller
{
  // Obtener todos los Trabajadores
  public function index()
  {
      $workers = Worker::all();
      return view('dashboard.workerstable')->with(['workerstable' => $workers]);
  }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Records;

class RecordsController extends Controller
{
    //Muestra todo el historial de la aplicacion
    public function getAllRecords() 
    {
        $records = Records::
            join('workers', 'workers.id', '=', 'records.worker_id')
            ->join('packages', 'packages.id', '=', 'records.package_id')
            ->select('workers.name', 'packages.bar_code', 'records.old_locker', 'records.new_locker', 'records.updated_at')
            ->get();
    }
    //Recibe: Nombre de usuario
    //Muestra todos los cambios realizados por el usuario en el sistema
    public function getRecordsByUserName($username) 
    {
        $records = Records::
            join('workers', 'workers.id', '=', 'records.worker_id')
            ->join('packages', 'packages.id', '=', 'records.package_id')
            ->select('workers.name', 'packages.bar_code', 'records.old_locker', 'records.new_locker', 'records.updated_at')
            ->where('workers.name', '=', $username)
            ->get();
    }
    //Recibe: Codigo de barras
    //Muestra todos los cambios hechos para un empaque en particular
    public function getRecordsByCodeBar($barcode)
    {
        $records = Records::
            join('workers', 'workers.id', '=', 'records.worker_id')
            ->join('packages', 'packages.id', '=', 'records.package_id')
            ->select('workers.name', 'packages.bar_code', 'records.old_locker', 'records.new_locker', 'records.updated_at')
            ->where('packages.bar_code', '=', $barcode)
            ->get();
    }
    //Recibe: fecha de modificiacion
    //Muestra todos los cambios realizados en cierta fecha
    public function getRecordsByDate($modificationDate)
    {
        $records = Records::
            join('workers', 'workers.id', '=', 'records.worker_id')
            ->join('packages', 'packages.id', '=', 'records.package_id')
            ->select('workers.name', 'packages.bar_code', 'records.old_locker', 'records.new_locker', 'records.updated_at')
            ->where('records.created_at', '=', $modificationDate)
            ->orWhere('records.updated_at', '=', $modificationDate)
            ->get();
    }
}

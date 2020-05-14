<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class SearchController extends Controller
{
    //Recibe: codigo de barras de empaque
    //Muestra busqueda de empaques en base a codigo de barras dentro de la base de datos
    public function searchByCodeBar($codebar)
    {
        $search = Package::
            join('lots', 'lots.id', '=', 'packages.lot_id')
            ->join('records', 'records.package_id', '=', 'packages.id')
            ->select('packages.bar_code', 'lots.number', 'records.new_locker', 'lots.client_id', 'lots.cut_date')
            ->where('packages.bar_code', '=', $codebar)
            ->get();
    }
    //Recibe: numero de lote
    //Muestra busqueda de empaques en base a numero de corte dentro de la base de datos
    public function searchByLot($lotNum)
    {
        $search = Package::
            join('lots', 'lots.id', '=', 'packages.lot_id')
            ->join('records', 'records.package_id', '=', 'packages.id')
            ->select('packages.bar_code', 'lots.number', 'records.new_locker', 'lots.client_id', 'lots.cut_date')
            ->where('lots.number', '=', $lotNum)
            ->get();
    }
    //Recibe: cliente
    //Muestra busqueda de empaques en base a cliente dentro de la base de datos
    public function searchByClient($client)
    {
        $search = Package::
            join('lots', 'lots.id', '=', 'packages.lot_id')
            ->join('records', 'records.package_id', '=', 'packages.id')
            ->select('packages.bar_code', 'lots.number', 'records.new_locker', 'lots.client_id', 'lots.cut_date')
            ->where('lots.client_id', '=', $client)
            ->get();
    }
    //Recibe: Fecha de corte
    //Muestra busqueda de empaques en base a fecha de corte en la base de datos
    public function searchByCutDate($cutDate)
    {
        $search = Package::
            join('lots', 'lots.id', '=', 'packages.lot_id')
            ->join('records', 'records.package_id', '=', 'packages.id')
            ->select('packages.bar_code', 'lots.number', 'records.new_locker', 'lots.client_id', 'lots.cut_date')
            ->where('lots.cut_date', '=', $cutDate)
            ->get();
    }
}

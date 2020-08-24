<?php

namespace App\Http\Controllers\Packages;

use App\Models\Package;
use Illuminate\Routing\Controller as BaseController;

class PackageRequestsController extends BaseController
{
    public function availablePackages()
    {
        $packages = Package::whereDoesntHave('requests', function ($query) {
            $query->where('id', '!=', 2);
        })
            ->whereHas('lastRecord.newLocker')
            ->get();
        return $packages;
    }
}

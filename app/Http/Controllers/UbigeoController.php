<?php

namespace App\Http\Controllers;

use App\Models\DepartmentsModel;
use App\Models\DistrictsModel;
use App\Models\ProvincesModel;
use Illuminate\Http\Request;

class UbigeoController extends Controller {
    
    public function departments() {
        $dep = DepartmentsModel::get();
        return $dep;
    }

    public function provinces($department_id) {
        $prov = ProvincesModel::where('department_id',$department_id)->get();
        return $prov;
    }

    public function districts($province_id) {
        $dist = DistrictsModel::where('province_id',$province_id)->get();
        return $dist;
    }
    
}
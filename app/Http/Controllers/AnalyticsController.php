<?php

namespace App\Http\Controllers;

use App\Models\ClientsModel;
use App\Models\EmployeeModel;
use App\Models\SuppliersModel;
use Illuminate\Http\Request;

class AnalyticsController extends Controller {
    
    public function audience() {

        $clients = ClientsModel::where('status',1)->whereNull('deleted_at')->get();
        $employees = EmployeeModel::where('status',1)->whereNull('deleted_at')->get();
        $suppliers = SuppliersModel::where('status',1)->whereNull('deleted_at')->get();

        // Quantity
        $labels = ['clients','employees','suppliers'];
        $quantities = [$clients->count(), $employees->count(), $suppliers->count()];
        $total_quantity = array_sum($quantities);
        $series = [
            round(($clients->count() * 100) / $total_quantity, 2),
            round(($employees->count() * 100) / $total_quantity, 2),
            round(($suppliers->count() * 100) / $total_quantity, 2)
        ];

        $quantity = [
            'labels' => $labels,
            'series' => $series,
            'total' => $total_quantity
        ];

        // Gender
        $labels = ['man','woman'];
        $genders = [
            round(($employees->where('gender',0)->count() * 100) / $employees->count(), 2),
            round(($employees->where('gender',1)->count() * 100) / $employees->count(), 2),
        ];
        $gender = [
            'labels' => $labels,
            'series' => $genders,
            'total' => $employees->count()
        ];

        return response()->json([
            'quantity' => $quantity,
            'gender' => $gender,
        ]);
    }
}

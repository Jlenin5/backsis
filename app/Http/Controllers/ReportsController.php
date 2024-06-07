<?php

namespace App\Http\Controllers;

use App\Models\BranchOfficesModel;
use App\Models\ProductsModel;
use App\Models\ProductWarehouseModel;
use App\Models\WarehousesModel;
use Illuminate\Http\Request;

class ReportsController extends Controller {
    
    public function stock_report(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;

        $data = array();

        $warehouses = WarehousesModel::whereNull('deleted_at')->get(['id', 'name']);
        $warehouses_id = WarehousesModel::whereNull('deleted_at')->pluck('id')->toArray();

        $branch_offices = BranchOfficesModel::whereNull('deleted_at')->get(['id', 'name']);

        $products_data = ProductsModel::where('status',1)->whereNull('deleted_at');

        $totalRows = $products_data->count();
        $products = $products_data->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($products as $product) {
            $item['id'] = $product->id;
            $item['code'] = $product->code;
            $item['name'] = $product->name;
            $item['purchase_price'] = $product->purchase_price;
            $item['sale_price'] = $product->sale_price;
            
            $reserve_stock = ProductWarehouseModel::where('product_id', $product->id)
                ->whereNull('deleted_at')
                ->whereIn('warehouse_id', $warehouses_id)
                ->when($request->filled('warehouse_id'), function ($query) use ($request) {
                        $query->where('warehouse_id', $request->warehouse_id);
                    })
                    ->when($request->filled('branch_office_id'), function ($query) use ($request) {
                        $query->whereHas('warehouse', function ($query) use ($request){
                            $query->where('branch_office_id',  $request->branch_office_id);
                        });
                    })
                ->sum('quantity');
            
            $item['reserve_stock'] = $reserve_stock;

            $item['unit'] = $product['unit']->name;

            $data[] = $item;
        }
        
        return response()->json([
            'data' => $data,
            'totalRows' => $totalRows,
            'warehouses' => $warehouses,
            'branch_offices'=> $branch_offices
        ]);
    }

}
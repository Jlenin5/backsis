<?php

namespace App\Http\Controllers;

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

        $companies = WarehousesModel::where('deleted_at', '=', null)->get(['id', 'name']);

        $products_data = ProductsModel::with([
                'categories', 'productImages', 'unit', 'warehouses'
            ])
            ->whereNull('deleted_at');

        $totalRows = $products_data->count();
        $products = $products_data->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($products as $product) {
            $item['id'] = $product->id;
            $item['code'] = $product->code;
            $item['name'] = $product->name;
            
            $current_stock = ProductWarehouseModel::where('product_id', $product->id)
                ->where('deleted_at', '=', null)
                ->whereIn('warehouse_id', $warehouses_id)
                // ->when($request->filled('warehouse_id'), function ($query) use ($request)  {
                //     $query->where('warehouse_id', $request->warehouse_id);
                //    })
                //    ->when($request->filled('company_id'), function ($query) use ($request){
                //     $query->whereHas('warehouse', function ($query) use ($request){
                //             $query->where('company_id',  $request->company_id);
                //          });
                //     })
                ->sum('quantity');
            
            $item['reserve'] = $product->reserveStock();
            $reserveStock = $product->reserveStock();

            if (count($reserveStock) > 0) {
                $totalreserve=0;
                foreach ($reserveStock as $reserve) {
                    if ($request['company_id']) {
                        if ($request['warehouse_id']) {
                            if ($reserve->warehouse_id == $request['warehouse_id']) {
                                $totalreserve = $reserve->total;
                            }
                        } else if ($reserve->company_id == $request['company_id']) {
                            $totalreserve += $reserve->total;
                        }
                    } else if ($request['warehouse_id']) {
                        if ($reserve->warehouse_id == $request['warehouse_id']) {
                            $totalreserve = $reserve->total;
                        }
                    } else {
                        $totalreserve += $reserve->total;
                    }
                }
                $item['reserveStock'] =  $totalreserve. ' ' . $product['unit']->short_name ;

            } else {
                $item['reserveStock'] = "";
            }

            $item['quantity'] = $current_stock .' '.$product['unit']->short_name;

            $data[] = $item;
        }
        
        return response()->json([
            'report' => $data,
            'totalRows' => $totalRows,
            'warehouses' => $warehouses,
            'companies'=> $companies
        ]);
    }

}
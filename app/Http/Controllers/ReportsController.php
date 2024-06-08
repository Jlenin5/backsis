<?php

namespace App\Http\Controllers;

use App\Models\BranchOfficesModel;
use App\Models\ProductsModel;
use App\Models\ProductWarehouseModel;
use App\Models\SaleOrderDetailsModel;
use App\Models\SaleOrdersModel;
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

        $products_data = ProductsModel::where('status',1)
                                    ->whereNull('deleted_at')
                                    ->whereHas('warehouses', function($query) {
                                    });

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

    public function stock_report_id($id) {
        $product = ProductsModel::with('productImages')->findOrFail($id);
        $array_warehouse_quantity = array();
        $array_sales = array();
        $product_warehouse = ProductWarehouseModel::where('product_id',$id)->whereNull('deleted_at')->get();
        foreach($product_warehouse as $item) {
            $warehouse = WarehousesModel::findOrFail($item->warehouse_id);
            $data = [
                'id' => $item->id,
                'name' => $warehouse->name,
                'quantity' => $item->quantity,
            ];
            $array_warehouse_quantity[] = $data;
        }

        $sale_detail = SaleOrderDetailsModel::where('product_id', $id)->get();
        foreach($sale_detail as $item) {
            $sale = SaleOrdersModel::with([
                    'client', 'warehouse.branch_office.company'
                ])
                ->where('is_approved',1)
                ->whereNull('deleted_at')
                ->find($item->sale_order_id);
            if ($sale) {
                $new_sale = [
                    'id' => $sale->id,
                    'code' => $sale->code,
                    'date' => $sale->date,
                    'quantity' => $item->quantity,
                    'client' => $sale->client->name,
                    'warehouse' => $sale->warehouse->name,
                    'branch_office' => $sale->warehouse->branch_office->name,
                    'company' => $sale->warehouse->branch_office->company->name,
                    'total' => $sale->total,
                ];
                $array_sales[] = $new_sale;
            }
        }

        $data = [];
        $data['id'] = $product->id;
        $data['code'] = $product->code;
        $data['name'] = $product->name;
        $data['featured'] = $product->featured;
        $data['product_images'] = $product->productImages;
        $data['warehouses'] = $array_warehouse_quantity;
        $data['sales'] = $array_sales;

        return response()->json([
            'data' => $data,
        ]);
    }

}
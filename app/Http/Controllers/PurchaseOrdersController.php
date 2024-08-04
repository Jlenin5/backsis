<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Models\PurchaseOrderDetailsModel;
use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseOrderExcel;
use App\Http\Requests\PurchaseOrders\Store;
use App\Http\Requests\PurchaseOrders\Update;
use App\Models\ProductWarehouseModel;
use App\Models\PurchaseOrderProductsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ApiResponser;

class PurchaseOrdersController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;
        
        return $this->getAll(
            PurchaseOrdersModel::whereNull('deleted_at')
                ->withDataAll([
                    'purchase_order_details',
                    'company',
                    'branch_office',
                    'warehouse',
                    'warehouse',
                    'supplier',
                    'currency'
                ])
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            PurchaseOrdersModel::whereNull('deleted_at')->count()
        );
    }

    public function show(PurchaseOrdersModel $purchase_order) {
        return $this->showOne($purchase_order->withData([
            'purchase_order_details',
            'company',
            'branch_office',
            'warehouse',
            'warehouse',
            'supplier',
            'currency'
        ]));
    }

    public function store(Store $request) {
        $lastCode = PurchaseOrdersModel::orderBy('id', 'desc')->first()->code ?? 'OC-00000';
        $lastNumber = (int)substr($lastCode, 5);
        $newNumber = $lastNumber + 1;
        $newCode = 'OC-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $request['code'] = $newCode;
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        $save_purchase_order = $this->stored(
            PurchaseOrdersModel::create($request->input())->withData([])
        );
        
        $last_purchase_order = PurchaseOrdersModel::latest('id')->first();

        foreach($request['purchase_order_details'] as $detail) {
            $purchase_order_detail = new PurchaseOrderProductsModel([
                'purchase_order_id' => $last_purchase_order->id,
                'product_id' => $detail['product_id'],
                'price' => $detail['price'],
                'tax' => $detail['tax'],
                'quantity' => $detail['quantity'],
                'warehouse_id' => $request['warehouse_id'],
                'user_create_id' => auth()->user()->id,
                'user_update_id' => auth()->user()->id
            ]);
            $purchase_order_detail->save();

            // $product_warehouse = ProductWarehouseModel::whereNull('deleted_at')
            //                                             ->where('product_id',$detail['product_id'])
            //                                             ->where('warehouse_id',$request['warehouse_id'])
            //                                             ->first();
            // if ($product_warehouse) {
            //     $product_warehouse->quantity = $product_warehouse->quantity + $detail['quantity'];
            //     $product_warehouse->update();
            // } else {
            //     $product_warehouse = new ProductWarehouseModel([
            //         'product_id' => $detail['product_id'],
            //         'warehouse_id' => $last_purchase_order->warehouse_id,
            //         'quantity' => $detail['quantity'],
            //         'user_create_id' => auth()->user()->id,
            //         'user_update_id' => auth()->user()->id
            //     ]);
            //     $product_warehouse->save();
            // }
        }
        return $save_purchase_order;
    }

    public function update(Update $request, PurchaseOrdersModel $purchase_order) {
        if ($request['supplier_document_date']) {
            $request['supplier_document_date'] = date("Y-m-d", strtotime($request['supplier_document_date']));
        }
        $request['user_update_id'] = auth()->user()->id;
        // $data = $request->json()->all();
        // $date = Carbon::parse($data['date']);
        // $date_approved = Carbon::parse($data['date_approved']);
        // $puor = PurchaseOrdersModel::find($id);
        // $puor->warehouse_id = $data['warehouse_id'];
        // $puor->supplier_id = $data['supplier_id'];
        // $puor->employee_id = $data['employee_id'];
        // $puor->supplier_document = $data['supplier_document'];
        // $puor->date = $date->format('Y-m-d');
        // $puor->discount = $data['discount'];
        // $puor->sub_total = $data['sub_total'];
        // $puor->total = $data['total'];
        // $puor->status = $data['status'];
        // $puor->is_approved = $data['is_approved'];
        // $puor->date_approved = $date_approved->format('Y-m-d');
        // $puor->update();
        // if (isset($data['purchase_order_details']) && is_array($data['purchase_order_details'])) {
        //     foreach ($data['purchase_order_details'] as $pod) {
        //         $existingDetail = PurchaseOrderDetailsModel::where('Product', $pod['Product'])
        //                                                     ->where('PurchaseOrder', $id)
        //                                                     ->first();
        //         if ($existingDetail) {
        //             $existingDetail->podPrice = $pod['podPrice'];
        //             $existingDetail->podTax = $pod['podTax'];
        //             $existingDetail->podDiscountMethod = $pod['podDiscountMethod'];
        //             $existingDetail->podDiscount = $pod['podDiscount'];
        //             $existingDetail->podQuantity = $pod['podQuantity'];
        //             $existingDetail->podTotal = $pod['podTotal'];
        //             $existingDetail->update();
        //         } else {
        //             $newDetail = new PurchaseOrderDetailsModel([
        //                 'Product' => $pod['Product'],
        //                 'PurchaseOrder' => $id,
        //                 'podPrice' => $pod['podPrice'],
        //                 'podTax' => $pod['podTax'],
        //                 'podDiscountMethod' => $pod['podDiscountMethod'],
        //                 'podDiscount' => $pod['podDiscount'],
        //                 'podQuantity' => $pod['podQuantity'],
        //                 'podTotal' => $pod['podTotal']
        //             ]);
        //             $newDetail->save();
        //         }
        //     }
        // }
        // $currentDetails = PurchaseOrderDetailsModel::where('PurchaseOrder', $id)->pluck('Product');
        // $incomingDetails = collect($data['purchase_order_details'])->pluck('Product');
        // $detailsToDelete = $currentDetails->diff($incomingDetails);
        
        // PurchaseOrderDetailsModel::where('PurchaseOrder', $id)
        //                         ->whereIn('Product', $detailsToDelete)
        //                         ->delete();
        $purchase_order->update($request->input());
        return $this->updated($purchase_order->withData([]));
    }

    public function destroy(PurchaseOrdersModel $purchase_order) {
        return ResponseJson::destroy($purchase_order->delete());
    }

    public function destroyMultiple(Request $request) {
        $ids = $request->input('dataId', []);
        if (empty($ids)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se proporcionaron datos para eliminar.'
            ], 400);
        }
        
        foreach ($ids as $puor_id) {
            PurchaseOrdersModel::whereId($puor_id)->update([
                'deleted_at' => now(),
            ]);
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }

    public function exportExcel(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $searchText = $request->query('search_text');

        $offset = ($page - 1) * $perPage;

        $query = PurchaseOrdersModel::with(
            'purchase_order_details','warehouses','suppliers','employees'
        )
        ->whereNull('deleted_at');

        if ($searchText) {
            $query->where(function ($query) use ($searchText) {
                $query->where('code', 'LIKE', "%{$searchText}%")
                        ->orWhereHas('currencies', function ($query) use ($searchText) {
                            $query->where('curName', 'LIKE', "%{$searchText}%");
                        })
                        ->orWhereHas('suppliers', function ($query) use ($searchText) {
                            $query->where('name', 'LIKE', "%{$searchText}%");
                        })
                        ->orWhereHas('employees', function ($query) use ($searchText) {
                            $query->where('first_name', 'LIKE', "%{$searchText}%")
                                ->orWhere('second_name', 'LIKE', "%{$searchText}%")
                                ->orWhere('surname', 'LIKE', "%{$searchText}%")
                                ->orWhere('second_surname', 'LIKE', "%{$searchText}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', second_name, ' ', surname, ' ', second_surname) LIKE ?", ["%{$searchText}%"]);
                        });
            });
        }

        $puor = $query->offset($offset)
                  ->limit($perPage)
                  ->orderBy('created_at', 'desc')
                  ->get();
            
        $fileName = 'archivo.xlsx';
        // $filePath = storage_path('app/exports/' . $fileName);

        return Excel::download(new PurchaseOrderExcel($puor), 'orden_de_compra.xlsx');
        // return response()->json(['message' => $request]);
    }

    public function exportPDF(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $searchText = $request->query('search_text');

        $offset = ($page - 1) * $perPage;

        $query = PurchaseOrdersModel::with(
            'purchase_order_details','warehouses','suppliers','employees'
        )
        ->whereNull('deleted_at');

        if ($searchText) {
            $query->where(function ($query) use ($searchText) {
                $query->where('code', 'LIKE', "%{$searchText}%")
                        ->orWhereHas('currencies', function ($query) use ($searchText) {
                            $query->where('curName', 'LIKE', "%{$searchText}%");
                        })
                        ->orWhereHas('suppliers', function ($query) use ($searchText) {
                            $query->where('name', 'LIKE', "%{$searchText}%");
                        })
                        ->orWhereHas('employees', function ($query) use ($searchText) {
                            $query->where('first_name', 'LIKE', "%{$searchText}%")
                                ->orWhere('second_name', 'LIKE', "%{$searchText}%")
                                ->orWhere('surname', 'LIKE', "%{$searchText}%")
                                ->orWhere('second_surname', 'LIKE', "%{$searchText}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', second_name, ' ', surname, ' ', second_surname) LIKE ?", ["%{$searchText}%"]);
                        });
            });
        }

        $purchase_orders = $query->offset($offset)
                  ->limit($perPage)
                  ->orderBy('created_at', 'desc')
                  ->get();

        $pdf = PDF::loadView('export.pdf.purchase_order', compact('purchase_orders'));
        return $pdf->stream("OC" . date('Y-m-d h:i:s') . ".pdf");
    }

    public function exportPDFId($id) {
        $purchase_order = PurchaseOrdersModel::with('purchase_order_details','warehouses','suppliers','employees')->where('deleted_at',null)->findOrFail($id);
        if (!$purchase_order) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        $pdf = PDF::loadView('export.pdf.purchase_order_id', compact('purchase_order'));
        return $pdf->stream("OC" . date('Y-m-d h:i:s') . ".pdf");
    }
}
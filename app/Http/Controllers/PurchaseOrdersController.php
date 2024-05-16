<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderDetailsModel;
use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseOrderExcel;
use App\Models\ProductWarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrdersController extends Controller {

    public function index(Request $request) {
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

        $totalRows = PurchaseOrdersModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $puor,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $puor = PurchaseOrdersModel::with('purchase_order_details','currencies','warehouses','suppliers','employees')->where('deleted_at',null)->findOrFail($id);
        if (!$puor) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $puor;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $date = Carbon::parse($data['date']);
        $date_approved = Carbon::parse($data['date_approved']);
        $puor = new PurchaseOrdersModel;
        $puor->code = $data['code'];
        $puor->warehouse_id = $data['warehouse_id'];
        $puor->supplier_id = $data['supplier_id'];
        $puor->employee_id = $data['employee_id'];
        $puor->supplier_document = $data['supplier_document'];
        $puor->date = $date->format('Y-m-d');
        $puor->discount = $data['discount'];
        $puor->sub_total = $data['sub_total'];
        $puor->total = $data['total'];
        $puor->status = $data['status'];
        $puor->is_approved = $data['is_approved'];
        $puor->date_approved = $date_approved->format('Y-m-d');
        $puor->save();
        foreach($data['purchase_order_details'] as $detail) {
            $purchase_order_detail = new PurchaseOrderDetailsModel([
                'product_id' => $detail['product_id'],
                'purchase_order_id' => $puor->id,
                'price' => $detail['price'],
                'quantity' => $detail['quantity'],
                'total' => $detail['total']
            ]);
            $purchase_order_detail->save();

            $product_warehouse = new ProductWarehouseModel([
                'product_id' => $detail['product_id'],
                'warehouse_id' => $puor->warehouse_id,
                'quantity' => $detail['quantity']
            ]);
            $product_warehouse->save();
        }
        return response()->json(['code'=>200,'status'=>'success','message'=>$puor]);
    }

    public function show(PurchaseOrdersModel $puor) {
        return $puor;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $date = Carbon::parse($data['date']);
        $date_approved = Carbon::parse($data['date_approved']);
        $puor = PurchaseOrdersModel::find($id);
        $puor->code = $data['code'];
        $puor->warehouse_id = $data['warehouse_id'];
        $puor->supplier_id = $data['supplier_id'];
        $puor->employee_id = $data['employee_id'];
        $puor->supplier_document = $data['supplier_document'];
        $puor->date = $date->format('Y-m-d');
        $puor->discount = $data['discount'];
        $puor->sub_total = $data['sub_total'];
        $puor->total = $data['total'];
        $puor->status = $data['status'];
        $puor->is_approved = $data['is_approved'];
        $puor->date_approved = $date_approved->format('Y-m-d');
        $puor->update();
        if (isset($data['purchase_order_details']) && is_array($data['purchase_order_details'])) {
            foreach ($data['purchase_order_details'] as $pod) {
                $existingDetail = PurchaseOrderDetailsModel::where('Product', $pod['Product'])
                                                            ->where('PurchaseOrder', $id)
                                                            ->first();
                if ($existingDetail) {
                    $existingDetail->podPrice = $pod['podPrice'];
                    $existingDetail->podTax = $pod['podTax'];
                    $existingDetail->podDiscountMethod = $pod['podDiscountMethod'];
                    $existingDetail->podDiscount = $pod['podDiscount'];
                    $existingDetail->podQuantity = $pod['podQuantity'];
                    $existingDetail->podTotal = $pod['podTotal'];
                    $existingDetail->update();
                } else {
                    $newDetail = new PurchaseOrderDetailsModel([
                        'Product' => $pod['Product'],
                        'PurchaseOrder' => $id,
                        'podPrice' => $pod['podPrice'],
                        'podTax' => $pod['podTax'],
                        'podDiscountMethod' => $pod['podDiscountMethod'],
                        'podDiscount' => $pod['podDiscount'],
                        'podQuantity' => $pod['podQuantity'],
                        'podTotal' => $pod['podTotal']
                    ]);
                    $newDetail->save();
                }
            }
        }
        $currentDetails = PurchaseOrderDetailsModel::where('PurchaseOrder', $id)->pluck('Product');
        $incomingDetails = collect($data['purchase_order_details'])->pluck('Product');
        $detailsToDelete = $currentDetails->diff($incomingDetails);
        
        PurchaseOrderDetailsModel::where('PurchaseOrder', $id)
                                ->whereIn('Product', $detailsToDelete)
                                ->delete();
        return response()->json(['code'=>200,'status'=>'success','message'=>$request->all()]);
    }

    public function destroy($id) {
        $puor = PurchaseOrdersModel::find($id);
        if (!$puor) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $puor->deleted_at = now();
        $puor->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = PurchaseOrdersModel::max('id');
        return response()->json(['ultimo_id' => $ultimoId]);
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
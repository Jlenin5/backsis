<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseOrderExcel;
use App\Models\ProductWarehouseModel;
use App\Models\SaleOrderDetailsModel;
use App\Models\SaleOrdersModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleOrdersController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
            $perPage = $request->query('per_page', 10);
            $searchText = $request->query('search_text');
            $offset = ($page - 1) * $perPage;
            $query = SaleOrdersModel::with(
                    'sale_order_details','warehouses','clients','employees'
                )
                ->whereNull('deleted_at');

            if ($searchText) {
                $query->where(function ($query) use ($searchText) {
                    $query->where('code', 'LIKE', "%{$searchText}%")
                            ->orWhereHas('clients', function ($query) use ($searchText) {
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

            $saor = $query->offset($offset)
                    ->limit($perPage)
                    ->orderBy('created_at', 'desc')
                    ->get();

            $totalRows = SaleOrdersModel::whereNull('deleted_at')->count();

            return response()->json([
                'data' => $saor,
                'totalRows' => $totalRows
            ]);
    }

    public function getId($id) {
        $saor = SaleOrdersModel::with('sale_order_details','warehouses','clients','employees')->whereNull('deleted_at')->findOrFail($id);
        if (!$saor) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $saor;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $date = Carbon::parse($data['date']);
        $due_date = Carbon::parse($data['due_date']);
        $date_approved = Carbon::parse($data['date_approved']);
        $saor = new SaleOrdersModel;
        $saor->code = $data['code'];
        $saor->warehouse_id = $data['warehouse_id'];
        $saor->client_id = $data['client_id'];
        $saor->employee_id = $data['employee_id'];
        $saor->date = $date->format('Y-m-d');
        $saor->due_date = $due_date->format('Y-m-d');
        $saor->tax = $data['tax'];
        $saor->tax_total = $data['tax_total'];
        $saor->shipping = $data['shipping'];
        $saor->discount = $data['discount'];
        $saor->sub_total = $data['sub_total'];
        $saor->total = $data['total'];
        $saor->status = $data['status'];
        $saor->is_approved = $data['is_approved'];
        $saor->date_approved = $date_approved->format('Y-m-d');
        $saor->save();

        foreach($data['sale_order_details'] as $detail) {
            $sale_order_detail = new SaleOrderDetailsModel([
                'product_name' => $detail['product_name'],
                'product_id' => $detail['product_id'],
                'sale_order_id' => $saor->id,
                'price' => $detail['price'],
                'quantity' => $detail['quantity'],
                'discount_method' => $detail['discount_method'],
                'discount' => $detail['discount'],
                'total' => $detail['total']
            ]);
            $sale_order_detail->save();

            $product_warehouse = new ProductWarehouseModel([
                'product_id' => $detail['product_id'],
                'warehouse_id' => $saor->warehouse_id,
                'quantity' => $detail['quantity']
            ]);
            $product_warehouse->save();
        }
        return response()->json(['code'=>200,'status'=>'success','message'=>$saor]);
    }

    public function show(SaleOrdersModel $sale_order) {
        return $sale_order;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $date = Carbon::parse($data['date']);
        $due_date = Carbon::parse($data['due_date']);
        $date_approved = Carbon::parse($data['date_approved']);
        $saor = SaleOrdersModel::find($id);
        $saor->code = $data['code'];
        $saor->warehouse_id = $data['warehouse_id'];
        $saor->client_id = $data['client_id'];
        $saor->employee_id = $data['employee_id'];
        $saor->date = $date->format('Y-m-d');
        $saor->due_date = $due_date->format('Y-m-d');
        $saor->tax = $data['tax'];
        $saor->tax_total = $data['tax_total'];
        $saor->shipping = $data['shipping'];
        $saor->discount = $data['discount'];
        $saor->sub_total = $data['sub_total'];
        $saor->total = $data['total'];
        $saor->status = $data['status'];
        $saor->is_approved = $data['is_approved'];
        $saor->date_approved = $date_approved->format('Y-m-d');
        $saor->update();
        if (isset($data['sale_order_details']) && is_array($data['sale_order_details'])) {
            foreach ($data['sale_order_details'] as $pod) {
                $existingDetail = SaleOrderDetailsModel::where('Product', $pod['Product'])
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
                    $newDetail = new SaleOrderDetailsModel([
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
        $currentDetails = SaleOrderDetailsModel::where('PurchaseOrder', $id)->pluck('Product');
        $incomingDetails = collect($data['sale_order_details'])->pluck('Product');
        $detailsToDelete = $currentDetails->diff($incomingDetails);
        
        SaleOrderDetailsModel::where('PurchaseOrder', $id)
                                ->whereIn('Product', $detailsToDelete)
                                ->delete();
        return response()->json(['code'=>200,'status'=>'success','message'=>$request->all()]);
    }

    public function destroy($id) {
        $saor = SaleOrdersModel::find($id);
        if (!$saor) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $saor->deleted_at = now();
        $saor->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = SaleOrdersModel::max('id');
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
        
        foreach ($ids as $id) {
            SaleOrdersModel::whereId($id)->update([
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

        $query = SaleOrdersModel::with(
            'sale_order_details','warehouses','clients','employees'
        )
        ->whereNull('deleted_at');

        if ($searchText) {
            $query->where(function ($query) use ($searchText) {
                $query->where('code', 'LIKE', "%{$searchText}%")
                        ->orWhereHas('currencies', function ($query) use ($searchText) {
                            $query->where('curName', 'LIKE', "%{$searchText}%");
                        })
                        ->orWhereHas('clients', function ($query) use ($searchText) {
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

        $saor = $query->offset($offset)
                  ->limit($perPage)
                  ->orderBy('created_at', 'desc')
                  ->get();
            
        $fileName = 'archivo.xlsx';
        // $filePath = storage_path('app/exports/' . $fileName);

        return Excel::download(new PurchaseOrderExcel($saor), 'orden_de_compra.xlsx');
        // return response()->json(['message' => $request]);
    }

    public function exportPDF(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $searchText = $request->query('search_text');

        $offset = ($page - 1) * $perPage;

        $query = SaleOrdersModel::with(
            'sale_order_details','warehouses','clients','employees'
        )
        ->whereNull('deleted_at');

        if ($searchText) {
            $query->where(function ($query) use ($searchText) {
                $query->where('code', 'LIKE', "%{$searchText}%")
                        ->orWhereHas('currencies', function ($query) use ($searchText) {
                            $query->where('curName', 'LIKE', "%{$searchText}%");
                        })
                        ->orWhereHas('clients', function ($query) use ($searchText) {
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

        $sale_orders = $query->offset($offset)
                  ->limit($perPage)
                  ->orderBy('created_at', 'desc')
                  ->get();

        $pdf = PDF::loadView('export.pdf.sale_order', compact('sale_orders'));
        return $pdf->stream("OC" . date('Y-m-d h:i:s') . ".pdf");
    }

    public function exportPDFId($id) {
        $sale_order = SaleOrdersModel::with('sale_order_details','warehouses','clients','employees')->where('deleted_at',null)->findOrFail($id);
        if (!$sale_order) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        $pdf = PDF::loadView('export.pdf.sale_order_id', compact('sale_order'));
        return $pdf->stream("OC" . date('Y-m-d h:i:s') . ".pdf");
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderDetailsModel;
use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseOrdersController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        $puor = PurchaseOrdersModel::with(
                'purchaseOrderDetails', 'serialNumber', 'currencies', 'companies', 'warehouses', 'suppliers', 'users'
            )
            ->whereNull('deleted_at')
            ->offset($offset)
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
        $puor = PurchaseOrdersModel::with('purchaseOrderDetails','serialNumber','currencies','companies','warehouses','suppliers','users')->where('deleted_at',null)->findOrFail($id);
        if (!$puor) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $puor;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $startDate = Carbon::parse($data['puorStartDate']);
        $endDate = Carbon::parse($data['puorEndDate']);
        $puor = new PurchaseOrdersModel;
        $puor->id = $data['id'];
        $puor->SerialNumber = $data['SerialNumber'];
        $puor->puorNumber = $data['puorNumber'];
        $puor->Currency = $data['Currency'];
        $puor->Company = $data['Company'];
        $puor->Warehouse = $data['Warehouse'];
        $puor->Supplier = $data['Supplier'];
        $puor->User = $data['User'];
        $puor->puorStartDate = $startDate->format('Y-m-d');
        $puor->puorEndDate = $endDate->format('Y-m-d');
        $puor->puorSubtotal = $data['puorSubtotal'];
        $puor->puorTax = $data['puorTax'];
        $puor->puorTotal = $data['puorTotal'];
        $puor->save();
        foreach($data['purchase_order_details'] as $prodDetail) {
            $puorDetail = new PurchaseOrderDetailsModel([
                'Product' => $prodDetail['id'],
                'PurchaseOrder' => $data['id'],
                'podPrice' => $prodDetail['podPrice'],
                'podTax' => $prodDetail['podTax'],
                'podDiscountMethod' => $prodDetail['podDiscountMethod'],
                'podDiscount' => $prodDetail['podDiscount'],
                'podQuantity' => $prodDetail['podQuantity'],
                'podTotal' => $prodDetail['podTotal']
            ]);
            $puorDetail->save();
        }
        return response()->json(['code'=>200,'status'=>'success','message'=>$puor]);
    }

    public function show(PurchaseOrdersModel $puor) {
        return $puor;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $startDate = Carbon::parse($data['puorStartDate']);
        $endDate = Carbon::parse($data['puorEndDate']);
        $puor = PurchaseOrdersModel::find($id);
        $puor->SerialNumber = $data['SerialNumber'];
        $puor->puorNumber = $data['puorNumber'];
        $puor->Currency = $data['Currency'];
        $puor->Company = $data['Company'];
        $puor->Warehouse = $data['Warehouse'];
        $puor->Supplier = $data['Supplier'];
        $puor->User = $data['User'];
        $puor->puorStartDate = $startDate->format('Y-m-d');
        $puor->puorEndDate = $endDate->format('Y-m-d');
        $puor->puorSubtotal = $data['puorSubtotal'];
        $puor->puorTax = $data['puorTax'];
        $puor->puorTotal = $data['puorTotal'];
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
}
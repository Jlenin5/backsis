<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderDetailsModel;
use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseOrdersController extends Controller {
    public function index() {
        $puor = PurchaseOrdersModel::with('purchaseOrderDetails','serialNumber','currencies','companies','branchOffices','suppliers','users')->get();
        return $puor;
    }

    public function getId($id) {
        $puor = PurchaseOrdersModel::with('purchaseOrderDetails','serialNumber','currencies','companies','branchOffices','suppliers','users')->findOrFail($id);
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
        $puor->BranchOffice = $data['BranchOffice'];
        $puor->Supplier = $data['Supplier'];
        $puor->User = $data['User'];
        $puor->puorStartDate = $startDate->format('Y-m-d');
        $puor->puorEndDate = $endDate->format('Y-m-d');
        $puor->puorSubtotal = $data['puorSubtotal'];
        $puor->puorIgv = $data['puorIgv'];
        $puor->puorTotal = $data['puorTotal'];
        // $puor->save();
        foreach($data['products'] as $products) {
            $puorDetail = new PurchaseOrderDetailsModel([
                'podProdName' => $products['prodName'],
                'podProdPrice' => $products['prodSalePrice'],
                'Product' => $products['id'],
                'Quotation' => $data['id'],
                'podQuantity' => $data['prodStock'],
                'podSubTotal' => $data['prodSalePrice'],
                'podTotal' => $data['prodSalePrice']
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
        $puor->BranchOffice = $data['BranchOffice'];
        $puor->Supplier = $data['Supplier'];
        $puor->User = $data['User'];
        $puor->puorStartDate = $startDate->format('Y-m-d');
        $puor->puorEndDate = $endDate->format('Y-m-d');
        $puor->puorSubtotal = $data['puorSubtotal'];
        $puor->puorIgv = $data['puorIgv'];
        $puor->puorTotal = $data['puorTotal'];
        // $puor->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>$puor]);
    }

    public function destroy($id) {
        $puor = PurchaseOrdersModel::find($id);
        if (!$puor) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $puor->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = PurchaseOrdersModel::max('id');
        return response()->json(['ultimo_id' => $ultimoId]);
    }

    public function destroyMultiple(Request $request) {
        $id = $request->input('dataId', []);
        if (empty($id)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se proporcionaron datos para eliminar.'
            ], 400);
        }
        PurchaseOrdersModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
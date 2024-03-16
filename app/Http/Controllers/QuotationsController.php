<?php

namespace App\Http\Controllers;

use App\Models\QuotationsModel;
use App\Models\QuoteDetailsModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuotationsController extends Controller {
    public function index() {
        $qt = QuotationsModel::with('quoteDetails','serialNumber','currencies','companies','branchOffices','clients','users')->get();
        return $qt;
    }

    public function getId($id) {
        $qt = QuotationsModel::with('quoteDetails','serialNumber','currencies','companies','branchOffices','clients','users')->findOrFail($id);
        if (!$qt) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $qt;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $startDate = Carbon::parse($data['qtStartDate']);
        $endDate = Carbon::parse($data['qtEndDate']);
        $qt = new QuotationsModel;
        $qt->id = $data['id'];
        $qt->SerialNumber = $data['SerialNumber'];
        $qt->qtNumber = $data['qtNumber'];
        $qt->Currency = $data['Currency'];
        $qt->Company = $data['Company'];
        $qt->BranchOffice = $data['BranchOffice'];
        $qt->Client = $data['Client'];
        $qt->User = $data['User'];
        $qt->qtStartDate = $startDate->format('Y-m-d');
        $qt->qtEndDate = $endDate->format('Y-m-d');
        $qt->qtSubtotal = $data['qtSubtotal'];
        $qt->qtIgv = $data['qtIgv'];
        $qt->qtTotal = $data['qtTotal'];
        // $qt->save();
        foreach($data['products'] as $products) {
            $qtDetail = new QuoteDetailsModel([
                'qtdProdName' => $products['prodName'],
                'qtdProdPrice' => $products['prodSalePrice'],
                'Product' => $products['id'],
                'Quotation' => $data['id'],
                'qtdQuantity' => $data['prodStock'],
                'qtdSubTotal' => $data['prodSalePrice'],
                'qtdTotal' => $data['prodSalePrice']
            ]);
            $qtDetail->save();
        }
        return response()->json(['code'=>200,'status'=>'success','message'=>$qt]);
    }

    public function show(QuotationsModel $qt) {
        return $qt;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $startDate = Carbon::parse($data['qtStartDate']);
        $endDate = Carbon::parse($data['qtEndDate']);
        $qt = QuotationsModel::find($id);
        $qt->SerialNumber = $data['SerialNumber'];
        $qt->qtNumber = $data['qtNumber'];
        $qt->Currency = $data['Currency'];
        $qt->Company = $data['Company'];
        $qt->BranchOffice = $data['BranchOffice'];
        $qt->Client = $data['Client'];
        $qt->User = $data['User'];
        $qt->qtStartDate = $startDate->format('Y-m-d');
        $qt->qtEndDate = $endDate->format('Y-m-d');
        $qt->qtSubtotal = $data['qtSubtotal'];
        $qt->qtIgv = $data['qtIgv'];
        $qt->qtTotal = $data['qtTotal'];
        // $qt->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>$qt]);
    }

    public function destroy($id) {
        $qt = QuotationsModel::find($id);
        if (!$qt) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $qt->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = QuotationsModel::max('id');
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
        QuotationsModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\InvoicesModel;
use Illuminate\Http\Request;

class InvoicesController extends Controller {
    public function index() {
        $inv = InvoicesModel::get();
        return $inv;
    }

    public function getId($id) {
        $inv = InvoicesModel::find($id);
        if (!$inv) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$inv];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $inv = new InvoicesModel;
        $inv->id = $data['id'];
        $inv->SerialNumber = $data['SerialNumber'];
        $inv->invNumber = $data['invNumber'];
        $inv->Currency = $data['Currency'];
        $inv->Company = $data['Company'];
        $inv->Client = $data['Client'];
        $inv->Employee = $data['Employee'];
        $inv->invSubtotal = $data['invSubtotal'];
        $inv->invIgv = $data['invIgv'];
        $inv->invTotal = $data['invTotal'];
        $inv->PaymentMethod = $data['PaymentMethod'];
        $inv->DocumentStatus = $data['DocumentStatus'];
        $inv->invCreatedAt = $data['invCreatedAt'];
        $inv->invDeletedAt = $data['invDeletedAt'];
        $inv->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(InvoicesModel $inv) {
        return $inv;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $inv = InvoicesModel::find($id);
        $inv->SerialNumber = $data['SerialNumber'];
        $inv->invNumber = $data['invNumber'];
        $inv->Currency = $data['Currency'];
        $inv->Company = $data['Company'];
        $inv->Client = $data['Client'];
        $inv->Employee = $data['Employee'];
        $inv->invSubtotal = $data['invSubtotal'];
        $inv->invIgv = $data['invIgv'];
        $inv->invTotal = $data['invTotal'];
        $inv->PaymentMethod = $data['PaymentMethod'];
        $inv->DocumentStatus = $data['DocumentStatus'];
        $inv->invCreatedAt = $data['invCreatedAt'];
        $inv->invDeletedAt = $data['invDeletedAt'];
        $inv->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $inv = InvoicesModel::find($id);
        if (!$inv) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $inv->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = InvoicesModel::max('id');
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
        InvoicesModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
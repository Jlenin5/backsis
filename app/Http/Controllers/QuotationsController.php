<?php

namespace App\Http\Controllers;

use App\Models\QuotationsModel;
use Illuminate\Http\Request;

class QuotationsController extends Controller {
    public function index() {
        $qt = QuotationsModel::get();
        return $qt;
    }

    public function getId($id) {
        $qt = QuotationsModel::find($id);
        if (!$qt) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$qt];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $qt = new QuotationsModel;
        $qt->id = $data['id'];
        $qt->SerialNumber = $data['SerialNumber'];
        $qt->qtNumber = $data['qtNumber'];
        $qt->Currency = $data['Currency'];
        $qt->Company = $data['Company'];
        $qt->Client = $data['Client'];
        $qt->Employee = $data['Employee'];
        $qt->qtSubtotal = $data['qtSubtotal'];
        $qt->qtIgv = $data['qtIgv'];
        $qt->qtTotal = $data['qtTotal'];
        $qt->qtCreatedAt = $data['qtCreatedAt'];
        $qt->qtDeletedAt = $data['qtDeletedAt'];
        $qt->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(QuotationsModel $qt) {
        return $qt;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $qt = QuotationsModel::find($id);
        $qt->SerialNumber = $data['SerialNumber'];
        $qt->qtNumber = $data['qtNumber'];
        $qt->Currency = $data['Currency'];
        $qt->Company = $data['Company'];
        $qt->Client = $data['Client'];
        $qt->Employee = $data['Employee'];
        $qt->qtSubtotal = $data['qtSubtotal'];
        $qt->qtIgv = $data['qtIgv'];
        $qt->qtTotal = $data['qtTotal'];
        $qt->qtCreatedAt = $data['qtCreatedAt'];
        $qt->qtDeletedAt = $data['qtDeletedAt'];
        $qt->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
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
<?php

namespace App\Http\Controllers;

use App\Models\PaymentTypesModel;
use Illuminate\Http\Request;

class PaymentTypesController extends Controller {

    public function index() {
        $payt = PaymentTypesModel::get();
        return $payt;
    }

    public function getId($id) {
        $payt = PaymentTypesModel::find($id);
        if (!$payt) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$payt];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $payt = new PaymentTypesModel;
        $payt->id = $data['id'];
        $payt->paytName = $data['paytName'];
        $payt->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(PaymentTypesModel $payt) {
        return $payt;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $payt = PaymentTypesModel::find($id);
        $payt->paytName = $data['paytName'];
        $payt->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $payt = PaymentTypesModel::find($id);
        if (!$payt) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $payt->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = PaymentTypesModel::max('id');
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
        PaymentTypesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethodsModel;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller {

    public function index() {
        $paym = PaymentMethodsModel::get();
        return $paym;
    }

    public function getId($id) {
        $paym = PaymentMethodsModel::find($id);
        if (!$paym) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$paym];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $paym = new PaymentMethodsModel;
        $paym->id = $data['id'];
        $paym->paymName = $data['paymName'];
        $paym->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(PaymentMethodsModel $paym) {
        return $paym;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $paym = PaymentMethodsModel::find($id);
        $paym->paymName = $data['paymName'];
        $paym->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $paym = PaymentMethodsModel::find($id);
        if (!$paym) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $paym->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = PaymentMethodsModel::max('id');
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
        PaymentMethodsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
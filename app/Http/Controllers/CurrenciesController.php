<?php

namespace App\Http\Controllers;

use App\Models\CurrenciesModel;
use Illuminate\Http\Request;

class CurrenciesController extends Controller {
    public function index() {
        $cur = CurrenciesModel::get();
        return $cur;
    }

    public function getId($id) {
        $cur = CurrenciesModel::find($id);
        if (!$cur) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$cur];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $cur = new CurrenciesModel;
        $cur->id = $data['id'];
        $cur->curName = $data['curName'];
        $cur->curConvert = $data['curConvert'];
        $cur->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(CurrenciesModel $cur) {
        return $cur;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $cur = CurrenciesModel::find($id);
        $cur->curName = $data['curName'];
        $cur->curConvert = $data['curConvert'];
        $cur->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $cur = CurrenciesModel::find($id);
        if (!$cur) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $cur->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = CurrenciesModel::max('id');
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
        CurrenciesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
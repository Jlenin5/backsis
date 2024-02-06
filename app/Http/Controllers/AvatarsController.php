<?php

namespace App\Http\Controllers;

use App\Models\AvatarsModel;
use Illuminate\Http\Request;

class AvatarsController extends Controller {
    public function index() {
        $ava = AvatarsModel::get();
        return $ava;
    }

    public function getId($id) {
        $ava = AvatarsModel::find($id);
        if (!$ava) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$ava];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $ava = new AvatarsModel;
        $ava->id = $data['id'];
        $ava->avaName = $data['avaName'];
        $ava->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(AvatarsModel $ava) {
        return $ava;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $ava = AvatarsModel::find($id);
        $ava->avaName = $data['avaName'];
        $ava->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $ava = AvatarsModel::find($id);
        if (!$ava) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $ava->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = AvatarsModel::max('id');
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
        AvatarsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
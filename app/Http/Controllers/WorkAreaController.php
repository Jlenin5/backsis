<?php

namespace App\Http\Controllers;

use App\Models\WorkAreaModel;
use Illuminate\Http\Request;

class WorkAreaController extends Controller {
    public function index() {
        $wa = WorkAreaModel::get();
        return $wa;
    }

    public function getId($id) {
        $wa = WorkAreaModel::find($id);
        if (!$wa) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$wa];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $wa = new WorkAreaModel;
        $wa->id = $data['id'];
        $wa->waName = $data['waName'];
        $wa->waState = $data['waState'];
        $wa->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(WorkAreaModel $wa) {
        return $wa;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $wa = WorkAreaModel::find($id);
        $wa->waName = $data['waName'];
        $wa->waState = $data['waState'];
        $wa->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $wa = WorkAreaModel::find($id);
        if (!$wa) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $wa->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = WorkAreaModel::max('id');
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
        WorkAreaModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
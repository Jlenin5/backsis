<?php

namespace App\Http\Controllers;

use App\Models\SerialNumberModel;
use Illuminate\Http\Request;

class SerialNumberController extends Controller {
    public function index() {
        $sn = SerialNumberModel::get();
        return $sn;
    }

    public function getId($id) {
        $sn = SerialNumberModel::find($id);
        if (!$sn) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$sn];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $sn = new SerialNumberModel;
        $sn->id = $data['id'];
        $sn->snSerie = $data['snSerie'];
        $sn->snNumber = $data['snNumber'];
        $sn->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(SerialNumberModel $sn) {
        return $sn;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $sn = SerialNumberModel::find($id);
        $sn->snSerie = $data['snSerie'];
        $sn->snNumber = $data['snNumber'];
        $sn->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $sn = SerialNumberModel::find($id);
        if (!$sn) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $sn->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = SerialNumberModel::max('id');
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
        SerialNumberModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
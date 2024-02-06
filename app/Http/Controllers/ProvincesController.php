<?php

namespace App\Http\Controllers;

use App\Models\ProvincesModel;
use Illuminate\Http\Request;

class ProvincesController extends Controller {
    public function index() {
        $prov = ProvincesModel::get();
        return $prov;
    }

    public function getId($id) {
        $prov = ProvincesModel::find($id);
        if (!$prov) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$prov];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $prov = new ProvincesModel;
        $prov->id = $data['id'];
        $prov->provName = $data['provName'];
        $prov->Department = $data['Department'];
        $prov->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProvincesModel $prov) {
        return $prov;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $prov = ProvincesModel::find($id);
        $prov->provName = $data['provName'];
        $prov->Department = $data['Department'];
        $prov->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $prov = ProvincesModel::find($id);
        if (!$prov) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $prov->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ProvincesModel::max('id');
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
        ProvincesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\DistrictsModel;
use Illuminate\Http\Request;

class DistrictsController extends Controller {
    public function index() {
        $dis = DistrictsModel::get();
        return $dis;
    }

    public function getId($id) {
        $dis = DistrictsModel::find($id);
        if (!$dis) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$dis];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $dis = new DistrictsModel;
        $dis->id = $data['id'];
        $dis->disName = $data['disName'];
        $dis->Province = $data['Province'];
        $dis->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(DistrictsModel $dis) {
        return $dis;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $dis = DistrictsModel::find($id);
        $dis->disName = $data['disName'];
        $dis->Province = $data['Province'];
        $dis->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $dis = DistrictsModel::find($id);
        if (!$dis) {
            return response()->json(['message' => 'Solidistud no encontrada'], 404);
        }
        $dis->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = DistrictsModel::max('id');
        return response()->json(['ultimo_id' => $ultimoId]);
    }

    public function destroyMultiple(Request $request) {
        $ids = $request->input('dataId', []);
        if (empty($ids)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se propordisonaron datos para eliminar.'
            ], 400);
        }
        DistrictsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\DepartmentsModel;
use Illuminate\Http\Request;

class DepartmentsController extends Controller {
    public function index() {
        $dep = DepartmentsModel::get();
        return $dep;
    }

    public function getId($id) {
        $dep = DepartmentsModel::find($id);
        if (!$dep) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$dep];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $dep = new DepartmentsModel;
        $dep->id = $data['id'];
        $dep->depName = $data['depName'];
        $dep->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(DepartmentsModel $dep) {
        return $dep;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $dep = DepartmentsModel::find($id);
        $dep->depName = $data['depName'];
        $dep->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $dep = DepartmentsModel::find($id);
        if (!$dep) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $dep->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = DepartmentsModel::max('id');
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
        DepartmentsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
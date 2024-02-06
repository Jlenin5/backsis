<?php

namespace App\Http\Controllers;

use App\Models\RolesModel;
use Illuminate\Http\Request;

class RolesController extends Controller {

    public function index() {
        $rol = RolesModel::get();
        return $rol;
    }

    public function getId($id) {
        $rol = RolesModel::find($id);
        if (!$rol) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$rol];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $rol = new RolesModel;
        $rol->id = $data['id'];
        $rol->rolName = $data['rolName'];
        $rol->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(RolesModel $rol) {
        return $rol;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $rol = RolesModel::find($id);
        $rol->rolName = $data['rolName'];
        $rol->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $rol = RolesModel::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $rol->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = RolesModel::max('id');
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
        RolesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
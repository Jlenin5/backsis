<?php

namespace App\Http\Controllers;

use App\Models\NamesMenuModel;
use Illuminate\Http\Request;

class NamesMenuController extends Controller {

    public function index() {
        $menu = NamesMenuModel::get();
        return $menu;
    }

    public function getId($id) {
        $menu = NamesMenuModel::find($id);
        if (!$menu) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$menu];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $menu = new NamesMenuModel;
        $menu->id = $data['id'];
        $menu->menuName = $data['menuName'];
        $menu->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(NamesMenuModel $menu) {
        return $menu;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $menu = NamesMenuModel::find($id);
        $menu->menuName = $data['menuName'];
        $menu->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $menu = NamesMenuModel::find($id);
        if (!$menu) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $menu->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = NamesMenuModel::max('id');
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
        NamesMenuModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
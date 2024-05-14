<?php

namespace App\Http\Controllers;

use App\Models\CategoriesModel;
use Illuminate\Http\Request;

class CategoriesController extends Controller {
    public function index() {
        $cate = CategoriesModel::get();
        return $cate;
    }

    public function getId($id) {
        $cate = CategoriesModel::find($id);
        if (!$cate) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$cate];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $cate = new CategoriesModel;
        $cate->name = $data['name'];
        $cate->status = $data['status'];
        $cate->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(CategoriesModel $cate) {
        return $cate;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $cate = CategoriesModel::find($id);
        $cate->name = $data['name'];
        $cate->status = $data['status'];
        $cate->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $cate = CategoriesModel::find($id);
        if (!$cate) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $cate->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = CategoriesModel::max('id');
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
        CategoriesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
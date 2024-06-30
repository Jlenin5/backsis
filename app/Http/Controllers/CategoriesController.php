<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\Store;
use App\Models\CategoriesModel;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller {

    use ApiResponser;

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

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            CategoriesModel::create($request->input())->withData([])
        );
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
<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Categories\Store;
use App\Http\Requests\Categories\Update;
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

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            CategoriesModel::create($request->input())->withData([])
        );
    }

    public function show(CategoriesModel $categories) {
        return $this->showOne($categories->withData([]));
    }

    public function update(Update $request, CategoriesModel $categories) {
        $request['user_update_id'] = auth()->user()->id;
        $categories->update($request->input());
        return $this->updated($categories->withData([]));
    }

    public function destroy(CategoriesModel $categories) {
        return ResponseJson::destroy($categories->delete());
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
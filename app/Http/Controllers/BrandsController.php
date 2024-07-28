<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Brands\Store;
use App\Http\Requests\Brands\Update;
use Illuminate\Http\Request;
use App\Models\BrandsModel;
use App\Traits\ApiResponser;

class BrandsController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            BrandsModel::whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            BrandsModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            BrandsModel::create($request->input())->withData([])
        );
    }

    public function show(BrandsModel $brands) {
        return $this->showOne($brands->withData([]));
    }

    public function update(Update $request, BrandsModel $brands) {
        $request['user_update_id'] = auth()->user()->id;
        $brands->update($request->input());
        return $this->updated($brands->withData([]));
    }

    public function destroy(BrandsModel $brands) {
        return ResponseJson::destroy($brands->delete());
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
        BrandsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
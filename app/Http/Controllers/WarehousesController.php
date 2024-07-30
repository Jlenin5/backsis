<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Warehouses\Store;
use App\Http\Requests\Warehouses\Update;
use App\Models\WarehousesModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class WarehousesController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            WarehousesModel::search(['name','phone'])
                ->filters()
                ->whereNull('deleted_at')
                ->with(['employee','department','province','district','company','branch_office'])
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            WarehousesModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            WarehousesModel::create($request->input())->withData([])
        );
    }

    public function show(WarehousesModel $warehouse) {
        return $this->showOne($warehouse->withData([]));
    }

    public function update(Update $request, WarehousesModel $warehouse) {
        $request['user_update_id'] = auth()->user()->id;
        $warehouse->update($request->input());
        return $this->updated($warehouse->withData([]));
    }

    public function destroy(WarehousesModel $warehouse) {
        return ResponseJson::destroy($warehouse->delete());
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

        foreach ($ids as $wh_id) {
            WarehousesModel::whereId($wh_id)->update([
                'deleted_at' => now(),
            ]);
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
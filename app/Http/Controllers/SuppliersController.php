<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Suppliers\Store;
use App\Http\Requests\Suppliers\Update;
use App\Models\SuppliersModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class SuppliersController extends Controller {

    use ApiResponser;
    
    public function index(Request $request) {
        
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            SuppliersModel::search(['name','phone'])
                ->whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            SuppliersModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            SuppliersModel::create($request->input())->withData([])
        );
    }

    public function show(SuppliersModel $suppliers) {
        return $this->showOne($suppliers->withData([]));
    }

    public function update(Update $request, SuppliersModel $suppliers) {
        $request['user_update_id'] = auth()->user()->id;
        $suppliers->update($request->input());
        return $this->updated($suppliers->withData([]));
    }

    public function destroy(SuppliersModel $suppliers) {
        return ResponseJson::destroy($suppliers->delete());
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
        foreach ($ids as $id) {
            SuppliersModel::whereId($id)->update([
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
<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\BranchOffices\Store;
use App\Http\Requests\BranchOffices\Update;
use App\Models\BranchOfficesModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class BranchOfficesController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            BranchOfficesModel::search(['name','phone'])
                ->filters()
                ->whereNull('deleted_at')
                ->withDataAll(['company','user.employee','department','province','district'])
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            BranchOfficesModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            BranchOfficesModel::create($request->input())->withData([])
        );
    }

    public function show(BranchOfficesModel $branch_offices) {
        return $this->showOne($branch_offices->withData([]));
    }

    public function update(Update $request, BranchOfficesModel $branch_offices) {
        $request['user_update_id'] = auth()->user()->id;
        $branch_offices->update($request->input());
        return $this->updated($branch_offices->withData([]));
    }

    public function destroy(BranchOfficesModel $branch_offices) {
        return ResponseJson::destroy($branch_offices->delete());
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

        foreach ($ids as $bo_id) {
            BranchOfficesModel::whereId($bo_id)->update([
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
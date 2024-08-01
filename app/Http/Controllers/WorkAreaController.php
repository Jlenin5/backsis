<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\WorkAreas\Store;
use App\Http\Requests\WorkAreas\Update;
use App\Models\WorkAreaModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class WorkAreaController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            WorkAreaModel::whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            WorkAreaModel::whereNull('deleted_at')->count()
        );
    }

    public function show(WorkAreaModel $work_area) {
        return ResponseJson::destroy($work_area->delete());
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            WorkAreaModel::create($request->input())->withData([])
        );
    }

    public function update(Update $request, WorkAreaModel $work_area) {
        $request['user_update_id'] = auth()->user()->id;
        $work_area->update($request->input());
        return $this->updated($work_area->withData([]));
    }

    public function destroy(WorkAreaModel $work_area) {
        return ResponseJson::destroy($work_area->delete());
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
            WorkAreaModel::whereId($id)->update([
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
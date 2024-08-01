<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\JobPositions\Store;
use App\Http\Requests\JobPositions\Update;
use App\Models\JobPositionModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class JobPositionController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            JobPositionModel::whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            JobPositionModel::whereNull('deleted_at')->count()
        );
    }

    public function show(JobPositionModel $job_position) {
        return $this->showOne($job_position->withData([]));
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            JobPositionModel::create($request->input())->withData([])
        );
    }

    public function update(Update $request, JobPositionModel $job_position) {
        $request['user_update_id'] = auth()->user()->id;
        $job_position->update($request->input());
        return $this->updated($job_position->withData([]));
    }

    public function destroy(JobPositionModel $job_position) {
        return ResponseJson::destroy($job_position->delete());
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
            JobPositionModel::whereId($id)->update([
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
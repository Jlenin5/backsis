<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Employees\Store;
use App\Http\Requests\Employees\Update;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class EmployeeController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            EmployeeModel::withDataAll(['avatar','work_area','job_position'])
                ->whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            EmployeeModel::whereNull('deleted_at')->count()
        );
    }

    public function show(EmployeeModel $employee) {
        return $this->showOne($employee->withData([]));
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            EmployeeModel::create($request->input())->withData([])
        );
    }

    public function update(Update $request, EmployeeModel $employee) {
        $request['user_update_id'] = auth()->user()->id;
        $employee->update($request->input());
        return $this->updated($employee->withData([]));
    }

    public function destroy(EmployeeModel $employee) {
        return ResponseJson::destroy($employee->delete());
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

        foreach ($ids as $emp_id) {
            EmployeeModel::whereId($emp_id)->update([
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
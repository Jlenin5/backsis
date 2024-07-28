<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\MeasurementUnit\Store;
use App\Http\Requests\MeasurementUnit\Update;
use App\Models\MeasurementUnitModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class MeasurementUnitController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            MeasurementUnitModel::whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            MeasurementUnitModel::whereNull('deleted_at')->count()
        );
    }

    public function show(MeasurementUnitModel $measurement_unit) {
        return $this->showOne($measurement_unit->withData(['employee.avatar']));
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            MeasurementUnitModel::create($request->input())->withData([])
        );
    }

    public function update(Update $request, MeasurementUnitModel $measurement_unit) {
        $request['user_update_id'] = auth()->user()->id;
        $measurement_unit->update($request->input());
        return $this->updated($measurement_unit->withData([]));
    }

    public function destroy(MeasurementUnitModel $measurement_unit) {
        return ResponseJson::destroy($measurement_unit->delete());
    }

    public function getMaxId() {
        $ultimoId = MeasurementUnitModel::max('id');
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
        foreach ($ids as $id) {
            MeasurementUnitModel::whereId($id)->update([
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
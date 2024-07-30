<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Currencies\Store;
use App\Http\Requests\Currencies\Update;
use App\Models\CurrenciesModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CurrenciesController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            CurrenciesModel::whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            CurrenciesModel::whereNull('deleted_at')->count()
        );
    }

    public function show(CurrenciesModel $currency) {
        return $this->showOne($currency->withData([]));
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            CurrenciesModel::create($request->input())->withData([])
        );
    }

    public function update(Update $request, CurrenciesModel $currency) {
        $request['user_update_id'] = auth()->user()->id;
        $currency->update($request->input());
        return $this->updated($currency->withData([]));
    }

    public function destroy(CurrenciesModel $currency) {
        return ResponseJson::destroy($currency->delete());
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
        CurrenciesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
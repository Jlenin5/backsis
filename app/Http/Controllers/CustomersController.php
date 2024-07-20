<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Customers\Store;
use App\Http\Requests\Customers\Update;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Carbon\Carbon;

class CustomersController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            CustomerModel::search(['first_name','document_number'])
                ->whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            CustomerModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $data = $request->json()->all();
        $birthdate = Carbon::parse($data['birthdate']);
        $request['birthdate'] = $birthdate->format('Y-m-d');
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        return $this->stored(
            CustomerModel::create($request->input())->withData([])
        );
    }

    public function show(CustomerModel $customers) {
        return $this->showOne($customers->withData([]));
    }

    public function update(Update $request, CustomerModel $customers) {
        $data = $request->json()->all();
        $birthdate = Carbon::parse($data['birthdate']);
        $request['birthdate'] = $birthdate->format('Y-m-d');
        $request['user_update_id'] = auth()->user()->id;
        $customers->update($request->input());
        return $this->updated($customers->withData([]));
    }

    public function destroy(CustomerModel $customers) {
        return ResponseJson::destroy($customers->delete());
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
            CustomerModel::whereId($id)->update([
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
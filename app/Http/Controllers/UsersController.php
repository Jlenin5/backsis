<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Users\Store;
use App\Http\Requests\Users\Update;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller {

    use ApiResponser;
    
    public function index(Request $request) {

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            UsersModel::withDataAll(['employee','roles'])
                ->where('deleted_at',null)
                ->whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            UsersModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $uuid = Uuid::uuid4();
        $request['uuid'] = $uuid->toString();
        $request['password'] = bcrypt($request->password);
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        $user = UsersModel::create($request->input());
        // $user->syncRoles($request->role_ids);

        return $this->stored($user);
    }

    public function show(UsersModel $user) {
        return $this->showOne($user->withData(['employee.avatar']));
    }

    public function update(Update $request, UsersModel $user) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        $user->update($request->input());
        return $this->updated($user->withData([]));
    }

    public function destroy(UsersModel $user) {
        return ResponseJson::destroy($user->delete());
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

        foreach ($ids as $user_id) {
            UsersModel::whereId($user_id)->update([
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
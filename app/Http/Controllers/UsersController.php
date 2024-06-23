<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersModel\Store;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Traits\ApiResponser;

class UsersController extends Controller {

    use ApiResponser;
    
    public function index(Request $request) {

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            UsersModel::with('employee','rol')
                ->where('deleted_at',null)
                ->whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function store(Store $request) {
        
        $uuid = Uuid::uuid4();
        $request['uuid'] = $uuid->toString();
        $request['password'] = bcrypt(($request->password));
        $user = UsersModel::create($request->input());
        $user->syncRoles($request->role_ids);

        return $this->stored($user);
    }

    public function show(UsersModel $user) {
        return $this->showOne($user->with('employee','rol'));
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $user = UsersModel::find($id);
        $user->employee_id = $data['employee_id'];
        $user->display_name = $data['display_name'];
        $user->display_email = $data['display_email'];
        $user->password = $data['password'];
        $user->rol_id = $data['rol_id'];
        $user->uuid = $data['uuid'];
        $user->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $user = UsersModel::find($id);
        if (!$user) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $user->deleted_at = now();
        $user->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = UsersModel::max('id');
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
<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use Illuminate\Http\Request;

class UsersController extends Controller {
    
    public function index() {
        $user = UsersModel::with('employees','avatars','workAreas','jobPositions','roles')->get();
        return $user;
    }

    public function getId($id) {
        $user = UsersModel::with('employees','avatars','workAreas','jobPositions','roles')->find($id);
        if (!$user) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$user];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $user = new UsersModel;
        $user->id = $data['id'];
        $user->Employee = $data['Employee'];
        $user->Avatar = $data['Avatar'];
        $user->WorkArea = $data['WorkArea'];
        $user->JobPosition = $data['JobPosition'];
        $user->userDisplayName = $data['userDisplayName'];
        $user->userPassword = $data['userPassword'];
        $user->Rol = $data['Rol'];
        $user->uuid = $data['uuid'];
        $user->userCreatedAt = $data['userCreatedAt'];
        $user->userUpdatedAt = $data['userUpdatedAt'];
        $user->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(UsersModel $user) {
        return $user;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $user = UsersModel::find($id);
        $user->Employee = $data['Employee'];
        $user->Avatar = $data['Avatar'];
        $user->WorkArea = $data['WorkArea'];
        $user->JobPosition = $data['JobPosition'];
        $user->userDisplayName = $data['userDisplayName'];
        $user->userPassword = $data['userPassword'];
        $user->Rol = $data['Rol'];
        $user->uuid = $data['uuid'];
        $user->userCreatedAt = $data['userCreatedAt'];
        $user->userUpdatedAt = $data['userUpdatedAt'];
        $user->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $user = UsersModel::find($id);
        if (!$user) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = UsersModel::max('id');
        return response()->json(['ultimo_id' => $ultimoId]);
    }

    public function destroyMultiple(Request $request) {
        $id = $request->input('dataId', []);
        if (empty($id)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se proporcionaron datos para eliminar.'
            ], 400);
        }
        UsersModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
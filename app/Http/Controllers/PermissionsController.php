<?php

namespace App\Http\Controllers;

use App\Models\PermissionsModel;
use Illuminate\Http\Request;

class PermissionsController extends Controller {
  public function index()
  {
    $perm = PermissionsModel::get();
    return $perm;
  }

  public function getId($id) {
    $perm = PermissionsModel::find($id);
    if (!$perm) {
      return response()->json(['message' => 'No hay datos para mostrar'], 404);
    }
    return [$perm];
  }

  public function store(Request $request) {
    $data = $request->json()->all();
    $perm = new PermissionsModel;
    $perm->id = $data['id'];
    $perm->Rol = $data['Rol'];
    $perm->NamesMenu = $data['NamesMenu'];
    $perm->save();
    return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Agregado correctamente']);
  }

  public function show(PermissionsModel $perm) {
    return $perm;
  }

  public function update(Request $request, $id) {
    $data = $request->json()->all();
    $perm = PermissionsModel::find($id);
    $perm->Rol = $data['Rol'];
    $perm->NamesMenu = $data['NamesMenu'];
    $perm->update();
    return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Actualizado Correctamente']);
  }

  public function destroy($id) {
    $perm = PermissionsModel::find($id);
    if (!$perm) {
      return response()->json(['message' => 'Solicitud no encontrada'], 404);
    }
    $perm->delete();
    return response()->json(['message' => 'Eliminado correctamente']);
  }

  public function getMaxId() {
    $ultimoId = PermissionsModel::max('id');
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
    PermissionsModel::whereIn('id', $ids)->delete();
    return response()->json([
      'code' => 200,
      'status' => 'success',
      'message' => 'Elementos eliminadas correctamente.'
    ]);
  }

  public function rolesStore(Request $request, PermissionsModel $permission) {
    $permission->syncRoles($request->roles);
    return $this->success($permission->roles);
  }
}

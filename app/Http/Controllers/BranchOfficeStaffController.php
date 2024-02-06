<?php

namespace App\Http\Controllers;

use App\Models\BranchOfficeStaffModel;
use Illuminate\Http\Request;

class BranchOfficeStaffController extends Controller {
    public function index() {
        $bos = BranchOfficeStaffModel::get();
        return $bos;
    }

    public function getId($id) {
        $bos = BranchOfficeStaffModel::find($id);
        if (!$bos) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$bos];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $bos = new BranchOfficeStaffModel;
        $bos->id = $data['id'];
        $bos->User = $data['User'];
        $bos->Warehouse = $data['Warehouse'];
        $bos->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(BranchOfficeStaffModel $bos) {
        return $bos;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $bos = BranchOfficeStaffModel::find($id);
        $bos->User = $data['User'];
        $bos->Warehouse = $data['Warehouse'];
        $bos->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $bos = BranchOfficeStaffModel::find($id);
        if (!$bos) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $bos->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = BranchOfficeStaffModel::max('id');
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
        BranchOfficeStaffModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
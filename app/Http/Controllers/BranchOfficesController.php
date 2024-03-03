<?php

namespace App\Http\Controllers;

use App\Models\BranchOfficesModel;
use Illuminate\Http\Request;

class BranchOfficesController extends Controller {
    public function index() {
        $bo = BranchOfficesModel::with('users','districts')->get();
        return $bo;
    }

    public function getId($id) {
        $bo = BranchOfficesModel::with('users','districts')->find($id);
        if (!$bo) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$bo];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $bo = new BranchOfficesModel;
        $bo->id = $data['id'];
        $bo->boName = $data['boName'];
        $bo->boPhone = $data['boPhone'];
        $bo->boEmail = $data['boEmail'];
        $bo->District = $data['District'];
        $bo->boAddress = $data['boAddress'];
        $bo->User = $data['User'];
        $bo->boState = $data['boState'];
        $bo->boCreatedAt = now();
        $bo->boUpdatedAt = now();
        $bo->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(BranchOfficesModel $bo) {
        return $bo;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $bo = BranchOfficesModel::find($id);
        $bo->boName = $data['boName'];
        $bo->boPhone = $data['boPhone'];
        $bo->boEmail = $data['boEmail'];
        $bo->District = $data['District'];
        $bo->boAddress = $data['boAddress'];
        $bo->User = $data['User'];
        $bo->boState = $data['boState'];
        $bo->boCreatedAt = now();
        $bo->boUpdatedAt = now();
        $bo->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $bo = BranchOfficesModel::find($id);
        if (!$bo) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $bo->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = BranchOfficesModel::max('id');
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
        BranchOfficesModel::boereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
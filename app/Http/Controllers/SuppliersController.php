<?php

namespace App\Http\Controllers;

use App\Models\SuppliersModel;
use Illuminate\Http\Request;

class SuppliersController extends Controller {

    public function index() {
        $supp = SuppliersModel::get();
        return $supp;
    }

    public function getId($id) {
        $supp = SuppliersModel::find($id);
        if (!$supp) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$supp];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $supp = new SuppliersModel;
        $supp->id = $data['id'];
        $supp->DocumentType = $data['DocumentType'];
        $supp->suppDocument = $data['suppDocument'];
        $supp->suppCompanyName = $data['suppCompanyName'];
        $supp->suppEmail = $data['suppEmail'];
        $supp->suppPhone = $data['suppPhone'];
        $supp->suppState = $data['suppState'];
        $supp->suppCreatedAt = $data['suppCreatedAt'];
        $supp->suppUpdatedAt = $data['suppUpdatedAt'];
        $supp->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(SuppliersModel $supp) {
        return $supp;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $supp = SuppliersModel::find($id);
        $supp->DocumentType = $data['DocumentType'];
        $supp->suppDocument = $data['suppDocument'];
        $supp->suppCompanyName = $data['suppCompanyName'];
        $supp->suppEmail = $data['suppEmail'];
        $supp->suppPhone = $data['suppPhone'];
        $supp->suppState = $data['suppState'];
        $supp->suppCreatedAt = $data['suppCreatedAt'];
        $supp->suppUpdatedAt = $data['suppUpdatedAt'];
        $supp->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $supp = SuppliersModel::find($id);
        if (!$supp) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $supp->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = SuppliersModel::max('id');
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
        SuppliersModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
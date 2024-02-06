<?php

namespace App\Http\Controllers;

use App\Models\DocumentTypesModel;
use Illuminate\Http\Request;

class DocumentTypesController extends Controller {

    public function index() {
        $doct = DocumentTypesModel::get();
        return $doct;
    }

    public function getId($id) {
        $doct = DocumentTypesModel::find($id);
        if (!$doct) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$doct];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $doct = new DocumentTypesModel;
        $doct->id = $data['id'];
        $doct->doctAbbreviation = $data['doctAbbreviation'];
        $doct->doctName = $data['doctName'];
        $doct->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(DocumentTypesModel $doct) {
        return $doct;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $doct = DocumentTypesModel::find($id);
        $doct->doctAbbreviation = $data['doctAbbreviation'];
        $doct->doctName = $data['doctName'];
        $doct->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $doct = DocumentTypesModel::find($id);
        if (!$doct) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $doct->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = DocumentTypesModel::max('id');
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
        DocumentTypesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
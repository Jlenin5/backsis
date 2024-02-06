<?php

namespace App\Http\Controllers;

use App\Models\DocumentStatusModel;
use Illuminate\Http\Request;

class DocumentStatusController extends Controller {
    public function index() {
        $dost = DocumentStatusModel::get();
        return $dost;
    }

    public function getId($id) {
        $dost = DocumentStatusModel::find($id);
        if (!$dost) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$dost];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $dost = new DocumentStatusModel;
        $dost->id = $data['id'];
        $dost->dostName = $data['dostName'];
        $dost->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(DocumentStatusModel $dost) {
        return $dost;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $dost = DocumentStatusModel::find($id);
        $dost->dostName = $data['dostName'];
        $dost->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $dost = DocumentStatusModel::find($id);
        if (!$dost) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $dost->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = DocumentStatusModel::max('id');
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
        DocumentStatusModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
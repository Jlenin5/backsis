<?php

namespace App\Http\Controllers;

use App\Models\DocumentContentModel;
use Illuminate\Http\Request;

class DocumentContentController extends Controller {
    public function index() {
        $doco = DocumentContentModel::get();
        return $doco;
    }

    public function getId($id) {
        $doco = DocumentContentModel::find($id);
        if (!$doco) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$doco];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $doco = new DocumentContentModel;
        $doco->id = $data['id'];
        $doco->docoHeader = $data['docoHeader'];
        $doco->docoHeaderMarked = $data['docoHeaderMarked'];
        $doco->docoFooter = $data['docoFooter'];
        $doco->docoFooterMarked = $data['docoFooterMarked'];
        $doco->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(DocumentContentModel $doco) {
        return $doco;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $doco = DocumentContentModel::find($id);
        $doco->docoHeader = $data['docoHeader'];
        $doco->docoHeaderMarked = $data['docoHeaderMarked'];
        $doco->docoFooter = $data['docoFooter'];
        $doco->docoFooterMarked = $data['docoFooterMarked'];
        $doco->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $doco = DocumentContentModel::find($id);
        if (!$doco) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $doco->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = DocumentContentModel::max('id');
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
        DocumentContentModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
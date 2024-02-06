<?php

namespace App\Http\Controllers;

use App\Models\QuoteDetailsModel;
use Illuminate\Http\Request;

class QuoteDetailsController extends Controller {
    public function index() {
        $qtd = QuoteDetailsModel::get();
        return $qtd;
    }

    public function getId($id) {
        $qtd = QuoteDetailsModel::find($id);
        if (!$qtd) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$qtd];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $qtd = new QuoteDetailsModel;
        $qtd->id = $data['id'];
        $qtd->Product = $data['Product'];
        $qtd->Quotation = $data['Quotation'];
        $qtd->qtdQuantity = $data['qtdQuantity'];
        $qtd->qtdSubtotal = $data['qtdSubtotal'];
        $qtd->qtdTotal = $data['qtdTotal'];
        $qtd->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(QuoteDetailsModel $qtd) {
        return $qtd;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $qtd = QuoteDetailsModel::find($id);
        $qtd->Product = $data['Product'];
        $qtd->Quotation = $data['Quotation'];
        $qtd->qtdQuantity = $data['qtdQuantity'];
        $qtd->qtdSubtotal = $data['qtdSubtotal'];
        $qtd->qtdTotal = $data['qtdTotal'];
        $qtd->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $qtd = QuoteDetailsModel::find($id);
        if (!$qtd) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $qtd->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = QuoteDetailsModel::max('id');
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
        QuoteDetailsModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
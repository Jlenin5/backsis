<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetailsModel;
use Illuminate\Http\Request;

class InvoiceDetailsController extends Controller {
    public function index() {
        $invd = InvoiceDetailsModel::get();
        return $invd;
    }

    public function getId($id) {
        $invd = InvoiceDetailsModel::find($id);
        if (!$invd) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$invd];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $invd = new InvoiceDetailsModel;
        $invd->id = $data['id'];
        $invd->Product = $data['Product'];
        $invd->Invoice = $data['Invoice'];
        $invd->invdQuantity = $data['invdQuantity'];
        $invd->invdSubtotal = $data['invdSubtotal'];
        $invd->invdTotal = $data['invdTotal'];
        $invd->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(InvoiceDetailsModel $invd) {
        return $invd;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $invd = InvoiceDetailsModel::find($id);
        $invd->Product = $data['Product'];
        $invd->Invoice = $data['Invoice'];
        $invd->invdQuantity = $data['invdQuantity'];
        $invd->invdSubtotal = $data['invdSubtotal'];
        $invd->invdTotal = $data['invdTotal'];
        $invd->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $invd = InvoiceDetailsModel::find($id);
        if (!$invd) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $invd->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = InvoiceDetailsModel::max('id');
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
        InvoiceDetailsModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
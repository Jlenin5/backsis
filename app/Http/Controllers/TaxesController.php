<?php

namespace App\Http\Controllers;

use App\Models\TaxesModels;
use Illuminate\Http\Request;

class TaxesController extends Controller {
    public function index() {
        $tax = TaxesModels::get();
        return $tax;
    }

    public function getId($id) {
        $tax = TaxesModels::find($id);
        if (!$tax) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$tax];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $tax = new TaxesModels;
        $tax->id = $data['id'];
        $tax->taxName = $data['taxName'];
        $tax->taxAbbreviation = $data['taxAbbreviation'];
        $tax->taxValue = $data['taxValue'];
        $tax->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(TaxesModels $tax) {
        return $tax;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $tax = TaxesModels::find($id);
        $tax->taxName = $data['taxName'];
        $tax->taxAbbreviation = $data['taxAbbreviation'];
        $tax->taxValue = $data['taxValue'];
        $tax->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $tax = TaxesModels::find($id);
        if (!$tax) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $tax->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = TaxesModels::max('id');
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
        TaxesModels::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ProductUnitModel;
use Illuminate\Http\Request;

class ProductUnitController extends Controller {
    public function index() {
        $prun = ProductUnitModel::get();
        return $prun;
    }

    public function getId($id) {
        $prun = ProductUnitModel::find($id);
        if (!$prun) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$prun];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $prun = new ProductUnitModel;
        $prun->id = $data['id'];
        $prun->prunUnit = $data['prunUnit'];
        $prun->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProductUnitModel $prun) {
        return $prun;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $prun = ProductUnitModel::find($id);
        $prun->prunUnit = $data['prunUnit'];
        $prun->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $prun = ProductUnitModel::find($id);
        if (!$prun) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $prun->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ProductUnitModel::max('id');
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
        ProductUnitModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
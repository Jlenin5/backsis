<?php

namespace App\Http\Controllers;

use App\Models\ProductWarehouseModel;
use Illuminate\Http\Request;

class ProductBranchOfficeController extends Controller {
    public function index() {
        $prbo = ProductWarehouseModel::get();
        return $prbo;
    }

    public function getId($id) {
        $prbo = ProductWarehouseModel::find($id);
        if (!$prbo) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$prbo];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $prbo = new ProductWarehouseModel;
        $prbo->id = $data['id'];
        $prbo->Product = $data['Product'];
        $prbo->BranchOffice = $data['BranchOffice'];
        $prbo->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProductWarehouseModel $prbo) {
        return $prbo;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $prbo = ProductWarehouseModel::find($id);
        $prbo->Product = $data['Product'];
        $prbo->BranchOffice = $data['BranchOffice'];
        $prbo->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $prbo = ProductWarehouseModel::find($id);
        if (!$prbo) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $prbo->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ProductWarehouseModel::max('id');
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
        ProductWarehouseModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
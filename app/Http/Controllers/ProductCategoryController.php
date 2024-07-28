<?php

namespace App\Http\Controllers;

use App\Models\ProductCategoriesModel;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller {
    public function index() {
        $prca = ProductCategoriesModel::get();
        return $prca;
    }

    public function getId($id) {
        $prca = ProductCategoriesModel::find($id);
        if (!$prca) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$prca];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $prca = new ProductCategoriesModel;
        $prca->id = $data['id'];
        $prca->Product = $data['Product'];
        $prca->Category = $data['Category'];
        $prca->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProductCategoriesModel $prca) {
        return $prca;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $prca = ProductCategoriesModel::find($id);
        $prca->Product = $data['Product'];
        $prca->Category = $data['Category'];
        $prca->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $prca = ProductCategoriesModel::find($id);
        if (!$prca) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $prca->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ProductCategoriesModel::max('id');
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
        ProductCategoriesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
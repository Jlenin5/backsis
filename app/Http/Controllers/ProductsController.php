<?php

namespace App\Http\Controllers;

use App\Models\ProductBranchOfficeModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductImagesModel;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ProductsController extends Controller {

    public function index() {
        $prod = ProductsModel::with('categories', 'productImages', 'serialNumber', 'unit', 'branchOffices')->get();
        $prod->each(function ($product) {
            $product->categories->each(function ($category) {
                unset($category->pivot);
            });
            $product->branchOffices->each(function ($branchOffice) {
                unset($branchOffice->pivot);
            });
        });
        return $prod;
    }

    public function getId($id) {
        $prod = ProductsModel::with('categories', 'productImages', 'serialNumber', 'unit', 'branchOffices')->findOrFail($id);
        $prod->categories->each(function ($category) {
            unset($category->pivot);
        });
        $prod->branchOffices->each(function ($branchOffice) {
            unset($branchOffice->pivot);
        });
        if (!$prod) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $prod;
    }

    public function store(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'SerialNumber' => 'required',
            'prodName' => 'required',
            'prodStock' => 'required|numeric',
            'prodPurchasePrice' => 'required|numeric',
            'prodSalePrice' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Datos incompletos o no válidos', 'errors' => $validator->errors()]);
        }
        $prod = new ProductsModel;
        $prod->id = (int)$data['id'];
        $prod->SerialNumber = $data['SerialNumber'];
        $prod->prodNumber = $data['prodNumber'];
        $prod->featuredImageId = $data['featuredImageId'];
        $prod->prodName = $data['prodName'];
        $prod->prodDescription = $data['prodDescription'] === null ? '' : $data['prodDescription'];
        $prod->Unit = $data['Unit'];
        $prod->prodStock = $data['prodStock'];
        $prod->prodPurchasePrice = $data['prodPurchasePrice'];
        $prod->prodSalePrice = $data['prodSalePrice'];
        $prod->prodWidth = $data['prodWidth'];
        $prod->prodHeight = $data['prodHeight'];
        $prod->prodDepth = $data['prodDepth'];
        $prod->prodWeight = $data['prodWeight'];
        $prod->prodState = (bool)$data['prodState'];
        $prod->prodWebHome = (bool)$data['prodWebHome'];
        $prod->prodCreatedAt = now();
        $prod->prodUpdatedAt = now();
        $prod->save();

        if (isset($request->categories) && is_array($request->categories)) {
            foreach ($data['categories'] as $categoryId) {
                $prodCategory = new ProductCategoryModel([
                    'Product' => $prod->id,
                    'Category' => $categoryId['id'],
                ]);
                $prodCategory->save();
            }
        }

        if (isset($request->branch_offices) && is_array($request->branch_offices)) {
            foreach ($data['branch_offices'] as $branchOfficeId) {
                $prodBranchOffice = new ProductBranchOfficeModel([
                    'Product' => $prod->id,
                    'BranchOffice' => $branchOfficeId['id'],
                ]);
                $prodBranchOffice->save();
            }
        }

        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProductsModel $prod) {
        return $prod;
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'SerialNumber' => 'required',
            'prodName' => 'required',
            'prodStock' => 'required|numeric',
            'prodPurchasePrice' => 'required|numeric',
            'prodSalePrice' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Datos incompletos o no válidos', 'errors' => $validator->errors()]);
        }
        $prod = ProductsModel::find($id);
        if (!$prod) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Producto no encontrado']);
        }
        $prod->SerialNumber = $data['SerialNumber'];
        $prod->prodNumber = $data['prodNumber'];
        $prod->featuredImageId = $data['featuredImageId'];
        $prod->prodName = $data['prodName'];
        $prod->prodDescription = $data['prodDescription'] === null ? '' : $data['prodDescription'];
        $prod->Unit = $data['Unit'];
        $prod->prodStock = $data['prodStock'];
        $prod->prodPurchasePrice = $data['prodPurchasePrice'];
        $prod->prodSalePrice = $data['prodSalePrice'];
        $prod->prodWidth = $data['prodWidth'];
        $prod->prodHeight = $data['prodHeight'];
        $prod->prodDepth = $data['prodDepth'];
        $prod->prodWeight = $data['prodWeight'];
        $prod->prodState = (bool)$data['prodState'];
        $prod->prodWebHome = (bool)$data['prodWebHome'];
        $prod->prodCreatedAt = now();
        $prod->prodUpdatedAt = now();
        $prod->update();
        
        $prod->categories()->detach();
        if (isset($data['categories']) && is_array($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                $prod->categories()->attach($categoryId['id']);
            }
        }

        $prod->branchOffices()->detach();
        if (isset($data['branch_offices']) && is_array($data['branch_offices'])) {
            foreach ($data['branch_offices'] as $branchOfficeId) {
                $prod->branchOffices()->attach($branchOfficeId['id']);
            }
        }

        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado correctamente']);
    }

    public function destroy($id) {
        $prod = ProductsModel::find($id);
        $prod->categories()->detach();
        $prod->branchOffices()->detach();
        if (!$prod) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $prod->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ProductsModel::max('id');
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

        DB::transaction(function () use ($ids) {
            foreach ($ids as $productId) {
                $product = ProductsModel::find($productId);
                if ($product) {
                    $product->categories()->detach();
                    $product->branchOffices()->detach();
                }
            }
            ProductsModel::whereIn('id', $ids)->delete();
        });

        ProductsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }


    public function featuredId($featured) {
        $prod = ProductsModel::with('categories', 'productImages', 'serialNumber', 'unit', 'branchOffices')->where('prodNumber',$featured)->first();
        $prod->categories->each(function ($category) {
            unset($category->pivot);
        });
        $prod->branchOffices->each(function ($branchOffice) {
            unset($branchOffice->pivot);
        });
        if (!$prod) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $prod;
    }

}
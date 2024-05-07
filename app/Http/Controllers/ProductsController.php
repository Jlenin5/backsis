<?php

namespace App\Http\Controllers;

use App\Models\ProductWarehouseModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductImagesModel;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ProductsController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        $prod = ProductsModel::with([
                'categories' => function($query) {
                    $query->whereNull('product_category.deleted_at');
                },
                'productImages', 'unit', 'warehouses'
            ])
            ->whereNull('deleted_at')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();

        $prod->each(function ($product) {
        $product->categories->each(function ($category) {
            unset($category->pivot);
        });
            // $product->warehouses->each(function ($branchOffice) {
            //     unset($branchOffice->pivot);
            // });
        });

        $totalRows = ProductsModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $prod,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $prod = ProductsModel::with([
            'categories' => function($query) {
                $query->whereNull('product_category.deleted_at');
            },
            'productImages', 'unit', 'warehouses'
        ])->findOrFail($id);
        $prod->categories->each(function ($category) {
            unset($category->pivot);
        });
        $prod->warehouses->each(function ($branchOffice) {
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
            'code' => 'required',
            'name' => 'required',
            'stock_alert' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Datos incompletos o no válidos', 'errors' => $validator->errors()]);
        }
        $prod = new ProductsModel;
        $prod->code = $data['code'];
        $prod->featured = $data['featured'];
        $prod->name = $data['name'];
        $prod->description = $data['description'] ?? '';
        $prod->unit_id = $data['unit_id'];
        $prod->stock_alert = $data['stock_alert'];
        $prod->purchase_price = $data['purchase_price'];
        $prod->sale_price = $data['sale_price'];
        $prod->width = $data['width'];
        $prod->height = $data['height'];
        $prod->depth = $data['depth'];
        $prod->weight = $data['weight'];
        $prod->web_site = (bool)$data['web_site'];
        $prod->status = (bool)$data['status'];
        $prod->save();

        if (isset($request->categories) && is_array($request->categories)) {
            foreach ($data['categories'] as $categoryId) {
                $prodCategory = new ProductCategoryModel([
                    'product_id' => $prod->id,
                    'category_id' => $categoryId['id'],
                ]);
                $prodCategory->save();
            }
        }

        // if (isset($request->branch_offices) && is_array($request->branch_offices)) {
        //     foreach ($data['branch_offices'] as $branchOfficeId) {
        //         $prodBranchOffice = new ProductWarehouseModel([
        //             'product_id' => $prod->id,
        //             'branch_office_id' => $branchOfficeId['id'],
        //         ]);
        //         $prodBranchOffice->save();
        //     }
        // }

        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProductsModel $prod) {
        return $prod;
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'code' => 'required',
            'name' => 'required',
            'stock_alert' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Datos incompletos o no válidos', 'errors' => $validator->errors()]);
        }
        $prod = ProductsModel::find($id);
        if (!$prod) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Producto no encontrado']);
        }
        $prod->code = $data['code'];
        $prod->featured = $data['featured'];
        $prod->name = $data['name'];
        $prod->description = $data['description'] ?? '';
        $prod->unit_id = $data['unit_id'];
        $prod->stock_alert = $data['stock_alert'];
        $prod->purchase_price = $data['purchase_price'];
        $prod->sale_price = $data['sale_price'];
        $prod->width = $data['width'];
        $prod->height = $data['height'];
        $prod->depth = $data['depth'];
        $prod->weight = $data['weight'];
        $prod->web_site = (bool)$data['web_site'];
        $prod->status = (bool)$data['status'];
        $prod->update();
        
        // Obtener las categorías enviadas desde el front
        $categories = $request->input('categories', []);
        // Obtener las categorías existentes del producto
        $existingCategories = $prod->categories->pluck('id')->toArray();
        // Recorrer las categorías enviadas desde el front
        foreach ($categories as $category) {
            // Verificar si la categoría ya existe en el producto
            if (!in_array($category['id'], $existingCategories)) {
                // Si no existe, crear una nueva entrada en product_category
                ProductCategoryModel::create([
                    'product_id' => $prod->id,
                    'category_id' => $category['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                // Si existe y está marcada como eliminada, actualizar deleted_at a null
                $existingCategory = ProductCategoryModel::where('product_id', $prod->id)
                    ->where('category_id', $category['id'])
                    ->whereNotNull('deleted_at')
                    ->first();
                if ($existingCategory) {
                    $existingCategory->deleted_at = null;
                    $existingCategory->update();
                }
            }
        }
        // Marcar las categorías que no fueron enviadas desde el front como eliminadas
        $deletedCategories = array_diff($existingCategories, array_column($categories, 'id'));
        ProductCategoryModel::whereIn('category_id', $deletedCategories)->update(['deleted_at' => Carbon::now()]);

        // $prod->warehouses()->detach();
        // if (isset($data['branch_offices']) && is_array($data['branch_offices'])) {
        //     foreach ($data['branch_offices'] as $branchOfficeId) {
        //         $prod->warehouses()->attach($branchOfficeId['id']);
        //     }
        // }

        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado correctamente']);
    }

    public function destroy($id) {
        $prod = ProductsModel::find($id);
        $prod->categories()->detach();
        $prod->warehouses()->detach();
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
                    $product->warehouses()->detach();
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
        $prod = ProductsModel::with('categories', 'productImages', 'serialNumber', 'unit', 'warehouses')->where('prodNumber',$featured)->first();
        $prod->categories->each(function ($category) {
            unset($category->pivot);
        });
        $prod->warehouses->each(function ($branchOffice) {
            unset($branchOffice->pivot);
        });
        if (!$prod) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $prod;
    }

}
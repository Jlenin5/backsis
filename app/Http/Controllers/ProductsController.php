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
        $product_data = json_decode($data['productData'], true);
        $prod = new ProductsModel;
        $prod->code = $product_data['code'];
        $prod->featured = $product_data['featured'];
        $prod->name = $product_data['name'];
        $prod->description = $product_data['description'] ?? '';
        $prod->unit_id = $product_data['unit_id'];
        $prod->stock_alert = $product_data['stock_alert'];
        $prod->purchase_price = $product_data['purchase_price'];
        $prod->sale_price = $product_data['sale_price'];
        $prod->width = $product_data['width'];
        $prod->height = $product_data['height'];
        $prod->depth = $product_data['depth'];
        $prod->weight = $product_data['weight'];
        $prod->web_site = (bool)$product_data['web_site'];
        $prod->status = (bool)$product_data['status'];
        $prod->save();

        if (isset($request->product_images) && is_array($request->product_images)) {
            foreach ($request->product_images as $index => $imageData) {
                if (isset($imageData['path'])) {
                    $file = $imageData['path'];
                    $fileName = uniqid($imageData['featured']) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("images/products/"), $fileName);

                    $productImage = new ProductImagesModel([
                        'path' => $fileName,
                        'product_id' => $prod->id,
                        'featured' => $imageData['featured']
                    ]);
                    $productImage->save();
                }
            }
        }

        if (isset($product_data['categories']) && is_array($product_data['categories'])) {
            foreach ($product_data['categories'] as $categoryId) {
                $prodCategory = new ProductCategoryModel([
                    'product_id' => $prod->id,
                    'category_id' => $categoryId['id'],
                ]);
                $prodCategory->save();
            }
        }

        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ProductsModel $prod) {
        return $prod;
    }

    public function update(Request $request) {
        $data = $request->all();
        $product_data = json_decode($data['productData'], true);
        $prod = ProductsModel::find($product_data['id']);
        if (!$prod) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Producto no encontrado']);
        }
        $prod->code = $product_data['code'];
        $prod->featured = $product_data['featured'];
        $prod->name = $product_data['name'];
        $prod->description = $product_data['description'] ?? '';
        $prod->unit_id = $product_data['unit_id'];
        $prod->stock_alert = $product_data['stock_alert'];
        $prod->purchase_price = $product_data['purchase_price'];
        $prod->sale_price = $product_data['sale_price'];
        $prod->width = $product_data['width'];
        $prod->height = $product_data['height'];
        $prod->depth = $product_data['depth'];
        $prod->weight = $product_data['weight'];
        $prod->web_site = (bool)$product_data['web_site'];
        $prod->status = (bool)$product_data['status'];
        $prod->update();

        if (isset($request->product_images) && is_array($request->product_images)) {
            foreach ($request->product_images as $index => $imageData) {
                if (isset($imageData['path'])) {
                    // Verificar si ya existe una imagen con el mismo 'featured'
                    $existingImage = ProductImagesModel::where('featured', $imageData['featured'])->first();
                    if (!$existingImage) {
                        $file = $imageData['path'];
                        $fileName = uniqid($imageData['featured']) . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path("images/products/"), $fileName);
    
                        $productImage = new ProductImagesModel([
                            'path' => $fileName,
                            'product_id' => $prod->id,
                            'featured' => $imageData['featured']
                        ]);
                        $productImage->save();
                    }
                }
                $existingImages = ProductImagesModel::where('product_id', $prod->id)->get()->pluck('featured')->toArray();
                // Eliminar imágenes existentes que no están presentes en las nuevas imágenes
                $imagesToDelete = array_diff($existingImages, array_column($request->product_images, 'featured'));
                ProductImagesModel::whereIn('featured', $imagesToDelete)->update(['deleted_at' => now()]);
            }
        }
        
        // Obtener las categorías enviadas desde el front
        $categories = $product_data['categories'] ?? [];
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
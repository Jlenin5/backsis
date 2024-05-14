<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\ProductImagesModel;
use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends Controller {

    public function index() {
        $prod = ProductImagesModel::get();
        return $prod;
    }

    public function store(Request $request) {
        sleep(1.5);
    
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
                            'product_id' => $imageData['product_id'],
                            'featured' => $imageData['featured']
                        ]);
                        $productImage->save();
                    }
                }
                $existingImages = ProductImagesModel::where('product_id', $imageData['product_id'])->get()->pluck('featured')->toArray();
                Log::info($existingImages);
                // Eliminar imágenes existentes que no están presentes en las nuevas imágenes
                $imagesToDelete = array_diff($existingImages, array_column($request->product_images, 'featured'));
                Log::info($imagesToDelete);
                $eliminado = ProductImagesModel::whereIn('featured', $imagesToDelete)->delete();
                Log::info($eliminado);
            }
        }
    
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Agregado correctamente']);
    }

    public function update(Request $request) {
        if (isset($request->product_images) && is_array($request->product_images)) {
            foreach ($request->product_images as $index => $imageData) {
                if (isset($imageData['path'])) {
                    $file = $imageData['path'];
                    $fileName = uniqid($imageData['featured']) . '.' . $file->getClientOriginalExtension();
                    $file->move(\public_path("images/products/"), $fileName);

                    $productImage = new ProductImagesModel([
                        'path' => $fileName,
                        'product_id' => $imageData['product_id'],
                        'featured' => $imageData['featured']
                    ]);
                    $productImage->update();
                }
            }
        }
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ProductImagesModel::max('id');
        return response()->json(['ultimo_id' => $ultimoId]);
    }

    public function destroyMultiple(Request $request) {
        $ids = $request->input('dataId', []);
        // Obtener las imágenes asociadas a los productos que se están eliminando
        $imagesToDelete = ProductImagesModel::whereIn('id', $ids)->pluck('path');
        // Eliminar las imágenes correspondientes
        foreach ($imagesToDelete as $image) {
            if (!empty($image)) {
                $imagePath = public_path('images/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        if (empty($ids)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se proporcionaron datos para eliminar.'
            ], 400);
        }
        ProductImagesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }

}
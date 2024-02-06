<?php

namespace App\Http\Controllers;

use App\Models\CompanyModel;
use Illuminate\Http\Request;

class CompanyController extends Controller {
    
    public function index() {
        $com = CompanyModel::get();
        return $com;
    }

    public function getId($id) {
        $com = CompanyModel::find($id);
        if (!$com) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$com];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $com = new CompanyModel;
        $com->id = $data['id'];
        $com->comCode = $data['comCode'];
        if ($request->hasFile('comImage')) {
            // Genera un nombre de archivo único y guarda la imagen en la carpeta 'images/company'
            $currentDate = now()->format('Y-m-d');
            $companyCode = $data['comCode'];
            $uniqueFilename = md5($currentDate . $companyCode) . "." . $request->file('comImage')->getClientOriginalExtension();
            $request->file('comImage')->move('images/company', $uniqueFilename);
            $com->comImage = $uniqueFilename;
        } else {
            $com->comImage = '';
        }
        $com->comName = $data['comName'];
        $com->comRUC = $data['comRUC'];
        $com->comEmail = $data['comEmail'];
        $com->comAddress = $data['comAddress'];
        $com->comWebSite = $data['comWebSite'];
        $com->comPhone = $data['comPhone'];
        $com->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(CompanyModel $com) {
        return $com;
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $com = CompanyModel::find($id);
        $com->comCode = $data['comCode'];
        if ($request->hasFile('comImage')) {
            // Elimina la imagen anterior si existe
            if (!empty($com->comImage)) {
                $imagePath = public_path('images/company/' . $com->comImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            // Almacena la nueva imagen
            $currentDate = now()->format('Y-m-d');
            $companyCode = $data['comCode'];
            $uniqueFilename = md5($currentDate . $companyCode) . "." . $request->file('comImage')->getClientOriginalExtension();
            $request->file('comImage')->move('images/company', $uniqueFilename);
            $com->comImage = $uniqueFilename;
        } else {
            $com->comImage = $request->comImage;
        }
        $com->comName = $data['comName'];
        $com->comRUC = $data['comRUC'];
        $com->comEmail = $data['comEmail'];
        $com->comAddress = $data['comAddress'];
        $com->comWebSite = $data['comWebSite'];
        $com->comPhone = $data['comPhone'];
        $com->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $com = CompanyModel::find($id);
        if (!$com) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $com->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = CompanyModel::max('id');
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
        CompanyModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
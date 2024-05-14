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
        $com->code = $data['code'];
        if ($request->hasFile('image')) {
            // Genera un nombre de archivo único y guarda la imagen en la carpeta 'images/company'
            $currentDate = now()->format('Y-m-d');
            $companyCode = $data['code'];
            $uniqueFilename = md5($currentDate . $companyCode) . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/company', $uniqueFilename);
            $com->image = $uniqueFilename;
        } else {
            $com->image = '';
        }
        $com->name = $data['name'];
        $com->document_number = $data['document_number'];
        $com->email = $data['email'];
        $com->address = $data['address'];
        $com->web_site = $data['web_site'];
        $com->phone = $data['phone'];
        $com->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(CompanyModel $com) {
        return $com;
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $com = CompanyModel::find($id);
        $com->code = $data['code'];
        if ($request->hasFile('image')) {
            // Elimina la imagen anterior si existe
            if (!empty($com->image)) {
                $imagePath = public_path('images/company/' . $com->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            // Almacena la nueva imagen
            $currentDate = now()->format('Y-m-d');
            $companyCode = $data['code'];
            $uniqueFilename = md5($currentDate . $companyCode) . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/company', $uniqueFilename);
            $com->image = $uniqueFilename;
        } else {
            $com->image = $request->image;
        }
        $com->name = $data['name'];
        $com->document_number = $data['document_number'];
        $com->email = $data['email'];
        $com->address = $data['address'];
        $com->web_site = $data['web_site'];
        $com->phone = $data['phone'];
        $com->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $com = CompanyModel::find($id);
        if (!$com) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $com->deleted_at = now();
        $com->update();
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

        foreach ($ids as $id) {
            CompanyModel::whereId($id)->update([
                'deleted_at' => now(),
            ]);
        }
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
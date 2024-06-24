<?php

namespace App\Http\Controllers;

use App\Models\CompaniesModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CompaniesController extends Controller {

    use ApiResponser;
    
    public function index(Request $request) {

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            CompaniesModel::where('deleted_at',null)
                ->whereNull('deleted_at')
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function getId($id) {
        $com = CompaniesModel::find($id);
        if (!$com) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$com];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $com = new CompaniesModel;
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

    public function show(CompaniesModel $com) {
        return $com;
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $com = CompaniesModel::find($id);
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
        $com = CompaniesModel::find($id);
        if (!$com) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $com->deleted_at = now();
        $com->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = CompaniesModel::max('id');
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
            CompaniesModel::whereId($id)->update([
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
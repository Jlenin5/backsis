<?php

namespace App\Http\Controllers;

use App\Models\SuppliersModel;
use Illuminate\Http\Request;

class SuppliersController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $supp = SuppliersModel::whereNull('deleted_at')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRows = SuppliersModel::whereNull('deleted_at')->count();
        
        return response()->json([
            'data' => $supp,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $supp = SuppliersModel::find($id);
        if (!$supp) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$supp];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $supp = new SuppliersModel;
        $supp->document_type = $data['document_type'];
        $supp->document_number = $data['document_number'];
        $supp->name = $data['name'];
        $supp->email = $data['email'];
        $supp->address = $data['address'];
        $supp->phone = $data['phone'];
        $supp->status = $data['status'];
        $supp->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(SuppliersModel $supp) {
        return $supp;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $supp = SuppliersModel::find($id);
        $supp->document_type = $data['document_type'];
        $supp->document_number = $data['document_number'];
        $supp->name = $data['name'];
        $supp->email = $data['email'];
        $supp->address = $data['address'];
        $supp->phone = $data['phone'];
        $supp->status = $data['status'];
        $supp->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $supp = SuppliersModel::find($id);
        if (!$supp) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $supp->deleted_at = now();
        $supp->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = SuppliersModel::max('id');
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
            SuppliersModel::whereId($id)->update([
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
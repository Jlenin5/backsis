<?php

namespace App\Http\Controllers;

use App\Models\WorkAreaModel;
use Illuminate\Http\Request;

class WorkAreaController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $wa = WorkAreaModel::whereNull('deleted_at')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRows = WorkAreaModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $wa,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $wa = WorkAreaModel::find($id);
        if (!$wa) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$wa];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $wa = new WorkAreaModel;
        $wa->name = $data['name'];
        $wa->status = $data['status'];
        $wa->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(WorkAreaModel $wa) {
        return $wa;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $wa = WorkAreaModel::find($id);
        $wa->name = $data['name'];
        $wa->status = $data['status'];
        $wa->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $wa = WorkAreaModel::find($id);
        if (!$wa) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $wa->deleted_at = now();
        $wa->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = WorkAreaModel::max('id');
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
            WorkAreaModel::whereId($id)->update([
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
<?php

namespace App\Http\Controllers;

use App\Models\JobPositionModel;
use Illuminate\Http\Request;

class JobPositionController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $jp = JobPositionModel::whereNull('deleted_at')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRows = JobPositionModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $jp,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $jp = JobPositionModel::find($id);
        if (!$jp) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $jp;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $jp = new JobPositionModel;
        $jp->name = $data['name'];
        $jp->status = $data['status'];
        $jp->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(JobPositionModel $jp) {
        return $jp;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $jp = JobPositionModel::find($id);
        $jp->name = $data['name'];
        $jp->status = $data['status'];
        $jp->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $jp = JobPositionModel::find($id);
        if (!$jp) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $jp->deleted_at = now();
        $jp->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = JobPositionModel::max('id');
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
            JobPositionModel::whereId($id)->update([
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
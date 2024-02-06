<?php

namespace App\Http\Controllers;

use App\Models\JobPositionModel;
use Illuminate\Http\Request;

class JobPositionController extends Controller {
    public function index() {
        $jp = JobPositionModel::get();
        return $jp;
    }

    public function getId($id) {
        $jp = JobPositionModel::find($id);
        if (!$jp) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$jp];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $jp = new JobPositionModel;
        $jp->id = $data['id'];
        $jp->jpName = $data['jpName'];
        $jp->jpState = $data['jpState'];
        $jp->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(JobPositionModel $jp) {
        return $jp;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $jp = JobPositionModel::find($id);
        $jp->jpName = $data['jpName'];
        $jp->jpState = $data['jpState'];
        $jp->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $jp = JobPositionModel::find($id);
        if (!$jp) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $jp->delete();
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
        JobPositionModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
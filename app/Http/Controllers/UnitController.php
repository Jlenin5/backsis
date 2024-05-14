<?php

namespace App\Http\Controllers;

use App\Models\UnitModel;
use Illuminate\Http\Request;

class UnitController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $prun = UnitModel::whereNull('deleted_at')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRows = UnitModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $prun,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $prun = UnitModel::where('deleted_at',null)->find($id);
        if (!$prun) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$prun];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $prun = new UnitModel;
        $prun->name = $data['name'];
        $prun->short_name = $data['short_name'];
        $prun->base_unit = $data['base_unit'];
        $prun->operator = $data['operator'];
        $prun->operator_value = $data['operator_value'];
        $prun->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(UnitModel $prun) {
        return $prun;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $prun = UnitModel::find($id);
        $prun->name = $data['name'];
        $prun->short_name = $data['short_name'];
        $prun->base_unit = $data['base_unit'];
        $prun->operator = $data['operator'];
        $prun->operator_value = $data['operator_value'];
        $prun->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $prun = UnitModel::find($id);
        if (!$prun) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $prun->deleted_at = now();
        $prun->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = UnitModel::max('id');
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
            UnitModel::whereId($id)->update([
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
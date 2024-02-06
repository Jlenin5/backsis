<?php

namespace App\Http\Controllers;

use App\Models\MobilitiesModel;
use Illuminate\Http\Request;

class MobilitiesController extends Controller {
    public function index() {
        $mob = MobilitiesModel::get();
        return $mob;
    }

    public function getId($id) {
        $mob = MobilitiesModel::find($id);
        if (!$mob) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$mob];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $mob = new MobilitiesModel;
        $mob->id = $data['id'];
        $mob->mobBrand = $data['mobBrand'];
        $mob->mobPlate = $data['mobPlate'];
        $mob->mobColor = $data['mobColor'];
        $mob->mobState = $data['mobState'];
        $mob->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(MobilitiesModel $mob) {
        return $mob;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $mob = MobilitiesModel::find($id);
        $mob->mobBrand = $data['mobBrand'];
        $mob->mobPlate = $data['mobPlate'];
        $mob->mobColor = $data['mobColor'];
        $mob->mobState = $data['mobState'];
        $mob->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $mob = MobilitiesModel::find($id);
        if (!$mob) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $mob->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = MobilitiesModel::max('id');
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
        MobilitiesModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
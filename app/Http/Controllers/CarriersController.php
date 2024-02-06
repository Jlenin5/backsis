<?php

namespace App\Http\Controllers;

use App\Models\CarriersModel;
use Illuminate\Http\Request;

class CarriersController extends Controller {
    public function index() {
        $carr = CarriersModel::get();
        return $carr;
    }

    public function getId($id) {
        $carr = CarriersModel::find($id);
        if (!$carr) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$carr];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $carr = new CarriersModel;
        $carr->id = $data['id'];
        $carr->carrName = $data['carrName'];
        $carr->carrPhone = $data['carrPhone'];
        $carr->carrGender = $data['carrGender'];
        $carr->carrDLicence = $data['carrDLicence'];
        $carr->carrState = $data['carrState'];
        $carr->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(CarriersModel $carr) {
        return $carr;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $carr = CarriersModel::find($id);
        $carr->carrName = $data['carrName'];
        $carr->carrPhone = $data['carrPhone'];
        $carr->carrGender = $data['carrGender'];
        $carr->carrDLicence = $data['carrDLicence'];
        $carr->carrState = $data['carrState'];
        $carr->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $carr = CarriersModel::find($id);
        if (!$carr) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $carr->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = CarriersModel::max('id');
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
        CarriersModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
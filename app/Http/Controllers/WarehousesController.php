<?php

namespace App\Http\Controllers;

use App\Models\WarehousesModel;
use Illuminate\Http\Request;

class WarehousesController extends Controller {
    public function index() {
        $wh = WarehousesModel::with('users','departments','provinces','districts')->where('deleted_at',null)->get();
        return $wh;
    }

    public function getId($id) {
        $wh = WarehousesModel::with('users','departments','provinces','districts')->where('deleted_at',null)->find($id);
        if (!$wh) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $wh;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $wh = new WarehousesModel;
        $wh->id = $data['id'];
        $wh->whName = $data['whName'];
        $wh->whPhone = $data['whPhone'];
        $wh->whEmail = $data['whEmail'];
        $wh->Department = $data['Department'];
        $wh->Province = $data['Province'];
        $wh->District = $data['District'];
        $wh->whAddress = $data['whAddress'];
        $wh->User = $data['User'];
        $wh->whState = $data['whState'];
        $wh->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(WarehousesModel $wh) {
        return $wh;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $wh = WarehousesModel::find($id);
        $wh->whName = $data['whName'];
        $wh->whPhone = $data['whPhone'];
        $wh->whEmail = $data['whEmail'];
        $wh->Department = $data['Department'];
        $wh->Province = $data['Province'];
        $wh->District = $data['District'];
        $wh->whAddress = $data['whAddress'];
        $wh->User = $data['User'];
        $wh->whState = $data['whState'];
        $wh->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $wh = WarehousesModel::find($id);
        if (!$wh) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $wh->deleted_at = now();
        $wh->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = WarehousesModel::max('id');
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

        foreach ($ids as $wh_id) {
            WarehousesModel::whereId($wh_id)->update([
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
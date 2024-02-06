<?php

namespace App\Http\Controllers;

use App\Models\TicketsModel;
use Illuminate\Http\Request;

class TicketsController extends Controller {
    public function index() {
        $tic = TicketsModel::get();
        return $tic;
    }

    public function getId($id) {
        $tic = TicketsModel::find($id);
        if (!$tic) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$tic];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $tic = new TicketsModel;
        $tic->id = $data['id'];
        $tic->SerialNumber = $data['SerialNumber'];
        $tic->ticNumber = $data['ticNumber'];
        $tic->Currency = $data['Currency'];
        $tic->Company = $data['Company'];
        $tic->Client = $data['Client'];
        $tic->Employee = $data['Employee'];
        $tic->ticSubtotal = $data['ticSubtotal'];
        $tic->ticIgv = $data['ticIgv'];
        $tic->ticTotal = $data['ticTotal'];
        $tic->PaymentMethod = $data['PaymentMethod'];
        $tic->DocumentStatus = $data['DocumentStatus'];
        $tic->ticCreatedAt = $data['ticCreatedAt'];
        $tic->ticDeletedAt = $data['ticDeletedAt'];
        $tic->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(TicketsModel $tic) {
        return $tic;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $tic = TicketsModel::find($id);
        $tic->SerialNumber = $data['SerialNumber'];
        $tic->ticNumber = $data['ticNumber'];
        $tic->Currency = $data['Currency'];
        $tic->Company = $data['Company'];
        $tic->Client = $data['Client'];
        $tic->Employee = $data['Employee'];
        $tic->ticSubtotal = $data['ticSubtotal'];
        $tic->ticIgv = $data['ticIgv'];
        $tic->ticTotal = $data['ticTotal'];
        $tic->PaymentMethod = $data['PaymentMethod'];
        $tic->DocumentStatus = $data['DocumentStatus'];
        $tic->ticCreatedAt = $data['ticCreatedAt'];
        $tic->ticDeletedAt = $data['ticDeletedAt'];
        $tic->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $tic = TicketsModel::find($id);
        if (!$tic) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $tic->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = TicketsModel::max('id');
        return response()->json(['ultimo_id' => $ultimoId]);
    }

    public function destroyMultiple(Request $request) {
        $id = $request->input('dataId', []);
        if (empty($id)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se proporcionaron datos para eliminar.'
            ], 400);
        }
        TicketsModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
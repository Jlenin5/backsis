<?php

namespace App\Http\Controllers;

use App\Models\TicketDetailsModel;
use Illuminate\Http\Request;

class TicketDetailsController extends Controller {
    public function index() {
        $ticd = TicketDetailsModel::get();
        return $ticd;
    }

    public function getId($id) {
        $ticd = TicketDetailsModel::find($id);
        if (!$ticd) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$ticd];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $ticd = new TicketDetailsModel;
        $ticd->id = $data['id'];
        $ticd->Product = $data['Product'];
        $ticd->Ticket = $data['Ticket'];
        $ticd->ticdQuantity = $data['ticdQuantity'];
        $ticd->ticdSubtotal = $data['ticdSubtotal'];
        $ticd->ticdTotal = $data['ticdTotal'];
        $ticd->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(TicketDetailsModel $ticd) {
        return $ticd;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $ticd = TicketDetailsModel::find($id);
        $ticd->Product = $data['Product'];
        $ticd->Ticket = $data['Ticket'];
        $ticd->ticdQuantity = $data['ticdQuantity'];
        $ticd->ticdSubtotal = $data['ticdSubtotal'];
        $ticd->ticdTotal = $data['ticdTotal'];
        $ticd->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $ticd = TicketDetailsModel::find($id);
        if (!$ticd) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $ticd->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = TicketDetailsModel::max('id');
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
        TicketDetailsModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
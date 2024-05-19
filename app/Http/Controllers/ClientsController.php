<?php

namespace App\Http\Controllers;

use App\Models\ClientsModel;
use Illuminate\Http\Request;

class ClientsController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $cli = ClientsModel::whereNull('deleted_at')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRows = ClientsModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $cli,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $cli = ClientsModel::whereNull('deleted_at')->find($id);
        if (!$cli) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $cli;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $cli = new ClientsModel;
        $cli->name = $data['name'];
        $cli->document_type = $data['document_type'];
        $cli->document_number = $data['document_number'];
        $cli->email = $data['email'];
        $cli->phone = $data['phone'];
        $cli->gender = $data['gender'];
        $cli->status = $data['status'];
        $cli->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ClientsModel $cli) {
        return $cli;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $cli = ClientsModel::find($id);
        $cli->name = $data['name'];
        $cli->document_type = $data['document_type'];
        $cli->document_number = $data['document_number'];
        $cli->email = $data['email'];
        $cli->phone = $data['phone'];
        $cli->gender = $data['gender'];
        $cli->status = $data['status'];
        $cli->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $cli = ClientsModel::find($id);
        if (!$cli) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $cli->deleted_at = now();
        $cli->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = ClientsModel::max('id');
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
            ClientsModel::whereId($id)->update([
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

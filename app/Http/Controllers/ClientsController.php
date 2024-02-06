<?php

namespace App\Http\Controllers;

use App\Models\ClientsModel;
use Illuminate\Http\Request;

class ClientsController extends Controller {

    public function index() {
        $cli = ClientsModel::get();
        return $cli;
    }

    public function getId($id) {
        $cli = ClientsModel::find($id);
        if (!$cli) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$cli];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $cli = new ClientsModel;
        $cli->id = $data['id'];
        $cli->cliFirstName = $data['cliFirstName'];
        $cli->cliSecondName = $data['cliSecondName'];
        $cli->DocumentType = $data['DocumentType'];
        $cli->cliDocument = $data['cliDocument'];
        $cli->cliEmail = $data['cliEmail'];
        $cli->cliPhone = $data['cliPhone'];
        $cli->cliGender = $data['cliGender'];
        $cli->cliState = $data['cliState'];
        $cli->cliCreatedAt = now();
        $cli->cliUpdatedAt = now();
        $cli->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(ClientsModel $cli) {
        return $cli;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $cli = ClientsModel::find($id);
        $cli->cliFirstName = $data['cliFirstName'];
        $cli->cliSecondName = $data['cliSecondName'];
        $cli->DocumentType = $data['DocumentType'];
        $cli->cliDocument = $data['cliDocument'];
        $cli->cliEmail = $data['cliEmail'];
        $cli->cliPhone = $data['cliPhone'];
        $cli->cliGender = $data['cliGender'];
        $cli->cliState = $data['cliState'];
        $cli->cliCreatedAt = now();
        $cli->cliUpdatedAt = now();
        $cli->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $cli = ClientsModel::find($id);
        if (!$cli) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $cli->delete();
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
        ClientsModel::whereIn('id', $ids)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\WarehousesModel;
use Illuminate\Http\Request;

class WarehousesController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $wh = WarehousesModel::with(
                'branch_office','employee','department','province','district'
            )
            ->where('deleted_at',null)
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRows = WarehousesModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $wh,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $wh = WarehousesModel::with('branch_office','employee','department','province','district')->where('deleted_at',null)->find($id);
        if (!$wh) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $wh;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $wh = new WarehousesModel;
        $wh->name = $data['name'];
        $wh->phone = $data['phone'];
        $wh->email = $data['email'];
        $wh->branch_office_id = $data['branch_office_id'];
        $wh->department_id = $data['department_id'];
        $wh->province_id = $data['province_id'];
        $wh->district_id = $data['district_id'];
        $wh->address = $data['address'];
        $wh->employee_id = $data['employee_id'];
        $wh->status = $data['status'];
        $wh->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(WarehousesModel $wh) {
        return $wh;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $wh = WarehousesModel::find($id);
        $wh->name = $data['name'];
        $wh->phone = $data['phone'];
        $wh->email = $data['email'];
        $wh->branch_office_id = $data['branch_office_id'];
        $wh->department_id = $data['department_id'];
        $wh->province_id = $data['province_id'];
        $wh->district_id = $data['district_id'];
        $wh->address = $data['address'];
        $wh->employee_id = $data['employee_id'];
        $wh->status = $data['status'];
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
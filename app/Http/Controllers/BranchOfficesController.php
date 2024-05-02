<?php

namespace App\Http\Controllers;

use App\Models\BranchOfficesModel;
use Illuminate\Http\Request;

class BranchOfficesController extends Controller {

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $offset = ($page - 1) * $perPage;
        $bo = BranchOfficesModel::with(
                'employees','departments','provinces','districts'
            )
            ->where('deleted_at',null)
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get();
        $totalRows = BranchOfficesModel::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $bo,
            'totalRows' => $totalRows
        ]);
    }

    public function getId($id) {
        $bo = BranchOfficesModel::with('employees','departments','provinces','districts')->where('deleted_at',null)->find($id);
        if (!$bo) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return [$bo];
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $bo = new BranchOfficesModel;
        $bo->name = $data['name'];
        $bo->phone = $data['phone'];
        $bo->email = $data['email'];
        $bo->department_id = $data['department_id'];
        $bo->province_id = $data['province_id'];
        $bo->district_id = $data['district_id'];
        $bo->address = $data['address'];
        $bo->employee_id = $data['employee_id'];
        $bo->status = $data['status'];
        $bo->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(BranchOfficesModel $bo) {
        return $bo;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $bo = BranchOfficesModel::find($id);
        $bo->name = $data['name'];
        $bo->phone = $data['phone'];
        $bo->email = $data['email'];
        $bo->department_id = $data['department_id'];
        $bo->province_id = $data['province_id'];
        $bo->district_id = $data['district_id'];
        $bo->address = $data['address'];
        $bo->employee_id = $data['employee_id'];
        $bo->status = $data['status'];
        $bo->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $bo = BranchOfficesModel::find($id);
        if (!$bo) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $bo->deleted_at = now();
        $bo->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = BranchOfficesModel::max('id');
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

        foreach ($ids as $bo_id) {
            BranchOfficesModel::whereId($bo_id)->update([
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
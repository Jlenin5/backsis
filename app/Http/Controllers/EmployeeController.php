<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class EmployeeController extends Controller {

    public function index() {
        $emp = EmployeeModel::with('documentType','avatars','workAreas','jobPositions')->where('deleted_at',null)->get();
        return $emp;
    }

    public function getId($id) {
        $emp = EmployeeModel::with('documentType','avatars','workAreas','jobPositions')->where('deleted_at',null)->findOrFail($id);
        if (!$emp) {
            return response()->json(['message' => 'No hay datos para mostrar'], 404);
        }
        return $emp;
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        $emp = new EmployeeModel;
        $emp->image = $data['image'];
        $emp->first_name = $data['first_name'];
        $emp->second_name = $data['second_name'];
        $emp->surname = $data['surname'];
        $emp->second_surname = $data['second_surname'];
        $emp->avatar_id = $data['avatar_id'];
        $emp->work_area_id = $data['work_area_id'];
        $emp->job_position_id = $data['job_position_id'];
        $emp->document_type = $data['document_type'];
        $emp->document_number = $data['document_number'];
        $emp->email = $data['email'];
        $emp->phone = $data['phone'];
        $emp->gender = $data['gender'];
        $emp->status = $data['status'];
        $emp->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(EmployeeModel $emp) {
        return $emp;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $emp = EmployeeModel::find($id);
        $emp->image = $data['image'];
        $emp->first_name = $data['first_name'];
        $emp->second_name = $data['second_name'];
        $emp->surname = $data['surname'];
        $emp->second_surname = $data['second_surname'];
        $emp->avatar_id = $data['avatar_id'];
        $emp->work_area_id = $data['work_area_id'];
        $emp->job_position_id = $data['job_position_id'];
        $emp->document_type = $data['document_type'];
        $emp->document_number = $data['document_number'];
        $emp->email = $data['email'];
        $emp->phone = $data['phone'];
        $emp->gender = $data['gender'];
        $emp->status = $data['status'];
        $emp->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Actualizado Correctamente']);
    }

    public function destroy($id) {
        $emp = EmployeeModel::find($id);
        if (!$emp) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $emp->deleted_at = now();
        $emp->update();
        return response()->json(['message' => 'Eliminado correctamente']);
    }

    public function getMaxId() {
        $ultimoId = EmployeeModel::max('id');
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

        foreach ($ids as $emp_id) {
            EmployeeModel::whereId($emp_id)->update([
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
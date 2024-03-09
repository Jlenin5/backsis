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
        $emp->id = $data['id'];
        $emp->empImage = $data['empImage'];
        $emp->empFirstName = $data['empFirstName'];
        $emp->empSecondName = $data['empSecondName'];
        $emp->empSurname = $data['empSurname'];
        $emp->empSecondSurname = $data['empSecondSurname'];
        $emp->DocumentType = $data['DocumentType'];
        $emp->Atavar = $data['Atavar'];
        $emp->WorkArea = $data['WorkArea'];
        $emp->JobPosition = $data['JobPosition'];
        $emp->empDocument = $data['empDocument'];
        $emp->empEmail = $data['empEmail'];
        $emp->empPhone = $data['empPhone'];
        $emp->empGender = $data['empGender'];
        $emp->empState = $data['empState'];
        $emp->save();
        return response()->json(['code'=>200,'status'=>'success','message'=>'Agregado correctamente']);
    }

    public function show(EmployeeModel $emp) {
        return $emp;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $emp = EmployeeModel::find($id);
        $emp->empImage = $data['empImage'];
        $emp->empFirstName = $data['empFirstName'];
        $emp->empSecondName = $data['empSecondName'];
        $emp->empSurname = $data['empSurname'];
        $emp->empSecondSurname = $data['empSecondSurname'];
        $emp->DocumentType = $data['DocumentType'];
        $emp->Atavar = $data['Atavar'];
        $emp->WorkArea = $data['WorkArea'];
        $emp->JobPosition = $data['JobPosition'];
        $emp->empDocument = $data['empDocument'];
        $emp->empEmail = $data['empEmail'];
        $emp->empPhone = $data['empPhone'];
        $emp->empGender = $data['empGender'];
        $emp->empState = $data['empState'];
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
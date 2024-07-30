<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Companies\Store;
use App\Http\Requests\Companies\Update;
use App\Models\CompaniesModel;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CompaniesController extends Controller {

    use ApiResponser;
    
    public function index(Request $request) {

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;

        return $this->getAll(
            CompaniesModel::search(['name','phone'])
                ->whereNull('deleted_at')
                ->with(['employee'])
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            CompaniesModel::whereNull('deleted_at')->count()
        );
    }

    public function store(Store $request) {
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        $data = $request->json()->all();
        $com = new CompaniesModel;
        if ($request->hasFile('image')) {
            // Genera un nombre de archivo único y guarda la imagen en la carpeta 'images/company'
            $currentDate = now()->format('Y-m-d');
            $companyCode = $data['code'];
            $uniqueFilename = md5($currentDate . $companyCode) . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/company', $uniqueFilename);
            $request['image'] = $uniqueFilename;
        } else {
            $request['image'] = '';
        }
        return $this->stored(
            CompaniesModel::create($request->input())->withData([])
        );
    }

    public function show(CompaniesModel $companies) {
        return $this->showOne($companies->withData([]));
    }

    public function update(Update $request, CompaniesModel $companies) {
        $request['user_update_id'] = auth()->user()->id;
        $data = $request->all();
        $com = CompaniesModel::find($data['id']);
        if ($request->hasFile('image')) {
            // Elimina la imagen anterior si existe
            if (!empty($com->image)) {
                $imagePath = public_path('images/company/' . $com->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            // Almacena la nueva imagen
            $currentDate = now()->format('Y-m-d');
            $companyCode = $data['code'];
            $uniqueFilename = md5($currentDate . $companyCode) . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/company', $uniqueFilename);
            $request['image'] = $uniqueFilename;
        } else {
            $request['image'] = $request->image;
        }
        $companies->update($request->input());
        return $this->updated($companies->withData([]));
    }

    public function destroy(CompaniesModel $companies) {
        return ResponseJson::destroy($companies->delete());
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
            CompaniesModel::whereId($id)->update([
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
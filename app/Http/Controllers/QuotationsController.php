<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Http\Requests\Quotations\Store;
use App\Models\QuotationsModel;
use App\Models\ProductQuotationsModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\ApiResponser;

class QuotationsController extends Controller {

    use ApiResponser;

    public function index(Request $request) {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $offset = ($page - 1) * $perPage;
        
        return $this->getAll(
            QuotationsModel::whereNull('deleted_at')
                ->withDataAll([
                    'product_quotations',
                    'customer',
                    'currency'
                ])
                ->offset($offset)
                ->limit($perPage)
                ->orderBy('created_at', 'desc')
                ->get(),
            QuotationsModel::whereNull('deleted_at')->count()
        );
    }

    public function show(QuotationsModel $quotations) {
        return $this->showOne($quotations->withData([
            'product_quotations',
            'customer',
            'currency'
        ]));
    }

    public function store(Store $request) {
        $lastCode = QuotationsModel::orderBy('id', 'desc')->first()->code ?? 'QT-00000';
        $lastNumber = (int)substr($lastCode, 5);
        $newNumber = $lastNumber + 1;
        $newCode = 'QT-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $request['code'] = $newCode;
        $request['user_create_id'] = auth()->user()->id;
        $request['user_update_id'] = auth()->user()->id;
        $save_quote = $this->stored(
            QuotationsModel::create($request->input())->withData([])
        );

        $last_quote = QuotationsModel::latest('id')->first();

        foreach($request['product_quotations'] as $item) {
            $product_quotation = new ProductQuotationsModel([
                'product_name' => $item['product_name'],
                'product_price' => $item['price'],
                'product_id' => $item['product_id'],
                'quotation_id' => $last_quote->id,
                'quantity' => $item['quantity'],
                'discount_method' => $item['discount_type'],
                'discount' => $item['discount'],
                'client_accept' => $item['client_accept'],
                'total' => $item['total'],
                'user_create_id' => auth()->user()->id,
                'user_update_id' => auth()->user()->id
            ]);
            $product_quotation->save();
        }

        return $save_quote;
    }

    public function update(Request $request, $id) {
        $data = $request->json()->all();
        $startDate = Carbon::parse($data['qtStartDate']);
        $endDate = Carbon::parse($data['qtEndDate']);
        $qt = QuotationsModel::find($id);
        $qt->SerialNumber = $data['SerialNumber'];
        $qt->qtNumber = $data['qtNumber'];
        $qt->Currency = $data['Currency'];
        $qt->Company = $data['Company'];
        $qt->BranchOffice = $data['BranchOffice'];
        $qt->Client = $data['Client'];
        $qt->User = $data['User'];
        $qt->qtStartDate = $startDate->format('Y-m-d');
        $qt->qtEndDate = $endDate->format('Y-m-d');
        $qt->qtSubtotal = $data['qtSubtotal'];
        $qt->qtIgv = $data['qtIgv'];
        $qt->qtTotal = $data['qtTotal'];
        // $qt->update();
        return response()->json(['code'=>200,'status'=>'success','message'=>$qt]);
    }

    public function destroy($id) {
        $qt = QuotationsModel::find($id);
        if (!$qt) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }
        $qt->delete();
        return response()->json(['message' => 'Eliminado correctamente']);
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
        QuotationsModel::whereIn('id', $id)->delete();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Elementos eliminadas correctamente.'
        ]);
    }
}
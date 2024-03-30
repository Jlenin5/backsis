<?php

namespace App\Exports;
use App\Models\PurchaseOrdersModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseOrderExcel implements FromView {
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View {
        $data = $this->data;
        if(!$data == null) {
            return view('export.excel.purchase_order', [
                'purchase_orders' => $data
            ]);
        }
        else {
            return view('export.excel.purchase_order', [
                'purchase_orders' => PurchaseOrdersModel::withDataAll()->get()
            ]);
        }
    }
}
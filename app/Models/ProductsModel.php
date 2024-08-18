<?php

namespace App\Models;

use App\Traits\BaseModelFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductsModel extends Model {

    use HasFactory, SoftDeletes, BaseModelFilter;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'code',
        'featured',
        'name',
        'description',
        'unit_id',
        'stock_alert',
        'purchase_price',
        'sale_price',
        'brand_id',
        'width',
        'height',
        'depth',
        'weight',
        'liters',
        'web_site',
        'status',
        'user_create_id',
        'user_update_id'
    ];

    protected $appends = ['stock','booking'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function user_create() {
        return $this->belongsTo(UsersModel::class, 'user_create_id')->withTrashed();
    }

    public function user_update() {
        return $this->belongsTo(UsersModel::class, 'user_update_id')->withTrashed();
    }

    public function categories() {
        return $this->belongsToMany(CategoriesModel::class, 'product_categories', 'product_id', 'category_id');
    }

    public function images() {
        return $this->hasMany(ProductImagesModel::class, 'product_id');
    }

    public function tax() {
        return $this->hasOne(ProductTaxesModel::class, 'product_id');
    }

    public function unit() {
        return $this->belongsTo(MeasurementUnitModel::class);
    }

    public function brand() {
        return $this->belongsTo(BrandsModel::class);
    }

    public function getStockAttribute() {
        $purchase_order_products = PurchaseOrderProductsModel::select('warehouse_id', DB::raw('sum(quantity) as total'))
            ->where('product_id', $this->id)
            ->whereHas("purchase_order", function ($query) {
                $query->where('status', 1);
                $query->whereNull('deleted_at');
            })
            // ->whereHas("purchase_order_workshop.purchase_order_workshop_details", function ($query) {
            //     $query->where('status', 'recibido');
            //     $query->where('deleted_at', null);
            // })
            ->whereHas("warehouse", function ($query) {
                $query->where('status', 1);
            })
            ->groupBy('warehouse_id')->get();

        //warehouse
        $warehouses = WarehousesModel::where('status', 1)->get();
        $id = [];
        $items = [];
        foreach ($purchase_order_products as $purchase_order_product) {
            $items[] = $purchase_order_product;
            $id[] = $purchase_order_product->warehouse_id;
        }
        foreach ($warehouses as $warehouse) {
            $wa[] = $warehouse->id;
            $unicos = array_diff($wa, $id);
        }
        $a = json_encode($items);
        $ab = json_decode($a, true);
        foreach ($unicos as $unico) {
            array_push($ab, [
                'warehouse_id' => $unico,
                'total' => 0
            ]);
        }
        
        // transfer
        // $warehouse_transfer_details = WarehouseTransferDetail::where('item_id', $this->id)
        //     ->whereHas("warehouse_transfer", function ($query) {
        //         $query->where('accordance', 1);
        //     })->get();
        // if($warehouse_transfer_details){
        //     foreach ($warehouse_transfer_details as $warehouse_transfer_detail) {
        //         $warehouse_transfer = WarehouseTransfer::where('id', $warehouse_transfer_detail->warehouse_transfer_id)->first();
        //         foreach ($ab as  $index => $item) {
        //             $item['total'] = (float)$item['total'];
        //             if ($item['warehouse_workshop_id'] == $warehouse_transfer->warehouse_origin_id) {

        //                 $item['total'] -= (float)$warehouse_transfer_detail->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }

        //             if ($item['warehouse_workshop_id']  == $warehouse_transfer->warehouse_destination_id) {

        //                 $item['total'] += (float)$warehouse_transfer_detail->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }
        //         }
        //     }
        // }

        // $item_price_workshop_quotations = ItemPriceWorkshopQuotation::where('status', 1)
        //                                         ->has("workshop_quotation.order_workshop.order_workshop_follows") //ventas
        //                                         ->whereHas("item_price", function ($query) {
        //                                             $query->where('item_id', $this->id);
        //                                         })->get();
        // if ($item_price_workshop_quotations) {
        //     foreach ($item_price_workshop_quotations as $item_price_workshop_quotation) {
        //         foreach ($ab as  $index => $item) {
        //             $item['total'] = (float)$item['total'];
        //             if ($item_price_workshop_quotation->warehouse_workshop_id == $item['warehouse_workshop_id']) {
        //                 $item['total'] -= (float)$item_price_workshop_quotation->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }
        //         }
        //     }
        // }

        // $counter_sale_details = CounterSaleDetail::whereHas("item_price", function ($query) {
        //                                             $query->where('item_id', $this->id);
        //                                         })
        //                                         ->whereHas("counter_sale", function ($query) {
        //                                             $query->where('status', '!=', 'pendiente');
        //                                         })->get();

        // if ($counter_sale_details) {
        //     foreach ($counter_sale_details as $counter_sale_detail) {
        //         foreach ($ab as  $index => $item) {
        //             $item['total'] = (float)$item['total'];
        //             if ($counter_sale_detail->counter_sale->warehouse_workshop_id == $item['warehouse_workshop_id']) {
        //                 $item['total'] -= (float)$counter_sale_detail->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }
        //         }
        //     }
        // }

        $items = [];
        foreach ($ab as  $item) {
            $items[] = $item;
        }

        return count($items) ? $items : [];
    }

    public function getBookingAttribute() {
        $purchase_order_products = PurchaseOrderProductsModel::select('warehouse_id', DB::raw('sum(quantity) as total'))
            ->where('product_id', $this->id)
            ->whereHas("purchase_order", function ($query) {
                $query->where('status', '1');
                $query->where('deleted_at', null);
            })
            // ->whereHas("purchase_order_workshop.purchase_order_workshop_details", function ($query) {
            //     $query->where('status', 'recibido');
            //     $query->where('deleted_at', null);
            // })
            ->whereHas("warehouse", function ($query) {
                $query->where('status', '1');
            })
            ->groupBy('warehouse_id')->get();

        //warehouse
        $warehouses = WarehousesModel::where('status', 1)->get();
        $id = [];
        $items = [];
        foreach ($purchase_order_products as $purchase_order_item) {
            $items[] = $purchase_order_item;
            $id[] = $purchase_order_item->warehouse_id;
        }
        foreach ($warehouses as $warehouse) {

            $wa[] = $warehouse->id;

            $unicos = array_diff($wa, $id);
        }
        $a = json_encode($items);
        $ab = json_decode($a, true);
        foreach ($unicos as $unico) {
            array_push($ab, [
                'warehouse_id' => $unico,
                'total' => 0
            ]);
        }

        // transfer
        // $warehouse_transfer_details = WarehouseTransferDetail::where('item_id', $this->id)
        //     ->whereHas("warehouse_transfer", function ($query) {
        //         $query->where('accordance', 1);
        //     })->get();

        // if($warehouse_transfer_details){
        //     foreach ($warehouse_transfer_details as $warehouse_transfer_detail) {


        //         $warehouse_transfer = WarehouseTransfer::where('id', $warehouse_transfer_detail->warehouse_transfer_id)->first();

        //         foreach ($ab as  $index => $item) {

        //             $item['total'] = (float)$item['total'];
        //             if ($item['warehouse_workshop_id'] == $warehouse_transfer->warehouse_origin_id) {

        //                 $item['total'] -= (float)$warehouse_transfer_detail->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }

        //             if ($item['warehouse_workshop_id']  == $warehouse_transfer->warehouse_destination_id) {

        //                 $item['total'] += (float)$warehouse_transfer_detail->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }
        //         }
        //     }
        // }

        $item_price_workshop_quotations = QuotationsModel:://doesntHave("workshop_quotation.order_workshop.order_workshop_follows") //ventas
                                                                    where('status', 1)
                                                                    // ->whereHas("item_price", function ($query) {
                                                                    //     $query->where('item_id', $this->id);
                                                                    // })
                                                                    // ->whereHas("workshop_quotation", function ($query) {
                                                                    //     $query->where('status', 1)->whereNull('deleted_at');
                                                                    // })
                                                                    // ->whereHas("workshop_quotation.order_workshop", function ($query) {
                                                                    //     $query->where('status', 1)->whereNull('deleted_at');
                                                                    // })
                                                                    ->get();

        // if ($item_price_workshop_quotations) {
        //     foreach ($item_price_workshop_quotations as $item_price_workshop_quotation) {
        //         foreach ($ab as  $index => $item) {
        //             $item['total'] = (float)$item['total'];
        //             if ($item_price_workshop_quotation->warehouse_workshop_id == $item['warehouse_workshop_id']) {
        //                 $item['total'] -= (float)$item_price_workshop_quotation->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }
        //         }
        //     }
        // }

        // $counter_sale_details = CounterSaleDetail::whereHas("item_price", function ($query) {
        //                                                         $query->where('item_id', $this->id);
        //                                                     })
        //                                                     // ->whereHas("counter_sale", function ($query) {
        //                                                     //     $query->where('status', '!=', 'cancelado');
        //                                                     // })
        //                                                     ->has('counter_sale')
        //                                                     ->get();

        // if ($counter_sale_details) {
        //     foreach ($counter_sale_details as $counter_sale_detail) {
        //         foreach ($ab as  $index => $item) {
        //             $item['total'] = (float)$item['total'];
        //             if ($counter_sale_detail->counter_sale->warehouse_workshop_id == $item['warehouse_workshop_id']) {
        //                 $item['total'] -= (float)$counter_sale_detail->amount;
        //                 array_push($ab, [
        //                     'warehouse_workshop_id' => $item['warehouse_workshop_id'],
        //                     'total' => (float)$item['total']
        //                 ]);
        //                 if ($item['warehouse_workshop_id'] == $item['warehouse_workshop_id']) {
        //                     unset($ab[$index]);
        //                 }
        //             }
        //         }
        //     }
        // }

        $items = [];
        foreach ($ab as  $item) {
            $items[] = $item;
        }

        return count($items) ? $items : [];
    }

    public function quotes() {
        // return $this->belongsToMany(QuotationsModel::class, 'quote_details', 'product_id', 'quotation_id')->withPivot('Quotation', 'Product');
    }
}
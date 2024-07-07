<?php

namespace App\Traits;

use function PHPSTORM_META\map;

trait BaseModelFilter {

    public function scopePaginator($query) {
        if (request()->has('paginate')) {
            return $query->paginate(request()->limit);
        }
        return $query->get();
    }

    public function scopeOrder_by($query, $fieldOrder = false) {
        if (request()->has('order_by')) {
            $orderBy = explode(',', request()->order_by);
            return $query->orderBy($orderBy[0], $orderBy[1]);
        }
        if ($fieldOrder) {
            return $query->orderBy($fieldOrder[0], $fieldOrder[1]);
        }
    }

    public function scopeSearch($query, $inputs = [], $relation = false) {
        if (request()->search) {
            $search = request()->get('search');
            if ($relation == true) {
                $query->whereHas("$relation", function ($subquery) use ($search, $inputs) {
                    foreach ($inputs as $key => $input) {
                        if ($key === 0) {
                            $subquery->where($input, 'like', "%{$search}%");
                        } else {
                            $subquery->orWhere($input, 'like', "%{$search}%");
                        }
                    }
                });
            } else {
                foreach ($inputs as $key => $input) {
                    if ($key === 0) {
                        $query->where($input, 'like', "%{$search}%");
                    } else {
                        $query->orWhere($input, 'like', "%{$search}%");
                    }
                }
            }
        }
    }

    public function scopeFilters($query) {
        if (request()->has('filters')) {
            $filters = explode('|', request()->input('filters'));
            foreach ($filters as $filter) {
                $filter = explode('=', $filter);
                $is_like = false;
                if (strpos($filter[1], "like") !== false) {
                    $is_like = true;
                    $filter[1] = str_replace("like", "", $filter[1]);
                }
                if ($is_like)
                    $query->where($filter[0], 'like', '%' . $filter[1] . '%');
                else
                    $query->where($filter[0], $filter[1]);
            }
        }
    }

    public function scopeWithDataAll($query, $with = []) {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $query->with($with);
        }
        return $query->with($with);
    }

    public function scopeWithData($query, $with = []) {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $this->load($with);
        }
        return $this->load($with);
    }

    public function scopeSearchFilters($query, $relation = false) {
        if (request()->has('search_filters')) {
            $search_filters = explode('|', request()->input('search_filters'));
            $relation_complement = '';
            $point = '';
            $relation_exists = false;
            foreach ($search_filters as $search_filter) {
                $search_filter = explode('=', $search_filter);
                if ($relation == true)
                    $point = '.';
                else
                    $point = '';

                switch ($search_filter[0]) {
                    case 'branch_office_id':
                    case 'company_id':
                        $relation_exists=true;
                        $point='';
                        break;
                    case 'store_id':
                        // $relation_complement = 'list_price';
                        // $relation_exists = true;
                        break;
                    case 'mark_id':
                    case 'mark_model_id':
                    case 'vehicle_id':
                    case 'year_id':
                        $relation_complement = '';
                        $relation_exists = true;
                        $point = '';
                        break;
                    case 'type_id':
                    case 'clas_id':
                    case 'fuel_id':
                        $relation_complement = 'vehicle';
                        $relation_exists = true;
                        break;
                    case 'provider_id':
                        if ($relation == "quotation.vehiclePrice") {
                            $relation_complement = 'list_price';
                            $relation_exists = true;
                            break;
                        }
                    case 'list_price_id':
                    case 'customer_id':
                    case 'seller_id':
                    case 'is_approval':
                        $relation_complement = '';
                        $relation_exists = false;
                        break;
                    case 'line_id':
                        if ($relation == "quotation.vehiclePrice") {
                            $relation_complement = 'vehicle';
                            $relation_exists = true;
                            break;
                        }
                        if ($relation == "vehiclePrice") {
                            $relation_complement = 'vehicle';
                            $relation_exists = true;
                            break;
                        }
                    case 'family_id':
                        if ($relation == "quotation.vehiclePrice") {
                            $relation_complement = 'vehicle';
                            $relation_exists = true;
                            break;
                        }
                        if ($relation == "vehiclePrice") {
                            $relation_complement = 'vehicle';
                            $relation_exists = true;
                            break;
                        }
                    default:
                        # code...
                        break;
                }

                if ($relation_exists == true) {
                    // if ($relation == "quotation.vehiclePrice") {
                    //     $query->whereHasMorph("quotation", [Quotation::class], function ($subquery) use ($search_filter, $point, $relation_complement) {
                    //         $subquery->whereHas("vehiclePrice" . $point . $relation_complement, function ($subquery) use ($search_filter) {
                    //             $subquery->where($search_filter[0], $search_filter[1]);
                    //         });
                    //     });
                    // } else if ($relation == "order.quotation.vehiclePrice") {
                    //     $query->whereHas('order', function ($query) use ($search_filter, $point, $relation_complement) {
                    //         $query->whereHasMorph("quotation", [Quotation::class], function ($subquery) use ($search_filter, $point, $relation_complement) {
                    //             $subquery->whereHas("vehiclePrice" . $point . $relation_complement, function ($subquery) use ($search_filter) {
                    //                 $subquery->where($search_filter[0], $search_filter[1]);
                    //             });
                    //         });
                    //     });
                    // } else {
                    //     $query->whereHas("$relation" . "$point" . "$relation_complement", function ($subquery) use ($search_filter) {
                    //         $subquery->where($search_filter[0], $search_filter[1]);
                    //     });
                    // }
                } else {
                    $query->where($search_filter[0], $search_filter[1]);
                }
            }
        }
    }

    public function scopeStartDate($query, $order = false) {
        if (request()->start_date) {
            if ($order == false){
                $date = 'created_at';
            }
            else{
                if($order == 'appointment' || $order == 'kardex'){
                    $date = "date";
                }
                else{
                    if($order == 'order_workshop_payments' || $order == 'counter_sale_payments'){
                        $date = "date_payment";
                    }
                    else{
                        $date = $order . "_date";
                    }
                }
            }

            $start_date = request()->get('start_date');

            $query->whereDate($date, '>=', $start_date);
        }
    }
    public function scopeEndDate($query, $order = false) {
        if (request()->end_date) {
            if ($order == 'order'){
                $date = 'order_date';
            }
            else{
                if($order == 'payment'){
                    $date = 'payment_date';
                }
                else{
                    if($order == 'appointment' || $order == 'kardex'){
                        $date = 'date';
                    }
                    else{
                        $date = 'created_at';
                    }
                }
            }                
            $end_date = request()->get('end_date');
            $query->whereDate($date, '<=', $end_date);
        }
    }

}
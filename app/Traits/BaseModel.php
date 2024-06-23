<?php

namespace App\Traits;

trait BaseModel {

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

  public function scopeFilters($query) {
    if (request()->has('filters')) {
      $filters = explode('|', request()->input('filters'));
      foreach ($filters as $filter) {
        $filter = explode('=', $filter);
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

  public function scopeWithDataCountAll($query, $couunt = []) {
    if (request()->has('count')) {
      $count = explode('|', request()->input('count'));
      return $query->withCount($count);
    }
    return $query->withCount($couunt);
  }

  public function scopeWithData($query, $with = []) {
    if (request()->has('with')) {
      $with = explode('|', request()->input('with'));
      return $this->load($with);
    }
    return $this->load($with);
  }

  public function scopeWithDataCount($query, $count = []) {
    if (request()->has('with')) {
      $count = explode('|', request()->input('with'));
      return $this->load($count);
    }
    return $this->load($count);
  }

  public function scopeLoadData($event = null, $with = []) {
    if (request()->has('with')) {
      $with = explode('|', request()->input('with'));
      return $this->load($with);
    }
    return $this->load($with);
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
}
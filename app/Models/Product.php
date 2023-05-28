<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['product_name', 'company_id', 'price', 'stock', 'comment','img_path'];

    public function Company()
{
    return $this->belongsTo('App\Models\Company');
}

public function Sale()
{
    return $this->hasMany('App\Models\Sale');
}

}

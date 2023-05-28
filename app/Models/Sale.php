<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
    protected $fillable = ['product_id'];

    public function Product()
{
    return $this->belongsTo('App\Models\Product');
}

}

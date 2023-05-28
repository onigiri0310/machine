<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['company_name', 'street_address', 'representative_name',];

    public function Product()
    {
        return $this->hasMany('App\Models\Product');
    }
}
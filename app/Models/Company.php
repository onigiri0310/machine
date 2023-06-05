<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['company_name'];

    public static function getAllCompanies()
    {
        return self::all();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

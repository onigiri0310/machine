<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['product_name', 'company_id', 'price', 'stock', 'comment', 'img_path'];

    public static function getAllProducts()
    {
        return self::all();
    }

    public static function searchProducts($productName, $companyId)
    {
        $query = self::query();

        if ($productName) {
            $query->where('product_name', 'LIKE', '%' . $productName . '%');
        }

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->get();
    }

    public static function getProductById($id)
    {
        return self::find($id);
    }

    public static function deleteProduct($id)
    {
        $product = self::find($id);

        if ($product) {
            // 画像ファイルの削除
            $imagePath = str_replace('storage/', '', $product->img_path);
            Storage::disk('public')->delete($imagePath);

            return $product->delete();
        }

        return false;
    }


public static function createProduct($data)
{
    // 画像ファイルの保存
    if (isset($data['img_path'])) {
        $imagePath = $data['img_path'];
        $data['img_path'] = 'images/' . basename($imagePath);
    }

    return self::create($data);
}

public static function updateProduct($id, $data)
{
    $product = self::find($id);

    if ($product) {
        // 画像ファイルの保存
        if (isset($data['img_path'])) {
            $imagePath = $data['img_path'];
            $data['img_path'] = 'images/' . basename($imagePath);
        }

        $product->update($data);

        return $product;
    }

    return null;
}


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
}

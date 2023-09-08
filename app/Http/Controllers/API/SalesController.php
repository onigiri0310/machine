<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function create(Request $request){
        //購入情報を取得
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // トランザクションを開始
        DB::beginTransaction();

        try {
            // 商品の在庫数を取得
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['message' => '商品が存在しません'], 404);
            }

            // 在庫が残っているかの確認
            if ($product->stock < $quantity) {
                return response()->json(['message' => '在庫が不足しています'], 400);
            }

            // 購入処理
            $sale = new Sale();
            $sale->product_id = $productId;
            $sale->quantity = $quantity;
            $sale->save();

            // 在庫減算処理
            $product->stock -= $quantity;
            $product->save();

            // トランザクションをコミット
            DB::commit();

            return response()->json(['message' => '購入が完了しました'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => '購入処理中にエラーが発生しました'], 500);
        }
    }
}
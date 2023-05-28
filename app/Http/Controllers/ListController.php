<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ListController extends Controller
{
    public function list(){
        $products = Product::all();
        $companies = Company::all();

        //ビューに渡して表示
        return view('list',compact('products'),compact('companies'));
    }

    public function search(Request $request)
    {
        $products = Product::all();
        $companies = Company::all();

        //入力された条件の取得
        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');

        //商品検索クエリを作成
        $query = Product::query();

        //商品名の部分一致
        if($productName){
            $query->where('product_name','LIKE','%'.$productName.'%');
        }

        //メーカー名
        if($companyId){
            $query->where('company_id',$companyId);
        }

        //検索結果の取得
        $products = $query->get();

        //ビューに渡して表示
        return view('list',compact('products'),compact('companies'));

    }

    public function show($id)
    {
        $product = Product::find($id);

        return view('detail', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $imagePath = str_replace('storage/', '', $product->img_path);
        \Illuminate\Support\Facades\Storage::delete('public/' . $imagePath);
        $product->delete();

        return redirect()->route('list')->with('success', '商品が削除されました');
    }

}

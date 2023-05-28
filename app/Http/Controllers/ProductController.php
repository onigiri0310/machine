<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{

    public function ProductRegister(){
        $companies = Company::all();
        return view('product_register', compact('companies'));
    }


    public function store(Request $request)
    {
        // リクエストデータを取得
        $data = $request->all();

        // 画像ファイルの保存
        if (isset($data['image'])) {
            $image = $data['image'];
            $imagePath = $image->store('images', 'public');
            $data['img_path'] = $imagePath;
        }

        // データベースに保存
        $products = new Product();
        $products->product_name = $data['product_name'];
        $products->company_id = $data['company_id'];
        $products->price = $data['price'];
        $products->stock = $data['stock'];
        $products->comment = $data['comment'];
        $products->img_path = $data['img_path'];
        $products->save();

        // 保存後の処理（リダイレクトなど）を追加

        return redirect('/product_register')->with('success', '登録しました');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $companies = Company::all();

        return view('edit', compact('product'),compact('companies'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->input('product_name');
        $product->company_id = $request->input('company_id');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');
        $product->img_path = $request->input('img_path');

        $product->save();

        return redirect()->route('detail',$product->id)->with('success', '更新しました');
    }

}

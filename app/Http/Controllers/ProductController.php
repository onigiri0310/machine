<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Exception;

class ProductController extends Controller
{
    public function list()
    {
        $products = Product::getAllProducts();
        $companies = Company::getAllCompanies();

        return view('list', compact('products', 'companies'));
    }

    public function search(Request $request)
    {
        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');

        $products = Product::searchProducts($productName, $companyId);
        $companies = Company::getAllCompanies();

        return view('list', compact('products', 'companies'));
    }

    public function show($id)
    {
        $product = Product::getProductById($id);

        return view('detail', compact('product'));
    }

    public function destroy($id)
    {
        try{
            $result = Product::deleteProduct($id);

            if ($result) {
                return redirect()->route('list')->with('success', '商品が削除されました');
            } else {
                return redirect()->route('list')->with('error', '商品の削除に失敗しました');
            }
        }catch(Exception $e) {
            return redirect()->route('list')->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    public function ProductRegister()
    {
        $companies = Company::getAllCompanies();

        return view('product_register', compact('companies'));
    }

    public function store(Request $request)
    {
        try{
            // リクエストデータを取得
            $data = $request->all();

            // 画像ファイルの保存
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public');
                $data['img_path'] = $imagePath;
            }

            // データベースに保存
            $product = Product::createProduct($data);

            if ($product) {
                return redirect('/product_register')->with('success', '登録しました');
            } else {
                return redirect('/product_register')->with('error', '登録に失敗しました');
            }
        }catch (Exception $e) {
            return redirect('/product_register')->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::getProductById($id);
        $companies = Company::getAllCompanies();

        return view('edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
    {
        try{
            $product = Product::updateProduct($id, $request->all());

            if ($product) {
                return redirect()->route('detail', $product->id)->with('success', '更新しました');
            } else {
                return redirect()->route('detail', $id)->with('error', '更新に失敗しました');
            }
        }catch (Exception $e) {
            // エラー処理
            return redirect()->route('detail', $id)->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }
}

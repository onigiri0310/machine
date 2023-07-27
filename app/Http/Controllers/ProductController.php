<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minStock = $request->input('min_stock');
        $maxStock = $request->input('max_stock');

        $products = Product::query();

        if (!empty($minPrice)) {
            $products->where('price', '>=', $minPrice);
        }

        if (!empty($maxPrice)) {
            $products->where('price', '<=', $maxPrice);
        }

        if (!empty($minStock)) {
            $products->where('stock', '>=', $minStock);
        }

        if (!empty($maxStock)) {
            $products->where('stock', '<=', $maxStock);
        }

        $products = $products
            ->when($productName, function ($query, $productName) {
                return $query->where('product_name', 'LIKE', "%$productName%");
            })
            ->when($companyId, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            })
            ->get();

        $companies = Company::getAllCompanies();

        $results = $products;

        return view('list', ['results' => $results,'companies' => $companies,'products' => $products]);
    }

    public function show($id)
    {
        $product = Product::getProductById($id);

        return view('detail', compact('product'));
    }

    public function destroy($id)
    {
        try {
            $result = Product::deleteProduct($id);

            if ($result) {
                return view('list', compact('products', 'companies'));
            } else {
                return response()->json(['success' => false, 'message' => '商品の削除に失敗しました']);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'エラーが発生しました: ' . $e->getMessage()]);
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

    public function getListAjax()
    {
        // 商品一覧を取得する処理（Eloquentクエリなど）
        $products = Product::all();

        // 取得した商品一覧データをJSON形式で返す
        return response()->json($products);
    }

    public function index()
    {
        // データの取得
        $products = Product::orderBy('id', 'desc')->get();

        return view('list', ['products' => $products]);
    }

    public function sort($column)
    {
        // カラムに基づいてデータをソート
        $products = Product::orderBy($column, 'asc')->get();

        return view('list', ['products' => $products]);
    }

}

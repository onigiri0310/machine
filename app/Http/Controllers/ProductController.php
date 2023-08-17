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

        $products = Product::query()
            ->when($productName, function ($query, $productName) {
                return $query->where('product_name', 'LIKE', "%$productName%");
            })
            ->when($companyId, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            })
            ->get();

        $companies = Company::getAllCompanies();

        // 各商品に関連する company_name を追加する
        foreach ($products as $product) {
            // 商品が所属する会社の情報を取得し、company_name を取得して追加する
            $product->company_name = $product->company->company_name;
        }

        $results = $products;

        $data = ['results' => $results, 'companies' => $companies, 'products' => $products];
        return response()->json($data);
    }




    public function show($id)
    {
        $product = Product::getProductById($id);

        return view('detail', compact('product'));
    }




    public function destroy($id)
    {
        DB::beginTransaction(); //トランザクション開始

        try {
            $result = Product::deleteProduct($id);

            if ($result) {
                DB::commit(); //成功時コミット
            } else {
                DB::rollback(); //失敗時ロールバック
                return response()->json(['success' => false, 'message' => '商品の削除に失敗しました']);
            }

            //削除成功時
            $products = Product::getAllProducts();
            $companies = Company::getAllCompanies();
            return response()->json(['success' => true, 'message' => '削除が成功しました']);
            return view('list',compact('products','companies'));

        } catch (Exception $e) {
            DB::rollback(); //エラー時ロールバック
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
        // 商品一覧を取得する処理
        $products = Product::all();

        // 各商品に関連する company_name を追加する
        foreach ($products as $product) {
            // 商品が所属する会社の情報を取得し、company_name を取得して追加する
            $product->company_name = $product->company->company_name;
        }

        // データを JSON 形式でクライアントに返す
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
        // プロダクトテーブルのカラムを指定する
        if ($column === 'company_name') {
            $products = Product::join('companies', 'products.company_id', '=', 'companies.id')
                ->orderBy('companies.company_name', 'asc')
                ->select('products.*')
                ->get();
        } else {
            $products = Product::orderBy($column, 'asc')->get();
        }

        // メーカー情報
        $products->load('company');

        // メーカー一覧の取得
        $companies = Company::all();

        return view('list', compact('products', 'companies'));
    }

}

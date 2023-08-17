@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-3">
                    {{ __('商品一覧') }}
                </div>

                <form id="search-form" class="form-inline my-2 my-lg-0 ml-2" action="{{ route('search') }}" method="GET">
                    商品名<input class="ProductName" type="text" name="product_name"><br>
                    メーカー名<select class="MakerName" name="company_id">
                            <option value="">選択してください</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                    </select><br>
                    <input type="number" name="min_price" placeholder="最低価格">
                    <input type="number" name="max_price" placeholder="最高価格"><br>
                    <input type="number" name="min_stock" placeholder="最低在庫数">
                    <input type="number" name="max_stock" placeholder="最大在庫数"><br>
                    <input type="submit" name="submit" value="検索">
                </form>

                <div class="col-md-6">
                    <button type="button" onclick="location.href='{{ route('ProductRegister') }}'" class="btn btn-primary">
                        {{ __('新規登録') }}
                    </button>
                </div>

                <div class="col-12" id="product-list">
                    <table class="col-10">
                        <tr>
                            <th class="sortable" data-column="id" data-sort="desc"><a href="{{ route('sort', ['column' => 'id', 'sort' => 'desc']) }}">ID</a></th>
                            <th class="sortable" data-column="img_path" data-sort="asc"><a href="{{ route('sort', ['column' => 'img_path', 'sort' => 'asc']) }}">商品画像</a></th>
                            <th class="sortable" data-column="product_name" data-sort="asc"><a href="{{ route('sort', ['column' => 'product_name', 'sort' => 'asc']) }}">商品名</a></th>
                            <th class="sortable" data-column="price" data-sort="asc"><a href="{{ route('sort', ['column' => 'price', 'sort' => 'asc']) }}">価格</a></th>
                            <th class="sortable" data-column="stock" data-sort="asc"><a href="{{ route('sort', ['column' => 'stock', 'sort' => 'asc']) }}">在庫数</a></th>
                            <th class="sortable" data-column="company_name" data-sort="asc"><a href="{{ route('sort', ['column' => 'company_name', 'sort' => 'asc']) }}">メーカー名</a></th>
                        </tr>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><img src="{{ asset('storage/' . $product->img_path) }}" class="img-fluid col-6"></td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->company->company_name }}</td>
                            <td>
                                <form action="{{ route('detail', $product->id) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">詳細</button>
                                </form>
                            </td>
                            <td>
                                <form
                                    action="{{ route('destroy', $product->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('本当に削除しますか？');"
                                    data-id="{{ $product->id }}">

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" data-id="{{ $product->id }}">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="js/product.js"></script>
@endsection

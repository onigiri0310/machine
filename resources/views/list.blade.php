@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-3">
                    {{ __('商品一覧') }}
                </div>

                <form id="search-form" class="form-inline my-2 my-lg-0 ml-2" action="{{ route('search') }}" method="GET" >
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
                    </button >
                </div>

                <div class="col-12" id="product-list">
                    <table class="col-10">
                        <tr>
                            <th><a href="{{ route('sort', 'id') }}">ID</a></th>
                            <th><a href="{{ route('sort', 'img_path') }}">商品画像</a></th>
                            <th><a href="{{ route('sort', 'product_name') }}">商品名</a></th>
                            <th><a href="{{ route('sort', 'price') }}">価格</a></th>
                            <th><a href="{{ route('sort', 'stock') }}">在庫数</a></th>
                            <th><a href="{{ route('sort', 'company_name') }}">メーカー名</a></th>
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
                                    <form action="{{ route('detail',$product->id) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">詳細</button>
                                    </form>
                                </td>
                                <td>
                                    <form
                                        action="{{ route('destroy',$product->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('本当に削除しますか？'); deleteProduct(event, {{ $product->id }});">

                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">削除</button>
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

@section('scripts')
<script>
$(document).ready(function() {
    // 商品一覧の非同期表示
    $.ajax({
        url: "{{ route('getListAjax') }}",
        type: "GET",
        dataType:"json",
    }).then(
         function(response) {
         // 取得したデータを表示する処理
         let productList = $('#product-list');

         // テーブルヘッダーを作成
        let tableHeader = $('<tr><th>ID</th><th>商品画像</th><th>商品名</th><th>価格</th><th>在庫数</th><th>メーカー名</th></tr>');
        productList.append(tableHeader);

        // 商品データをテーブルに追加
         response.products.forEach(function(product) {
            let tableRow = $('<tr></tr>');
            tableRow.append('<td>' + product.id + '</td>');
            tableRow.append('<td><img src="' + product.img_path + '" class="img-fluid col-6"></td>');
            tableRow.append('<td>' + product.product_name + '</td>');
            tableRow.append('<td>' + product.price + '</td>');
            tableRow.append('<td>' + product.stock + '</td>');
            tableRow.append('<td>' + product.company_name + '</td>');
            productList.append(tableRow);
        }),

            },

            function(xhr, status, error) {
                // エラーハンドリングの処理
            }
        );



     // 検索フォームの送信イベント
    $('#search-form').submit(function(event) {
        event.preventDefault();

        // 入力されたキーワードとメーカー名を取得
        let productName = $('.ProductName').val();
        let makerName = $('.MakerName').val();

        // 商品一覧を非同期で取得
        $.ajax({
            url: "{{ route('search') }}",
            type: "GET",
            dataType:"json",
            data: {
                product_name: productName,
                company_id: makerName
            },
        }).then(
            function(response) {
                // テーブルをクリア
                $('#product-list').empty();

                // テーブルヘッダーを作成
                let tableHeader = $('<tr><th>ID</th><th>商品画像</th><th>商品名</th><th>価格</th><th>在庫数</th><th>メーカー名</th></tr>');
                $('#product-list').append(tableHeader);

                // 商品データをテーブルに追加
                response.products.forEach(function(product) {
                    var tableRow = $('<tr></tr>');
                    tableRow.append('<td>' + product.id + '</td>');
                    tableRow.append('<td><img src="{{ asset("storage") }}/' + product.img_path + '" class="img-fluid col-6"></td>');
                    tableRow.append('<td>' + product.product_name + '</td>');
                    tableRow.append('<td>' + product.price + '</td>');
                    tableRow.append('<td>' + product.stock + '</td>');
                    tableRow.append('<td>' + product.company.company_name + '</td>');
                    $('#product-list').append(tableRow);
                });
            },
            function(xhr, status, error) {
                // エラーハンドリングの処理
            }
        );
    });



    function deleteProduct(event,productId){
        event.preventDefault();

        //削除リクエスト送信
        $.ajax({
            url: '/products/${productId}',
            type: 'DELETE',
            dataType: 'json',
            success: function (data) {
                //削除が成功したとき
                if(data.success) {
                    //非表示にする
                    $('tr[data-product-id="' + productId + '"]').hide();
                } else {
                    //失敗したとき
                    alert(data.message);
                }
            },
            error: function (xhr,status,error) {
                //エラーハンドリング
                alert('エラーが発生しました');
            },
        });
    }
});
</script>


@endsection

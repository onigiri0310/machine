@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-3">
                    {{ __('商品一覧') }}
                </div>

                <form class="form-inline my-2 my-lg-0 ml-2" action="{{ route('search') }}" method="GET" >
                    商品名<input class="ProductName" type="text" name="product_name"><br>
                    メーカー名<select class="MakerName" name="company_id">
                            <option value="">選択してください</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                    </select><br>
                    <input type="submit" name="submit" value="検索">
                </form>

                <div class="col-md-6">
                    <button type="button" onclick="location.href='{{ route('ProductRegister') }}'" class="btn btn-primary">
                        {{ __('新規登録') }}
                    </button >
                </div>

                <div class="col-12">
                    <table class="col-10">
                        <tr>
                            <th>ID</th>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫数</th>
                            <th>メーカー名</th>
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
                                <form action="{{ route('destroy',$product->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
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
@endsection

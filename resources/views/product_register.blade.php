@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-3">
                    {{ __('商品新規登録') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">商品名:</label>
                            <input type="text" id="product_name" name="product_name" required>
                        </div>

                        <div class="form-group">
                            <label for="company_id">メーカー:</label>
                            <select id="company_id" name="company_id" required>
                                <option value="">選択してください</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">価格</label>
                            <input type="number" id="price" name="price" required>
                            <span>円</span>
                        </div>

                        <div class="form-group">
                            <label for="stock">在庫数</label>
                            <input type="number" id="stock" name="stock" required>
                        </div>

                        <div class="form-group">
                            <label for="comment">コメント</label>
                            <textarea id="comment" name="comment"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="img_path">商品画像</label>
                            <input type="file" id="img_path" name="image">
                        </div>

                        <button class="btn btn-primary" type="submit" class="button">登録</button>
                    </form>

                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <div class="col-md-6">
                        <button type="button" onclick="location.href='{{ route('list') }}'" class="btn btn-secondary">
                            {{ __('戻る') }}
                        </button >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

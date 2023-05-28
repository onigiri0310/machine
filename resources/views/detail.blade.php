@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-3">
                    {{ __('商品詳細') }}
                </div>

                <div class="card-body">
                    <p>ID: {{ $product->id }}</p>
                    <img src="{{ asset('storage/' . $product->img_path) }}" class="img-fluid">
                    <p>商品名: {{ $product->product_name }}</p>
                    <p>メーカー名: {{ $product->company->company_name }}</p>
                    <p>価格: {{ $product->price }}</p>
                    <p>在庫数: {{ $product->stock }}</p>
                    <p>コメント: {{ $product->comment }}</p>

                </div>

                <div class="col-md-6">
                    <button type="button" onclick="location.href='{{ route('edit',$product->id) }}'" class="btn btn-primary">
                        {{ __('編集') }}
                    </button>
                </div>

                <div class="col-md-6">
                    <button type="button" onclick="location.href='{{ route('list') }}'" class="btn btn-secondary">
                        {{ __('戻る') }}
                    </button >
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

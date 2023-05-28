@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-3">
                    {{ __('商品編集') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('update',$product->id) }}" enctype="multipart/form-data" id="new">
                            @csrf
                            @method('PUT')

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
                                <label for="img-path">商品画像</label>
                                <input type="file" id="img-path" name="image">
                            </div>

                            <div class="col-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('登録') }}
                                </button>
                            </div>
                        </form>

                        <div class="col-6">
                            <button type="button" onclick="location.href='{{ route('detail',$product->id) }}'" class="btn btn-secondary">
                                {{ __('戻る') }}
                            </button >
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

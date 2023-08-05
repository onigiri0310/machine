console.log("通った");

$(document).ready(function() {
    // 商品一覧の非同期表示
    function displayProductList() {
        $.ajax({
            url: getListAjaxUrl,
            type: "GET",
            dataType: "json",
            success: function(response) {
                // 取得したデータを表示する処理
                let productList = $('#product-list');
                productList.empty();
                console.log("いちらん");

                // テーブルヘッダーを作成
                let tableHeader = $('<tr><th class="sortable" data-column="id" data-sort="desc">ID</th><th class="sortable" data-column="img_path" data-sort="asc">商品画像</th><th class="sortable" data-column="product_name" data-sort="asc">商品名</th><th class="sortable" data-column="price" data-sort="asc">価格</th><th class="sortable" data-column="stock" data-sort="asc">在庫数</th><th class="sortable" data-column="company_name" data-sort="asc">メーカー名</th></tr>');
                productList.append(tableHeader);

                // 商品データをテーブルに追加
                response.forEach(function(product) {
                    let tableRow = $('<tr></tr>');
                    tableRow.append('<td>' + product.id + '</td>');
                    tableRow.append('<td><img src="http://localhost:8888/machine/public/storage/' + product.img_path + '" class="img-fluid col-6"></td>');
                    tableRow.append('<td>' + product.product_name + '</td>');
                    tableRow.append('<td>' + product.price + '</td>');
                    tableRow.append('<td>' + product.stock + '</td>');
                    tableRow.append('<td>' + product.company.company_name + '</td>');
                    productList.append(tableRow);
                });
            },
            error: function(xhr, status, error) {
                // エラーハンドリングの処理
                alert('エラーが発生しました');
            }
        });
    }

    // 初期表示時に商品一覧を表示
    displayProductList();


    // 検索フォームの送信イベント
    $('#search-form').submit(function(event) {
        event.preventDefault();

        // フォームのデータを取得してサーバーに送信
        let formData = $(this).serialize();

        //データが取得できているか確認
        console.log(formData);
        let encodedString = '%E3%81%A8%E3%82%8A'; //とり
        let decodedString = decodeURIComponent(encodedString);
        console.log(decodedString);

        $.ajax({
            url: searchUrl,
            type: 'GET',
            data: formData,
            success: function(response) {
                // 取得したデータを元に商品一覧を更新する処理を実行
                updateProductList(response);
              },
              error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(status);
                console.log(error);
                // エラーハンドリングの処理
                alert("データ送信エラー", error);
              }
        });
    });



    function updateProductList(products) {
        let productList = $('#product-list');
        productList.empty();

        // テーブルヘッダーを作成
        let tableHeader = $('<tr><th>ID</th><th>商品画像</th><th>商品名</th><th>価格</th><th>在庫数</th><th>メーカー名</th></tr>');
        productList.append(tableHeader);

        // 商品データをテーブルに追加
        products.forEach(function(product) {
            let tableRow = $('<tr></tr>');
            tableRow.append('<td>' + product.id + '</td>');
            tableRow.append('<td><img src="' + product.img_path + '" class="img-fluid col-6"></td>');
            tableRow.append('<td>' + product.product_name + '</td>');
            tableRow.append('<td>' + product.price + '</td>');
            tableRow.append('<td>' + product.stock + '</td>');
            tableRow.append('<td>' + product.company_name + '</td>');
            productList.append(tableRow);
        });
    }




    // 商品詳細ページへのリンクボタンがクリックされたときの処理
    $('.btn-show-details').click(function() {
        let productId = $(this).data('product-id');
        let url = "{{ route('detail', ':id') }}".replace(':id', productId);
        window.location.href = url;
    });




    // 商品削除ボタンのクリックイベント処理
    $('#product-list').on('click', '.btn-danger', function(event) {
        event.preventDefault();
        let productId = $(this).data('product-id');

        // 商品削除リクエスト送信
        $.ajax({
            url: '/products/' + productId,
            type: 'DELETE',
            dataType: 'json',
            success: function(data) {
                // 削除が成功したとき
                if (data.success) {
                    // 該当のIDの行を非表示にする
                    $('tr[data-product-id="' + productId + '"]').hide();
                } else {
                    // 失敗したとき
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                // エラーハンドリング
                alert('エラーが発生しました');
            },
        });
    });

     // ソート機能の追加
    $('#product-list').on('click', '.sortable', function() {
        let column = $(this).data('column');
        let currentSort = $(this).data('sort');

        // 昇順 ⇄ 降順 の切り替え
        if (currentSort === 'asc') {
            $(this).data('sort', 'desc');
        } else {
            $(this).data('sort', 'asc');
        }

        // 商品一覧を非同期で取得
        $.ajax({
            url: "{{ route('sort') }}",
            type: "GET",
            dataType: "json",
            data: {
                column: column,
                sort: currentSort
            },
            success: function(response) {
                // テーブルをクリア
                $('#product-list table').empty();

                // テーブルヘッダーを作成
                let tableHeader = $('<tr><th class="sortable" data-column="id" data-sort="desc">ID</th><th class="sortable" data-column="img_path" data-sort="asc">商品画像</th><th class="sortable" data-column="product_name" data-sort="asc">商品名</th><th class="sortable" data-column="price" data-sort="asc">価格</th><th class="sortable" data-column="stock" data-sort="asc">在庫数</th><th class="sortable" data-column="company_name" data-sort="asc">メーカー名</th></tr>');
                $('#product-list table').append(tableHeader);

                // 商品データをテーブルに追加
                response.forEach(function(product) {
                    var tableRow = $('<tr></tr>');
                    tableRow.append('<td>' + product.id + '</td>');
                    tableRow.append('<td><img src="http://localhost:8888/machine/public/storage/' + product.img_path + '" class="img-fluid col-6"></td>');
                    tableRow.append('<td>' + product.product_name + '</td>');
                    tableRow.append('<td>' + product.price + '</td>');
                    tableRow.append('<td>' + product.stock + '</td>');
                    tableRow.append('<td>' + product.company.company_name + '</td>');
                    $('#product-list table').append(tableRow);
                });
            },
            error: function(xhr, status, error) {
                // エラーハンドリングの処理
                alert('エラーが発生しました');
            }
        });
    });
});

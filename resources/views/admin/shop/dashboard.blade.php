<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショップ管理者ダッシュボード</title>
</head>
<body>
    <h1>ショップ管理者ダッシュボード</h1>
    <style>
    .tab-button { display: inline-block; cursor: pointer; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    </style>

    

       @if (session('success'))
        <div style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 10px; border-radius: 4px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
        @endif


        @if (session('error'))
        <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 10px; border-radius: 4px; font-weight: bold;">
            ⚠️ {{ session('error') }}
        </div>
        @endif

      <button class="tab-button.active" onclick="openTab(event, 'bids-product')">入札情報</button>
      <button class="tab-button.active" onclick="openTab(event, 'product')">商品情報</button>
      <button class="tab-button" onclick="openTab(event, 'transaction-detail')">取引明細</button>
     
       <div id="bids-product" class="tab-content">
            <h2>入札情報</h2>
            <div>
                <label>検索</label>
                <input type="text" name="bidder_id">
                <input type="button" value="検索">
            </div>

            <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc">
                <span style="width: 150px;">名前</span>
                <span style="width: 150px;">買い手ID</span>
                <span style="width: 150px;">商品名</span>
                <span style="width: 150px;">商品ID</span>
                <span style="width: 150px;">商品コメント</span>
                <span style="width: 150px;">入札金額</span>
            </div>

            @foreach ($bids as $bid)
            <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
                <span style="width: 150px;">{{ $bid->yic_users->name }}</span>
                <span style="width: 150px;">{{ $bid->yic_users->user_id }}</span>
                <span style="width: 150px;">{{ $bid->products->product_name }}</span>
                <span style="width: 150px;">{{ $bid->product_id }}</span>
                <span style="width: 150px;">{{ $bid->products->comment }}</span>
                <span style="width: 150px;"> {{ $bid->bid_amount}}円</span>
            </div>
            @endforeach
       </div>

       
       <div id="product" class="tab-content">
        <h2>商品情報</h2>
        <div>
            <label>検索</label>
            <input type="text" name="product_id">
            <input type="button" value="検索">
        </div>

        <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc">
            <span style="width: 150px;">名前</span>
            <span style="width: 150px;">出品者ID</span>
            <span style="width: 150px;">商品名</span>
            <span style="width: 150px;">商品ID</span>
            <span style="width: 150px;">商品コメント</span>
            <span style="width: 150px;">落札希望価格</span>
            <span style="width: 150px;">落札期限</span>
       </div>

       @foreach ($products as $product)
       <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
       <span style="width: 150px;">{{ $product->yic_users->name }}</span>
       <span style="width: 150px;">{{ $product->yic_users->user_id }}</span>
       <span style="width: 150px;">{{ $product->product_name }}</span>
       <span style="width: 150px;">{{ $product->product_id }}</span>
       <span style="width: 150px;">{{ $product->comment }}</span>
       <span style="width: 150px;">{{ $product->wish_price }}円</span>
       <span style="width: 150px;">{{ $product->end_date }}</span>
      <a href="{{ route('admin.shop.edit', ['product_id' => $product->product_id]) }}">
       <button>修正</button>
      </a>

      <form action="{{ route('admin.shop.destroy',  ['product_id' =>$product->product_id]) }}" method="POST">
       @csrf
       @method('DELETE')
      <input type="submit" value="削除">
      </form>
       </div>
       @endforeach
       </div>
       
       <script>
        function openTab(evt, tabName) {
            let contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            // 選択されたコンテンツのみ表示する
            document.getElementById(tabName).style.display = "block";
        }

        // ページ読み込み時に、指定されたタブを自動で開く
        window.onload = function() {
            // セッション('tab')、または URLのクエリパラメータ(?tab=...) からタブ名を取得。
            // どちらもない場合はデフォルトで 'admin.shop' を開く。
            let activeTab = "{{ session('tab', request('tab', 'bids-product')) }}";
            
            // タブを開く処理を実行
            openTab(null, activeTab);
        }
        </script>
</body>
</html>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品者ダッシュボード</title>
    <meta http-equiv="refresh" content="60">
    <style>
        .tab-button { display: inline-block; cursor: pointer; padding: 10px; background: #eee; border: 1px solid #ccc; }
        .tab-content { display: none; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    
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

    <div>
        <button class="tab-button" onclick="openTab(event, 'product-history')">商品登録</button>
        <button class="tab-button" onclick="openTab(event, 'view')">閲覧</button>
        <button class="tab-button" onclick="openTab(event, 'history')">履歴</button>
        <button class="tab-button" onclick="openTab(event, 'notification')">通知</button>
         <div style="margin-top: 20px;">
        <a href="{{ route('user.logout') }}">ログアウト</a>
</div>
</div>

    <div id="product-history" class="tab-content">
        <h2>商品登録</h2>
        @if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form action="{{ route('user.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label>カテゴリ</label>
                <select name="category_id">
                    <option value="">選択してください</option>
                    <option value="1">家電製品</option>
                    <option value="2">衣類</option>
                    <option value="3">書籍類</option>
                    <option value="4">スポーツ用品</option>
                    <option value="5">美容関連</option>
                    <option value="6">その他</option>
                </select>
            </div>
            <div>
                <label>商品名</label>
                <input type="text" name="product_name">
            </div>
            <div>
                <label>商品説明</label>
                <textarea name="comment"></textarea>
            </div>
            <div>
                <label for="image_path">写真</label>
                <input type="file" id="image_path" name="image_path" accept="image/*">
            </div>
            <div>
                <label>落札希望価格</label>
                <input type="number" name="wish_price" min="1">円
            </div>
            <div>
                <label>落札期限</label>
                <input type="datetime-local" name="end_date">
            </div>
            <button type="submit">登録</button>
        </form>
    </div>

    <div id="view" class="tab-content">
        <h2>他人の出品商品（閲覧）</h2>
        
        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
            @forelse ($other_products as $item)
                <div style="width: 200px; border: 1px solid #ddd; border-radius: 5px; padding: 10px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <a href="{{ route('user.show', $item->product_id) }}" style="display: block; text-decoration: none;">
                    <div style="text-align: center; height: 150px; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9; margin-bottom: 10px;">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                        @else
                            <span style="color: #999;">画像なし</span>
                        @endif
                    </div>
                    
                    <div style="font-size: 14px;">
                        <div style="font-weight: bold; margin-bottom: 5px; font-size: 16px;">
                            {{ $item->product_name }}
                        </div>
                        <div style="color: #e60000; font-weight: bold; margin-bottom: 5px;">
                            {{ number_format($item->wish_price) }} 円
                        </div>
                        <div style="color: #666; font-size: 12px; margin-bottom: 5px;">
                            出品者: {{ $item->yic_users->name }}
                        </div>
                        <div style="color: #666; font-size: 12px; margin-bottom: 15px;">
                            期限: {{ \Carbon\Carbon::parse($item->end_date)->format('Y/m/d H:i') }}
                        </div>
                        
                        <div style="text-align: center;">
                            <a href="{{ route('user.show', $item->product_id) }}" style="display: block; padding: 8px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px;">詳細を見る</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>現在、他の人が出品している商品はありません。</p>
            @endforelse
        </div>
    </div>

    <div id="history" class="tab-content">
        <h2>出品した商品確認</h2>
        <form action="{{ route('user.dashboard') }}" method="GET">
            <div>
                <label>検索:</label>
                <input type="text" name="name">
                <input type="submit" value="検索">
            </div>
        </form>
        
        <div style="margin-top: 20px;">
            <div style="display: flex; font-weight: bold; border-bottom: 2px solid #000;">
                <span style="width: 150px;">商品名</span>
                <span style="width: 150px;">画像</span>
                <span style="width: 150px;">商品コメント</span>
                <span style="width: 150px;">希望価格</span>
                <span style="width: 200px;">落札期限</span>
                <span style="width: 150px;">操作</span>
                
            </div>
            @foreach ($products as $product)
            <div style="display: flex; border-bottom: 1px solid #ccc;">
                <span style="width: 150px; word-break: break-all; padding-right: 10px;">
                <a href="{{ route('user.show', $product->product_id) }}" style="color: blue; text-decoration: underline;"> 
                   {{ $product->product_name }}
                </a>
                </span>
                
                <span style="width: 150px;">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="商品画像" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                    @else
                        <span>画像なし</span>
                    @endif
                </span>
                <span style="width: 150px;">{{ $product->comment }}</span>
                <span style="width: 150px;">{{ $product->wish_price }}</span>
                <span style="width: 150px;">{{ $product->end_date }}</span>
                <span style="width: 150px; display: flex; gap: 10px;">
                <a href="{{ route('user.edit', $product->product_id) }}" style="padding: 5px 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 3px;">修正</a>
                <form action="{{ route('user.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 5px 10px; background-color: #f44336; color: white; border: none; border-radius: 3px; cursor: pointer;">削除</button>
                </form>
                </span>
            </div>
            @endforeach
        </div>
    </div>

       <div id="notification" class="tab-content">
    <h2>通知一覧</h2>
    @forelse ($sold_transactions as $transaction)
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            商品名: <strong>{{ $transaction->products->product_name ?? '不明な商品' }}</strong><br>
            落札金額: {{ number_format($transaction->winnig_price) }} 円<br>
            落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}

            <span style="width: 150px;">
                @if($transaction->status == 2)
                <div>
                    <a href="{{ route('user.notification.show',$transaction->transaction_id) }}" style="display:inline-block; padding: 10px 20px; background: green; color: white; font-weight: bold; text-decoration: none; border-radius: 4px;">
                        発送依頼商品について
                    </a>
                </div>
                @elseif($transaction->status == 3)
                <div>
                既に発送依頼は完了しています
                </div>
                @elseif($transaction-> status == 5)
                <div>
                    {{ number_format($transaction->payout_amount) }} 円が振り込まれました
                </div>
                @else
                <div>
                    まだ未発送です
                </div>
                @endif
            </span>
        </div>
    @empty
        <p>現在、落札した商品はありません。</p>
    @endforelse
</div>

<script>
    function openTab(evt, tabName) {
        let contents = document.getElementsByClassName("tab-content");
        for (let i = 0; i < contents.length; i++) {
            contents[i].style.display = "none";
        }
        let target = document.getElementById(tabName);
        if (target) {
            target.style.display = "block";
        }
    }

    // 即時実行の初期化処理
    (function() {
        let activeTab = "{{ session('tab', request('tab', 'product-history')) }}";
        let target = document.getElementById(activeTab);
        if (target) {
            target.style.display = "block";
        } else {
            document.getElementById('product-history').style.display = "block";
        }
    })();
</script>
</body>
</html>
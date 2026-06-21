<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta http-equiv="refresh" content="60">
    <style>
        .tab-button { display: inline-block; cursor: pointer; padding: 10px; background: #eee; border: 1px solid #ccc; }
        .tab-content { display: inline-block; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    @if (session('error'))
        <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 10px; border-radius: 4px; font-weight: bold;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 10px; border-radius: 4px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif
        <div>
        <button class="tab-button" onclick="openTab(event, 'product-show')">商品閲覧</button>
        <button class="tab-button" onclick="openTab(event, 'history')">履歴</button>
        <button class="tab-button" onclick="openTab(event, 'notification')">通知</button>
         <div style="margin-top: 20px;">
        <a href="{{ route('user.logout') }}">ログアウト</a>
    </div>

      <div id="product-show" class="tab-content">
        <h2>商品閲覧</h2>
          <input type="hidden" name="tab" value="order">
                    <label>検索:</label>
            <input type="text" name="name" value="{{ request('name') }}">
            <input type="submit" value="検索">
            
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
                            <a href="{{ route('buyer.show', $item->product_id) }}" style="display: block; padding: 8px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px;">詳細を見る</a>
                        </div>

                         <div style="text-align: center;">
                            <a href="{{ route('buyer.bids', ['product_id' => $item->product_id]) }}" style="display: block; padding: 8px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px;">入札する</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>現在、他の人が出品している商品はありません。</p>
            @endforelse
        </div>
    </div>


   <div id="history" class="tab-content">
        <h2>あなたの入札履歴</h2>

        <div style="margin-top: 20px;">
            <div style="display: flex; font-weight: bold; border-bottom: 2px solid #000; padding-bottom: 10px; background-color: #f9f9f9;">
                <span style="width: 150px;">商品名</span>
                <span style="width: 150px;">名前</span>
                <span style="width: 150px;">住所</span>
                <span style="width: 150px;">入札金額</span>
                <span style="width: 180px;">入札日時</span>
            </div>

            @forelse ($my_bids as $bid)
            <div style="display: flex; border-bottom: 1px solid #ccc; padding: 10px 0; align-items: center;">
                
                <span style="width: 150px; padding-right: 10px; word-break: break-all;">
                    {{ $bid->products->product_name  }}
                </span>
                
                <span style="width: 150px;">{{ auth()->user()->name }}</span>
                <span style="width: 150px;">{{ auth()->user()->address }}</span>
                
                <span style="width: 150px; color: #e60000; font-weight: bold;">
                    {{ number_format($bid->bid_amount) }} 円
                </span>
                
                <span style="width: 180px; color: #666;">
                    {{ \Carbon\Carbon::parse($bid->bid_at)->format('Y/m/d H:i') }}
                </span>
                
            </div>
            @empty
            <div style="padding: 20px 0; color: #666; text-align: center;">
                まだ入札した履歴はありません。
            </div>
            @endforelse
        </div>
    </div>

    <div id="notification" class="tab-content">
        @forelse ($won_transactions as $transaction)
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        商品名: <strong>{{ $transaction->products->product_name  }}</strong><br>
        落札金額: {{ number_format($transaction->winnig_price) }} 円<br>
        落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}
       @if($transaction->status == 1)
        
            <a href="{{ route('buyer.deposit.show', $transaction->transaction_id) }}" style="display:inline-block; padding: 10px 20px; background: green; color: white; font-weight: bold; text-decoration: none; border-radius: 4px;">
                入金画面へ進む
            </a>
        @elseif($transaction->status == 3)
    <div style="padding: 10px; background-color: #fff3cd; color: #856404; font-weight: bold; margin-bottom: 10px;">
        ⚠️ 商品の発送依頼が完了しました。
    
    <a href="{{ route('buyer.check.show', $transaction->transaction_id) }}" style="display:inline-block; padding: 10px 20px; background: #007bff; color: white; font-weight: bold; text-decoration: none; border-radius: 4px;">
        発送依頼商品について
    </a>
</div>
      
        <div style="padding: 10px; background-color: #d4edda; color: #155724; font-weight: bold; width: fit-content;">
            ✅ この取引はすでに入金処理が完了しています。
        </div>
        @endif
        </div>
        @empty
        <div style="padding: 20px 0; color: #666;">
            新しい通知はありません。
        </div>
        @endforelse
    </div>
    
    
    <script>
        function openTab(evt, tabName) {
            let contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            document.getElementById(tabName).style.display = "block";
        }
        window.onload = function() {
            let activeTab = "{{ session('tab', request('tab', 'product-show')) }}";
            openTab(null, activeTab);
        }
</script>

    
</body>
</html>
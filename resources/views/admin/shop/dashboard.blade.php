<x-shop>

    <h1>ショップ管理者ダッシュボード</h1>

   <x-flash-message />

    {{-- タブボタンエリア --}}
    <div class="tab-navigation">
        <button class="tab-button" id="btn-bids-product" onclick="openTab(event, 'bids-product')">入札情報</button>
        <button class="tab-button" id="btn-product" onclick="openTab(event, 'product')">商品情報</button>
        <button class="tab-button" id="btn-transaction-detail" onclick="openTab(event, 'transaction-detail')">取引明細</button>
        <a href="{{ route('user.logout') }}" style="margin-left: auto; color: #ef4444; font-weight: bold; text-decoration: none; padding: 10px;">
        ログアウト
    </a>
    </div>
    

    {{-- 入札情報 --}}
    <div id="bids-product" class="tab-content">
        <h2>入札情報 (買い手)</h2>
        <form action="{{ route('admin.shop.dashboard') }}" method="GET" class="search-box">
        <input type="hidden" name="tab" value="bids-product">
            <label>検索</label>
            <input type="text" name="bidder_id" value="{{ request('bidder_id') }}" placeholder="名前で検索">
            <input type="submit" value="検索">
            <a href="{{ route('admin.shop.dashboard', ['tab' => 'bids-product']) }}">クリア</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>買い手ID</th>
                    <th>商品名</th>
                    <th>商品ID</th>
                    <th>商品コメント</th>
                    <th>入札金額</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bids as $bid)
                    <tr>
                        <td>{{ $bid->yic_users->name }}</td>
                        <td>{{ $bid->yic_users->user_id }}</td>
                        <td>{{ $bid->products->product_name ?? '不明'}}</td>
                        <td>{{ $bid->product_id }}</td>
                        <td>{{ $bid->products->comment ?? 'なし'}}</td>
                        <td><strong>{{ number_format($bid->bid_amount) }}円</strong></td>
                    </tr>
             @empty
            <tr>
            <td colspan="8" style="text-align: center; padding: 20px; color: #888;">
                該当する商品情報はありません。
            </td>
        </tr>
    @endforelse
            </tbody>
        </table>
    </div>

    {{-- 商品情報 --}}
    <div id="product" class="tab-content">
    <h2>商品情報 (出品者)</h2>
    <form action="{{ route('admin.shop.dashboard') }}" method="GET" class="search-box">
    <input type="hidden" name="tab" value="product">
    <label>検索</label>
    <input type="text" name="product_name" value="{{ request('product_name') }}" placeholder="商品名で検索">
    <input type="submit" value="検索"> 
    <a href="{{ route('admin.shop.dashboard', ['tab' => 'product']) }}">クリア</a>
</form>

        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出品者ID</th>
                    <th>商品名</th>
                    <th>商品ID</th>
                    <th>商品コメント</th>
                    <th>落札希望価格</th>
                    <th>落札期限</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->yic_users->name }}</td>
                        <td>{{ $product->yic_users->user_id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->product_id }}</td>
                        <td>{{ $product->comment }}</td>
                        <td>{{ number_format($product->wish_price) }}円</td>

                        <td>{{ \Carbon\Carbon::parse($product->end_date)->format('Y年m月d日 H:i') }}</td>
                        <td>
                           @if($product->transactions->where('status', 5)->isNotEmpty())
                    {{-- ステータス5の場合は操作不可を表示 --}}
                    <span style="color: #a0aec0; font-size: 0.9em;">修正・削除不可</span>
                @else
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.shop.edit', ['product_id' => $product->product_id]) }}" class="btn-edit">修正</a>
                        <form action="{{ route('admin.shop.destroy', ['product_id' => $product->product_id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">削除</button>
                        </form>
                    </div>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="text-align: center; padding: 20px; color: #888;">
                該当する商品情報はありません。
            </td>
        </tr>
    @endforelse
            </tbody>
        </table>
    </div>

    {{-- 取引明細 --}}
    <div id="transaction-detail" class="tab-content">
        <h2>取引明細（入金通知）</h2>
        
        <table>
            <thead>
                <tr>
                    <th>取引ID</th>
                    <th>買い手ID</th>
                    <th>商品名</th>
                    <th>落札金額</th>
                    <th>ステータス / アクション</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    {{-- 入金確認済み(status == 2)の場合は背景をハイライトクラスに --}}
                    <tr class="{{ $transaction->status == 2 ? 'bg-highlight' : '' }}">
                        <td>{{ $transaction->transaction_id }}</td>
                        <td>{{ $transaction->buyer_id }}</td>
                        <td>{{ $transaction->products?->product_name ?? '不明な商品'}}</td>
                        <td><strong>{{ number_format($transaction->winnig_price) }}円</strong></td>
                        <td id="action-cell-{{ $transaction->transaction_id }}">
    @if($transaction->status == 1)
        <span class="text-muted">落札通知済み</span>
    
    @elseif($transaction->status == 2)
        @if($transaction->payment_received_at)
            {{-- 発送依頼を既に送っている場合は発送完了として表示 --}}
            <label style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 6px 14px; border-radius: 9999px; display: inline-block;">
                 発送完了
            </label>
        @else
            <span class="text-danger">入金済</span>
            <div>
                {{-- 管理者が発送依頼ページへ飛ぶためのボタン --}}
                <a href="{{ route('admin.shop.shipping.show', $transaction->transaction_id) }}" class="btn-action">
                    発送依頼へ
                </a>
            </div>
        @endif
        
    @elseif($transaction->status == 3)
        <label style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 6px 14px; border-radius: 9999px; display: inline-block;">
             発送完了
        </label>
        
    @elseif($transaction->status == 4)
        <span class="text-danger">受け取り確認済</span>
        <div>
            {{-- 管理者が振込処理ページへ飛ぶためのボタン --}}
            <a href="{{ route('admin.shop.transfer.show', $transaction->transaction_id) }}" class="btn-action">
                振り込みへ
            </a>
        </div>
        
     @elseif($transaction->status == 5)
        <span style="color: #2f855a; font-weight: bold;">出品者振込完了</span>
     @endif
    </td>
     
    </tr>
    @empty
    <tr>
            <td colspan="9" style="text-align: center; padding: 20px; color: #888;">
                該当する商品情報はありません。
            </td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>

    {{--タブ切り替え用のスクリプト --}}
    <script>
        function openTab(evt, tabName) {
            // 全てのタブコンテンツを非表示にする
            let contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            
            // 全てのタブボタンから active クラスを消す
            let buttons = document.getElementsByClassName("tab-button");
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove("active");
            }
            
            // 選択されたタブを表示し、ボタンに active クラスをつける
            document.getElementById(tabName).style.display = "block";
            if (evt) {
                evt.currentTarget.classList.add("active");
            } else {
                document.getElementById('btn-' + tabName).classList.add("active");
            }
        }

        // ページ読み込み時の自動初期化
        window.onload = function() {
            let activeTab = "{{ session('tab', request('tab', 'bids-product')) }}";
            openTab(null, activeTab);
        }
    </script>

</x-shop>

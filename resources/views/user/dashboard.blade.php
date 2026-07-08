<x-user>

    {{-- フラッシュメッセージ --}}
      <x-flash-message />

    {{-- タブボタン --}}
    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        <button class="tab-button" data-tab="product-history">商品登録</button>
        <button class="tab-button" data-tab="view">閲覧</button>
        <button class="tab-button" data-tab="history">履歴</button>
        <button class="tab-button" data-tab="notification">
            通知
            @if(isset($unread_count) && $unread_count > 0)
            <span class="notification-badge">{{ $unread_count }}</span>
            @endif
        </button>
       
            <a href="{{ route('user.logout') }}" class="logout-link">ログアウト</a>
            </div>
    

    {{-- タブ1: 商品登録 --}}
    <div id="product-history" class="tab-content">
        <h2>商品登録</h2>


        <form action="{{ route('user.product.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
            @csrf
            <div class="form-group">
                <label>カテゴリ <span class="required">※この項目は必須です</span></label>
                <select name="category_id" required>
                    <option value="">選択してください</option>
                    <option value="1">家電製品</option>
                    <option value="2">衣類</option>
                    <option value="3">書籍類</option>
                    <option value="4">スポーツ用品</option>
                    <option value="5">美容関連</option>
                    <option value="6">その他</option>
                </select>
            </div>
            <div class="form-group">
                <label>商品名 <span class="required">※この項目は必須です</span></label>
                <input type="text" name="product_name" placeholder="商品名を入力してください" required>
            </div>
            <div class="form-group">
                <label>商品説明 <span class="required">※この項目は必須です</span></label>
                <textarea name="comment" required></textarea>
            </div>
            <div class="form-group">
                <label for="image_path">写真</label>
                <input type="file" id="image_path" name="image_path" accept="image/*">
            </div>
            <div class="form-group">
                <label>落札希望価格 <span class="required">※この項目は必須です</span></label>
                <input type="number" name="wish_price" min="1" placeholder="例: 1000" required> 円
            </div>
            <div class="form-group">
                <label>落札期限 <span class="required">※この項目は必須です</span></label>
                <input type="datetime-local" name="end_date" required>
            </div>
            <button type="submit" class="submit-btn">登録</button>
        </form>
    </div>{{-- /product-history --}}


    {{-- タブ2: 閲覧 --}}
    <div id="view" class="tab-content">
        <h2>他人の出品商品(閲覧)</h2>
 
        <form action="{{ route('user.dashboard') }}" method="GET" class="search-box">
                <input type="hidden" name="tab" value="view">
                <label>検索:</label>
                <input type="text" name="name" value="{{ request('name') }}" placeholder="商品名で検索">
                <input type="submit" value="検索">
            </form>

        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
            @forelse ($other_products as $item)
                @if(\Carbon\Carbon::parse($item->end_date)->isFuture())
                    <x-product-table :item="$item" />
                @endif
            @empty
                <p>現在、他の人が出品している商品はありません。</p>
            @endforelse
        </div>
    </div>{{-- /view --}}


    {{-- タブ3: 履歴 --}}
    <div id="history" class="tab-content">

    
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; padding-left: 10px; border-left: 4px solid #3b82f6;">出品した商品確認</h2>

    
 
            
            <select onchange="filterHistory(this.value)" style="padding: 5px 10px; cursor: pointer;">
                <option value="all">すべて表示</option>
                <option value="active">出品中のみ</option>
                <option value="sold">落札された商品（出品終了）</option>
                <option value="unsold">未落札の商品（出品終了）</option>
            </select>

            <div style="display: flex; gap: 15px;">
                <div style="font-size: 0.95rem; font-weight: bold; color: #4b5563; background-color: #f3f4f6; padding: 6px 16px; border-radius: 20px; border: 1px solid #d1d5db; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    出品回数: <span style="color: #3b82f6; font-size: 1.2rem; margin: 0 4px;">{{ $listing_count ?? 0 }}</span> 回
                </div>
            </div>
        </div>

         
            <form action="{{ route('user.dashboard') }}" method="GET" class="search-box">
                <input type="hidden" name="tab" value="history">
                <label>検索:</label>
                <input type="text" name="history" value="{{ request('history') }}" placeholder="商品名で検索">
                <input type="submit" value="検索">
                 <a href="{{ route('user.dashboard', ['tab' => 'history']) }}">クリア</a>
            </form>

        <div style="margin-top: 20px;">
            <div style="display: flex; font-weight: bold; border-bottom: 2px solid #000; padding-bottom: 5px;">
                <span style="width: 150px;">商品名</span>
                <span style="width: 150px;">画像</span>
                <span style="width: 150px;">商品コメント</span>
                <span style="width: 150px;">希望価格</span>
                <span style="width: 200px;">落札期限</span>
                <span style="width: 150px;">状態</span>
                <span style="width: 150px;">操作</span>
            </div>

            @forelse ($products as $product)
                @php
                    $isPast = \Carbon\Carbon::parse($product->end_date)->isPast();
                    $rowStatus = 'active';
                    if ($product->status === '出品終了' || $isPast) {
                        if (in_array($product->product_id, $sold_product_ids)) {
                            $rowStatus = 'sold';
                        } else {
                            $rowStatus = 'unsold';
                        }
                    }
                @endphp
                <div class="product-item" data-status="{{ $rowStatus }}" style="display: flex; border-bottom: 1px solid #ccc; padding: 10px 0; align-items: center;">
                    <span style="width: 150px; word-break: break-all; padding-right: 10px;">
                        <a href="{{ route('user.show', $product->product_id) }}" style="color: blue; text-decoration: underline;">
                            {{ $product->product_name }}
                        </a>
                    </span>
                    <span style="width: 150px;">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="商品画像" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                        @else
                            <span style="color: #999;">画像なし</span>
                        @endif
                    </span>
                    <span style="width: 150px;">{{ $product->comment }}</span>
                    <span style="width: 150px;">{{ number_format($product->wish_price) }}円</span>
                    <span style="width: 200px;">{{ \Carbon\Carbon::parse($product->end_date)->format('Y-m-d H:i') }}</span>
                    <span style="width: 150px; font-weight: bold;">
                        @if($product->status === '出品終了' || $isPast)
                            @if (in_array($product->product_id, $sold_product_ids))
                                <span style="color: #e53e3e; font-weight: bold; background-color: #fed7d7; padding: 4px 10px; border-radius: 9999px; display: inline-block; font-size: 14px;">落札</span>
                            @else
                                <span style="color: #718096; font-weight: bold; background-color: #f7fafc; padding: 4px 10px; border-radius: 9999px; display: inline-block; font-size: 14px;">未落札</span>
                            @endif
                        @else
                            <span style="color: #3182ce; font-weight: bold; background-color: #ebf8ff; padding: 4px 10px; border-radius: 9999px; display: inline-block; font-size: 14px;">出品中</span>
                        @endif
                    </span>
                    <span style="width: 150px; display: flex; gap: 10px; align-items: center;">
                        @if(\Carbon\Carbon::parse($product->end_date)->isFuture())
                            <a href="{{ route('user.edit', $product->product_id) }}" style="padding: 5px 10px; background-color: #ffc107; color: black; text-decoration: none; border-radius: 3px; font-size: 14px; height: 30px; box-sizing: border-box; display: inline-flex; align-items: center;">修正</a>
                            <form action="{{ route('user.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin: 0; display: flex;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">削除</button>
                            </form>
                        @else
                            <span style="color: #6c757d; font-size: 14px;">操作不可</span>
                        @endif
                    </span>
                </div>
            @empty
                <div style="padding: 30px 0; color: #666; text-align: center; font-weight: bold;">
                    該当する商品はありません。
                </div>
            @endforelse

            <div id="history-empty-message" style="display: none; padding: 30px 0; color: #666; text-align: center; font-weight: bold;">
                該当する情報はありません。
            </div>
        </div>

    </div>


   {{-- 通知 --}}
    <div id="notification" class="tab-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">通知一覧</h2>
            <select id="notification-filter" onchange="window.renderNotifications()" style="padding: 8px 15px; border-radius: 4px; border: 1px solid #ccc; font-weight: bold; cursor: pointer;">
                <option value="all">すべての通知（未読・既読）</option>
                <option value="unread">未読のみ</option>
                <option value="read">既読のみ</option>
                <option value="deleted">削除フォルダ</option>
            </select>
        </div>

        <div id="notification-list">
            @forelse ($sold_transactions as $transaction)
               <div class="notification-card" data-txn-id="{{ $transaction->transaction_id }}" data-status="{{ $transaction->status }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <p style="font-size: 1.1rem; margin-top: 0;">
                                <span class="badge-area"></span>
                                商品名: <strong>{{ $transaction->products->product_name ?? '不明な商品' }}</strong>
                            </p>
                            <p>落札金額: <span style="color: #e60000; font-weight: bold; font-size: 1.1rem;">{{ number_format($transaction->winnig_price) }} 円</span></p>
                            <p style="color: #666; margin-bottom: 20px;">落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class="action-area" style="display: flex; gap: 10px;"></div>
                    </div>
                    <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        @if($transaction->status == 2 && $transaction->payment_received_at)
                            <a href="{{ route('user.notification.show', $transaction->transaction_id) }}" class="btn-action btn-blue">発送依頼を確認する</a>
                        @elseif($transaction->status == 3)
                            <label style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 6px 14px; border-radius: 9999px; display: inline-block;">発送完了</label>
                        @elseif($transaction->status == 5)
                            <span style="color: #007bff; font-weight: bold;">{{ number_format($transaction->payout_amount) }} 円が振り込まれました</span>
                        
                        @else
                            <span style="color: #6c757d;">現在取引進行中</span>
                        @endif
                    </div>
                </div>
            @empty
                <p>現在、通知はありません。</p>
            @endforelse
        </div>

        <div id="js-empty-message" style="display: none; padding: 30px 0; color: #666; text-align: center; font-weight: bold;">
            該当する通知はありません。
        </div>
    </div>
    <script>
    window.AppConfig = {
        userId: "{{ auth()->id() }}",
        csrfToken: "{{ csrf_token() }}",
        hideUrl: "{{ url('user/notification/hide') }}"
    };
</script>
<script src="{{ asset('js/user_notification.js') }}"></script>

</x-user>
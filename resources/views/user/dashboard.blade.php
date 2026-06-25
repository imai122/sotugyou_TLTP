<x-user>

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div class="alert-success" style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 10px; border-radius: 4px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 10px; border-radius: 4px; font-weight: bold;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- タブボタン --}}
    <div style="margin-bottom: 20px;">
        <button class="tab-button" onclick="openTab(event, 'product-history')">商品登録</button>
        <button class="tab-button" onclick="openTab(event, 'view')">閲覧</button>
        <button class="tab-button" onclick="openTab(event, 'history')">履歴</button>
        <button class="tab-button" onclick="openTab(event, 'notification')">
        通知
        @if(isset($unread_count) && $unread_count > 0)
        <span class="notification-badge">{{ $unread_count }}</span>
        @endif
</button>
        <div style="margin-top: 20px;">
            <a href="{{ route('user.logout') }}" style="color: #dc3545; font-weight: bold;">ログアウト</a>
        </div>
    </div>

    {{-- 💡 タブ1: 商品登録 --}}
    <div id="product-history" class="tab-content">
        <h2>商品登録</h2>
        
        <form action="{{ route('user.product.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
            @csrf
            <div class="form-group">
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
            <div class="form-group">
                <label>商品名</label>
                <input type="text" name="product_name" placeholder="商品名を入力してください">
            </div>
            <div class="form-group">
                <label>商品説明</label>
                <textarea name="comment"></textarea>
            </div>
            <div class="form-group">
                <label for="image_path">写真</label>
                <input type="file" id="image_path" name="image_path" accept="image/*">
            </div>
            <div class="form-group">
                <label>落札希望価格</label>
                <input type="number" name="wish_price" min="1" placeholder="例: 1000"> 円
            </div>
            <div class="form-group">
                <label>落札期限</label>
                <input type="datetime-local" name="end_date">
            </div>
            <button type="submit" class="submit-btn">登録</button>
        </form>
    </div>

    {{-- 💡 タブ2: 閲覧 --}}
    <div id="view" class="tab-content">
        <h2>他人の出品商品(閲覧)</h2>

        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
        @forelse ($other_products as $item)
        @if(\Carbon\Carbon::parse($item->end_date)->isFuture())
            <x-product-table :item="$item" />
            @endif
        @empty
            <p>現在、他の人が出品している商品はありません。</p>
        @endforelse
        </div>
    </div>

    {{-- 💡 タブ3: 履歴 --}}
    <div id="history" class="tab-content">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; padding-left: 10px; border-left: 4px solid #3b82f6;">出品した商品確認</h2>
            
        <select onchange="filterHistory(this.value)" style="padding: 5px 10px; cursor: pointer;">
        <option value="all">すべて表示</option>
        <option value="active">出品中のみ</option>
        <option value="expired">出品終了分のみ</option>
        </select>
            {{-- 右上に表示するステータスバッジ群 --}}
            <div style="display: flex; gap: 15px;">
                {{-- 出品回数 --}}
                <div style="font-size: 0.95rem; font-weight: bold; color: #4b5563; background-color: #f3f4f6; padding: 6px 16px; border-radius: 20px; border: 1px solid #d1d5db; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    出品回数: <span style="color: #3b82f6; font-size: 1.2rem; margin: 0 4px;">{{ $listing_count ?? 0 }}</span> 回
                </div>
                
            </div>
        </div>
        
        <form action="{{ route('user.dashboard') }}" method="GET">
            <div style="display: flex; align-items: center; gap: 10px;">
                <label>検索:</label>
                <input type="text" name="name" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;" placeholder="商品名で検索">
                <input type="hidden" name="tab" value="history">
                <input type="submit" value="検索" style="padding: 5px 10px; cursor: pointer;">
                @if(request('name'))
            <a href="{{ route('user.dashboard', ['tab' => 'history']) }}" style="color: #dc3545; text-decoration: none; font-size: 14px;">✖ クリア</a>
        @endif
            </div>
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
            @foreach ($products as $product)
            @php
            $isPast = \Carbon\Carbon::parse($product->end_date)->isPast();
           @endphp
          <div class="product-item" 
          data-status="{{ $isPast ? 'expired' : 'active' }}" 
           style="display: flex; border-bottom: 1px solid #ccc; padding: 10px 0; align-items: center;">
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
                @if(\Carbon\Carbon::parse($product->end_date)->isPast())
                <span style="color: #dc3545;">出品終了</span>
                @else
                <span style="color: #28a745;">出品中</span>
                @endif
                </span>
                <span style="width: 150px; display: flex; gap: 10px; align-items: center;">
                    @if(\Carbon\Carbon::parse($product->end_date)->isFuture())
                <a href="{{ route('user.edit', $product->product_id) }}"
                 style="padding: 5px 10px; background-color: #ffc107; color: black; text-decoration: none; border-radius: 3px; font-size: 14px; height: 30px; box-sizing: border-box; display: inline-flex; align-items: center;">修正</a>
                    
                    {{-- formタグに margin: 0; を追加して余白を消す --}}
                    <form action="{{ route('user.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin: 0; display: flex;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">削除</button>
                    </form>
                    @else
              {{-- 出品終了のときは「終了済み」とだけ表示する --}}
                <span style="color: #6c757d; font-size: 14px;">操作不可</span>
                @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>



    {{--通知 --}}
    <div id="notification" class="tab-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">通知一覧</h2>
         {{-- 💡 振り分け用コンボボックス --}}
                <select id="notification-filter" onchange="applyFilter()" style="padding: 8px 15px; border-radius: 4px; border: 1px solid #ccc; font-weight: bold; cursor: pointer;">
                <option value="all">すべての通知（未読・既読）</option>
                <option value="unread">未読のみ</option>
                <option value="read">既読のみ</option>
                <option value="deleted">削除フォルダ</option>
                </select>
            </div>

            <div id="notification-list">
            @forelse ($sold_transactions as $transaction)
            <div class="notification-card" data-txn-id="{{ $transaction->transaction_id }}">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="font-size: 1.1rem; margin-top: 0;">
                <span class="badge-area"></span>
                商品名: <strong>{{ $transaction->products->product_name ?? '不明な商品' }}</strong><br>
                </p>
                <p>落札金額: <span style="color: #e60000; font-weight: bold; font-size: 1.1rem;">{{ number_format($transaction->winnig_price) }} 円</span></p>
                <p style="color: #666; margin-bottom: 20px;">落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p><br>
            </div>

            {{-- 既読か削除かのボタン --}}
            <div class="action-area" style="display: flex; gap: 10px;"></div>
            </div>
 
             {{-- 案内メッセージ --}}
                    <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    @if($transaction->status == 2)
                        <a href="{{ route('user.notification.show',$transaction->transaction_id) }}" style="display:inline-block; padding: 10px 20px; background: #28a745; color: white; font-weight: bold; text-decoration: none; border-radius: 4px;">
                            発送依頼商品について
                        </a>
                    @elseif($transaction->status == 3)
                        <span style="color: #6c757d;">既に発送依頼は完了しています</span>
                    @elseif($transaction-> status == 5)
                        <span style="color: #007bff; font-weight: bold;">{{ number_format($transaction->payout_amount) }} 円が振り込まれました</span>
                    @else
                        <span style="color: #6c757d;">まだ未発送です</span>
                    @endif
                </div>
            </div>
        @empty
            <p>現在、落札した商品はありません。</p>
        @endforelse
    </div>

    {{-- JavaScript --}}
    <script>
        function openTab(evt, tabName) {
            let contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            let buttons = document.getElementsByClassName("tab-button");
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove("active");
            }

            let target = document.getElementById(tabName);
            if (target) {
                target.style.display = "block";
            }
            if(evt) {
                evt.currentTarget.classList.add("active");
            }
        }

        // 即時実行の初期化処理
        (function() {
            let activeTab = "{{ session('tab', request('tab', 'product-history')) }}";
            let target = document.getElementById(activeTab);
            if (target) {
                target.style.display = "block";
                // ボタンのハイライト
                let buttons = document.getElementsByClassName("tab-button");
                for (let btn of buttons) {
                    if (btn.getAttribute('onclick').includes(activeTab)) {
                        btn.classList.add("active");
                    }
                }
            } else {
                document.getElementById('product-history').style.display = "block";
            }
        })();

        function filterHistory(status) {
    let items = document.getElementsByClassName("product-item");
    for (let item of items) {
        if (status === 'all') {
            item.style.display = "flex";
        } else if (status === 'active') {
            item.style.display = (item.getAttribute('data-status') === 'active') ? "flex" : "none";
        } else if (status === 'expired') {
            item.style.display = (item.getAttribute('data-status') === 'expired') ? "flex" : "none";
        }

    }
}



        const storageKey = "notifications_user_{{ auth()->id() }}";

        // ブラウザの記憶を取得する
        function getNotificationStates() {
            return JSON.parse(localStorage.getItem(storageKey)) || {};//取得する際に何もなかったら空にする
        }

        // ブラウザに状態を記憶させる
        function saveNotificationStates(states) {//既読や未読といった状態を取得(states)
            localStorage.setItem(storageKey, JSON.stringify(states));//文字として保存する
        }

        // 「既読」「削除」ボタンを押した時の処理
        function markNotification(txnId, state) {
            if (state === 'deleted' && !confirm('削除フォルダに移動しますか？')) return;

            let states = getNotificationStates();
            states[txnId] = state; // 'read' か 'deleted' を保存
            saveNotificationStates(states);

            renderNotifications(); // 画面を更新
        }

        // 画面の表示を最新の状態に切り替える
        function renderNotifications() {
            let states = getNotificationStates();
            let filter = document.getElementById('notification-filter').value;//combobox表示
            let cards = document.getElementsByClassName('notification-card');

            for (let card of cards) {
                let txnId = card.getAttribute('data-txn-id');
                let currentState = states[txnId] || 'unread'; // 記憶がないものは「未読(unread)」

                let badgeArea = card.querySelector('.badge-area');
                let actionArea = card.querySelector('.action-area');

                // 1. バッジとボタンの書き換え
                if (currentState === 'deleted') {
                    badgeArea.innerHTML = '<span style="color: #999; font-weight: bold; margin-right: 5px;">🗑️ 削除済み</span>';
                    actionArea.innerHTML = ''; // 削除済みの場合はボタンを消す
                } else if (currentState === 'read') {
                    badgeArea.innerHTML = '<span style="background-color: #10b981; color: white; font-size: 12px; padding: 3px 8px; border-radius: 10px; margin-right: 5px;">既読</span>';
                    actionArea.innerHTML = `<button onclick="markNotification('${txnId}', 'deleted')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">削除</button>`;
                } else { // unread (未読)
                    badgeArea.innerHTML = '<span style="background-color: #ef4444; color: white; font-size: 12px; padding: 3px 8px; border-radius: 10px; margin-right: 5px;">未読</span>';
                    actionArea.innerHTML = `
                        <button onclick="markNotification('${txnId}', 'read')" style="padding: 6px 12px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">既読にする</button>
                        <button onclick="markNotification('${txnId}', 'deleted')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">削除</button>
                    `;
                }

                // 2. コンボボックスの選択に合わせて 表示/非表示 を切り替え
                if (filter === 'all') {
                    card.style.display = (currentState !== 'deleted') ? "block" : "none";
                } else if (filter === 'unread') {
                    card.style.display = (currentState === 'unread') ? "block" : "none";
                } else if (filter === 'read') {
                    card.style.display = (currentState === 'read') ? "block" : "none";
                } else if (filter === 'deleted') {
                    card.style.display = (currentState === 'deleted') ? "block" : "none";
                }
            }
        }

        // コンボボックスを変更した時に呼ばれる
        function applyFilter() {
            renderNotifications();
        }

        // ページが読み込まれた時に一度だけ実行して画面を整える
        window.addEventListener('DOMContentLoaded', () => {
            renderNotifications();
        });

    </script>

</x-user>
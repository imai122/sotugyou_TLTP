<x-buyer>
<div class="buyer-container">
        {{-- フラッシュメッセージ --}}
        @if (session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        
        {{-- タブナビゲーション --}}
        <div class="tab-nav">
            <button class="tab-button" onclick="openTab(event, 'product-show')">商品閲覧</button>
            <button class="tab-button" onclick="openTab(event, 'history')">履歴</button>
            <button class="tab-button" onclick="openTab(event, 'notification')">通知</button>
            <a href="{{ route('user.logout') }}">ログアウト</a>
        </div>

        {{-- 💡 タブ1: 商品閲覧 --}}
        <div id="product-show" class="tab-content">
            <h2>商品閲覧</h2>
            
            <form action="" method="GET" class="search-box">
                <input type="hidden" name="tab" value="product-show">
                <label>検索:</label>
                <input type="text" name="name" value="{{ request('name') }}" placeholder="商品名で検索">
                <input type="submit" value="検索">
            </form>
            
            <div class="product-grid">
                @forelse ($other_products as $item)
                    @if(\Carbon\Carbon::parse($item->end_date)->isFuture())
                        <div class="product-card">
                            <x-product-table :item="$item" />
                            <a href="{{ route('buyer.bids', $item->product_id) }}" class="btn-bid">入札する</a>
                        </div>
                    @endif
                @empty
                    <p style="color: #666; width: 100%; text-align: center; padding: 30px;">現在、出品されている商品はありません。</p>
                @endforelse
            </div>
        </div>

        {{-- 💡 タブ2: 履歴 --}}
        <div id="history" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">あなたの入札履歴</h2>
                <div class="badge-count">
                    購入回数: <span>{{ $purchase_count ?? 0 }}</span> 回
                </div>
            </div>

            <div>
                <div class="list-header">
                    <span class="col-150">商品名</span>
                    <span class="col-150">名前</span>
                    <span class="col-150">住所</span>
                    <span class="col-150">入札金額</span>
                    <span class="col-180">入札日時</span>
                </div>

                @forelse ($my_bids as $bid)
                    <div class="list-row">
                        <span class="col-150">{{ $bid->products->product_name }}</span>
                        <span class="col-150">{{ auth()->user()->name }}</span>
                        <span class="col-150">{{ auth()->user()->address }}</span>
                        <span class="col-150" style="color: #e60000; font-weight: bold;">{{ number_format($bid->bid_amount) }} 円</span>
                        <span class="col-180" style="color: #666;">{{ \Carbon\Carbon::parse($bid->bid_at)->format('Y/m/d H:i') }}</span>
                    </div>
                @empty
                    <div style="padding: 30px 0; color: #666; text-align: center;">まだ入札した履歴はありません。</div>
                @endforelse
            </div>
        </div>

        {{-- 通知 --}}
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
            @forelse ($won_transactions as $transaction)
                <div class="notification-card" data-txn-id="{{ $transaction->transaction_id }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                   <p style="font-size: 1.1rem; margin-top: 0;">
                    {{-- ここにJSで 未読/既読/削除済み のバッジが入ります --}}
                    <span class="badge-area"></span>
                     商品名: <strong>{{ $transaction->products->product_name }}</strong>
                    </p>
                    <p>落札金額: <span style="color: #e60000; font-weight: bold; font-size: 1.1rem;">{{ number_format($transaction->winnig_price) }} 円</span></p>
                    <p style="color: #666; margin-bottom: 20px;">落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p>
                    </div>
                            
                    {{-- ここにJSで「既読にする」「削除」ボタンが入ります --}}
                    <div class="action-area" style="display: flex; gap: 10px;"></div>
                    </div>
                        
                    {{-- 案内メッセージ --}}
                    <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    @if($transaction->status == 1)
                        <a href="{{ route('buyer.deposit.show', $transaction->transaction_id) }}" class="btn-action btn-green">
                            入金画面へ進む
                        </a>
                    @elseif($transaction->status == 3)
                        <div class="status-msg-warning">
                            ⚠️ 商品の発送依頼が完了しました。
                        </div>
                        <a href="{{ route('buyer.check.show', $transaction->transaction_id) }}" class="btn-action btn-blue" style="margin-bottom: 15px;">
                            発送依頼商品について
                        </a>
                        <br>
                        <div class="status-msg-success">
                            ✅ この取引はすでに入金処理が完了しています。
                        </div>
                    @endif
                </div>
            @empty
                <div style="padding: 30px 0; color: #666; text-align: center;">新しい通知はありません。</div>
            @endforelse
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function openTab(evt, tabName) {
            // すべてのコンテンツを隠す
            let contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            
            // すべてのボタンの active クラスを外す
            let buttons = document.getElementsByClassName("tab-button");
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove("active");
            }
            
            // 対象のタブを表示
            document.getElementById(tabName).style.display = "block";
            
            // クリックされたボタンを active にする
            if(evt) {
                evt.currentTarget.classList.add("active");
            }
        }

        // ページ読み込み時の処理
        window.onload = function() {
            let activeTab = "{{ session('tab', request('tab', 'product-show')) }}";
            let targetButton = document.querySelector(`.tab-button[onclick*="${activeTab}"]`);
            
            // 対象のタブを開く（イベントがない場合は疑似的に渡す）
            openTab({ currentTarget: targetButton || document.querySelector('.tab-button') }, activeTab);
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
</x-buyer>
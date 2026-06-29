<x-buyer>
<div class="buyer-container">
        {{-- フラッシュメッセージ --}}
        @if (session('error'))
            <div class="alert alert-error"> {{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success"> {{ session('success') }}</div>
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

        {{--タブ2: 履歴 --}}
        <div id="history" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">あなたの入札履歴</h2>

                <select onchange="filterHistory(this.value)" style="padding: 5px 10px; cursor: pointer;">
                    <option value="all">全て表示</option>
                    <option value="active">出品中のみ</option>
                    <option value="sold">落札された商品のみ</option>
                    <option value="unsold">未落札の商品</option>
                </select>
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

                    <span class="col-150">落札状況</span>
                </div>

                @forelse ($my_bids as $bid)
                 @php
           $isPast = \Carbon\Carbon::parse($bid->products->end_date)->isPast();
           $rowStatus = 'active'; // デフォルトは出品中
           if ($bid->products->status === '出品終了' || $isPast) {
            if (in_array($bid->products->product_id, $won_product_ids)) {
                $rowStatus = 'sold';   // 落札
            } else {
                $rowStatus = 'unsold'; // 未落札
            }
        }
        @endphp
                   <div class="list-row product-item" data-status="{{ $rowStatus }}">
                        <span class="col-150">{{ $bid->products->product_name }}</span>
                        <span class="col-150">{{ auth()->user()->name }}</span>
                        <span class="col-150">{{ auth()->user()->address }}</span>
                        <span class="col-150" style="color: #e60000; font-weight: bold;">{{ number_format($bid->bid_amount) }} 円</span>
                        <span class="col-180" style="color: #666;">{{ \Carbon\Carbon::parse($bid->bid_at)->format('Y/m/d H:i') }}</span>
                        <!-- <span class="col-150">{{ $bid->products->status }}</span> -->

                    @if ($bid->products->status === '出品終了')
                    @if (in_array($bid->product_id, $won_product_ids))
                    <span style="color: #e53e3e; font-weight: bold; background-color: #fed7d7; padding: 4px 10px; border-radius: 9999px; display: inline-block;">
                    落札
                    </span>
                    @else
                    <span style="color: #e53e3e; font-weight: bold; background-color: #fed7d7; padding: 4px 10px; border-radius: 9999px; display: inline-block;">
                    未落札
                    </span>
                    @endif
                    @else
                    <span style="color: #3182ce; font-weight: bold; background-color: #ebf8ff; padding: 4px 10px; border-radius: 9999px; display: inline-block;">
                    出品中
                    </span>
                    @endif
                    </span>
                    </div>
                @empty
                    <div style="padding: 30px 0; color: #666; text-align: center;">まだ入札した履歴はありません。</div>
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
            {{-- 💡 振り分け用コンボボックス --}}
                <select id="notification-filter" onchange="applyFilter()" style="padding: 8px 15px; border-radius: 4px; border: 1px solid #ccc; font-weight: bold; cursor: pointer;">
                <option value="all">すべての通知（未読・既読）</option>
                <option value="unread">未読のみ</option>
                <option value="read">既読のみ</option>
                <option value="deleted">削除フォルダ</option>
                </select>
            </div>

            <div id="notification-list">
                <div id="js-empty-message" style="display: none; padding: 30px 0; color: #666; text-align: center;">
                    該当する通知はありません。
                </div>
            @forelse ($won_transactions as $transaction)
                <div class="notification-card" data-txn-id="{{ $transaction->transaction_id }}" data-status="{{ $transaction->status }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                   <p style="font-size: 1.1rem; margin-top: 0;">
                    <!-- <span class="badge-area"></span> -->
                    <span>aaa</span> <span class="badge-area"></span>
                     商品名: <strong>{{ $transaction->products->product_name }}</strong>
                    </p>
                    <p>落札金額: <span style="color: #e60000; font-weight: bold; font-size: 1.1rem;">{{ number_format($transaction->winnig_price) }} 円</span></p>
                    <p style="color: #666; margin-bottom: 20px;">落札日時: {{ \Carbon\Carbon::parse($transaction->won_at)->format('Y/m/d H:i') }}</p>
                    </div>
                            
                    <div class="action-area" style="display: flex; gap: 10px;"></div>
                    </div>
                        
                    {{-- 案内メッセージ --}}
                       @if($transaction->status == 1)
                    <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                 
                        <a href="{{ route('buyer.deposit.show', $transaction->transaction_id) }}" class="btn-action btn-green">
                            入金画面へ進む
                        </a>
                    </div>

                        @elseif($transaction->status == 2)
                       {{-- 入金が完了している場合（status 2, 3, 5などはすべてここを通る） --}}
                      <!-- <div class="status-msg-success" style="color: #155724; font-weight: bold; margin-bottom: 10px;"> -->
                         <div style="background-color: #d4edda; padding: 10px; border-radius: 6px; border: 1px solid #c3e6cb;">
                        <div style="color: #155724; font-weight: bold; margin-bottom: 10px; font-size: 0.95rem;">
                         入金が完了しました！管理者に通知済みです。発送されるまで少々お待ちください。
                         </div>
                         </div>
                         
                    @elseif($transaction->status == 3)
                        <div style="background-color: #d4edda; padding: 10px; border-radius: 6px; border: 1px solid #c3e6cb;">
                        <div style="color: #155724; font-weight: bold; margin-bottom: 10px; font-size: 0.95rem;">
            
                             商品の発送依頼が完了しました。
                        </div>
                        <a href="{{ route('buyer.check.show', $transaction->transaction_id) }}" class="btn-action btn-blue" style="margin-bottom: 15px;">
                            発送依頼商品について
                        </a>
                        </div>
                        
                        @elseif($transaction->status == 5)
                        <div style="background-color: #d4edda; padding: 10px; border-radius: 6px; border: 1px solid #c3e6cb;">
                        <div style="color: #155724; font-weight: bold; margin-bottom: 10px; font-size: 0.95rem;">
                        取引終了しました。落札おめでとうございます。
                         </div>
                        
                         
                         
                        <!-- <br> -->
                        <!-- <div style="color: #155724;">
                            ✅ この取引はすでに入金処理が完了しています。
                        </div> -->
                        </div>
                    </div>
                        @endif
                    
                    </div>
                
                 
                  @empty
                <div style="padding: 30px 0; color: #666; text-align: center;">新しい通知はありません。</div>
            @endforelse

            <div id="js-empty-message" style="display: none; padding: 30px 0; color: #666; text-align: center; font-weight: bold;">
            該当する通知はありません。
        </div>
            </div>
        </div>
</div> {{-- /buyer-container --}}

    {{-- JavaScript --}}
 


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let activeTab = "{{ session('tab', request('tab', 'product-show')) }}";
            let targetButton = document.querySelector(`.tab-button[onclick*="${activeTab}"]`);
            
            openTab({ currentTarget: targetButton || document.querySelector('.tab-button') }, activeTab);
            
            renderNotifications();
        });

        function openTab(evt, tabName) {
            let contents = document.querySelectorAll(".tab-content");
            contents.forEach(content => content.style.display = "none");
            
            let buttons = document.querySelectorAll(".tab-button");
            buttons.forEach(button => button.classList.remove("active"));
            
            let targetTab = document.getElementById(tabName);
            if (targetTab) targetTab.style.display = "block";
            
            if(evt && evt.currentTarget) {
                evt.currentTarget.classList.add("active");
            }
        }

        const storageKey = "notifications_user_{{ auth()->id() ?? 'guest' }}";

        function getNotificationStates() {
            try {
                let data = localStorage.getItem(storageKey);//ログインしている今の情報取得
                return data ? JSON.parse(data) : {};//ある場合に出力、ない場合は何も出さない
            } catch (e) {
                return {};
            }
        }


        function saveNotificationStates(states) {
            try {
                localStorage.setItem(storageKey, JSON.stringify(states));
            } catch (e) {
                console.error("保存失敗", e);
            }
        }


        function markNotification(txnId, state) {
            let states = getNotificationStates();
            if (state === 'deleted' && !confirm('削除フォルダに移動しますか？')) return;

          
            if (state === 'permanently_delete') {
            if (!confirm('本当にこの通知を一覧から削除しますか？')) return;

            delete states[txnId]; 
           saveNotificationStates(states);
           renderNotifications();
           return;
}

            states[txnId] = state;
            saveNotificationStates(states);
            renderNotifications();
        }


        function renderNotifications() {//画面の最新状態更新
            let states = getNotificationStates();//statesに取得情報保存
            let filterElement = document.getElementById('notification-filter');//comboboxの取得
            let filter = filterElement ? filterElement.value : 'all';//
            let cards = document.querySelectorAll('.notification-card');
            
            let visibleCount = 0; 

            cards.forEach(card => {
                let txnId = card.getAttribute('data-txn-id');
                let status = parseInt(card.getAttribute('data-status')) || 0;
                let currentState = states[txnId] || 'unread';

                let badgeArea = card.querySelector('.badge-area');
                let actionArea = card.querySelector('.action-area');
                let canDelete = (status >= 5);

                if (currentState === 'deleted') {
                    badgeArea.innerHTML = '<span style="color: #999; font-weight: bold; margin-right: 5px;">🗑️ 削除済み</span>';

                actionArea.innerHTML = `
                <button onclick="markNotification('${txnId}', 'permanently_delete')" 
                        style="padding: 6px 12px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    完全に削除
                </button>`;
                } else if (currentState === 'read') {
                    badgeArea.innerHTML = '<span style="background-color: #10b981; color: white; font-size: 12px; padding: 3px 8px; border-radius: 10px; margin-right: 5px;">既読</span>';
                    if (canDelete) {
                        actionArea.innerHTML = `<button onclick="markNotification('${txnId}', 'deleted')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">削除</button>`;
                    } else {
                        actionArea.innerHTML = `<span style="color: #999; font-size: 12px; font-weight: bold; margin-top: 5px;">取引中のため削除不可</span>`;
                    }
                } else { 
                    badgeArea.innerHTML = '<span style="background-color: #ef4444; color: white; font-size: 12px; padding: 3px 8px; border-radius: 10px; margin-right: 5px;">未読</span>';
                    let deleteBtnHtml = canDelete 
                        ? `<button onclick="markNotification('${txnId}', 'deleted')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">🗑️️削除</button>`
                        : `<span style="color: #999; font-size: 12px; font-weight: bold; margin-left: 10px; line-height: 30px;">取引中のため削除不可</span>`;
                    actionArea.innerHTML = `
                        <button onclick="markNotification('${txnId}', 'read')" style="padding: 6px 12px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">既読にする</button>
                        ${deleteBtnHtml}
                    `;
                }

                let isVisible = false;
                if (filter === 'all') {
                    isVisible = (currentState !== 'deleted');
                } else {
                    isVisible = (currentState === filter);
                }

                if (isVisible) {//isVisibleで画面に表示するか
                    card.style.display = "block";
                    visibleCount++; // 💡 表示されたらカウントを増やす
                } else {
                    card.style.display = "none";
                }
            });


            // 💡 すべて隠れた時に「該当する通知はありません」を出す処理
            let jsEmptyMsg = document.getElementById('js-empty-message');
            if (jsEmptyMsg) {
                if (cards.length > 0 && visibleCount === 0) {
                    jsEmptyMsg.style.display = "block"; 
                } else {
                    jsEmptyMsg.style.display = "none";  
                }
            }
        }

        function applyFilter() {
            renderNotifications();
        }

         function filterHistory(selectedValue) {
            // 画面内のすべての「商品行（product-item）」を取得
            const items = document.querySelectorAll('.product-item');

            let visibleCount = 0;

            items.forEach(item => {
                // 「すべて表示」が選ばれているか、行の目印（data-status）が選択されたものと一致する場合
                if (selectedValue === 'all' || item.getAttribute('data-status') === selectedValue) {
                    item.style.display = 'flex'; // 表示する
                    visibleCount++;
                } else {
                    item.style.display = 'none'; // 一致しないものは隠す
                }
            });

            let emptyMsg = document.getElementById('history-empty-message');
            if (emptyMsg) {
                // 商品が元々1件以上あるのに、表示件数が0になってしまったらメッセージを出す
                if (items.length > 0 && visibleCount === 0) {
                    emptyMsg.style.display = "block"; 
                } else {
                    emptyMsg.style.display = "none";  
                }
            }
}
    </script>
</x-buyer>
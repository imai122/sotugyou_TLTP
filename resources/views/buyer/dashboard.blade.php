<x-buyer>
<div class="buyer-container">
        {{-- フラッシュメッセージ --}}
       <x-flash-message />
        
        {{-- タブボタン --}}
        <div class="tab-nav" style="display: flex; align-items: center;">
            <button class="tab-button" onclick="openTab(event, 'product-show')">商品閲覧</button>
            <button class="tab-button" onclick="openTab(event, 'history')">履歴</button>
            <button class="tab-button" onclick="openTab(event, 'notification')">通知</button>
             <a href="{{ route('user.logout') }}" class="logout-link">ログアウト</a>
            </div>

        {{--商品閲覧 --}}
        <div id="product-show" class="tab-content">
            <h2>商品閲覧</h2>
            
            <form action="" method="GET" class="search-box">
                <input type="hidden" name="tab" value="product-show">
                <label>検索:</label>
                <input type="text" name="name" value="{{ request('name') }}" placeholder="商品名で検索">
                <input type="submit" value="検索">
            </form>
            
        @php
                $active_products = $other_products->filter(function($item) {
                    return \Carbon\Carbon::parse($item->end_date)->isFuture();
                });
            @endphp

            <div class="product-grid">
                    @forelse ($active_products as $item)
                    <div class="product-card">
                        <x-product-table :item="$item" />
                        <a href="{{ route('buyer.bids', $item->product_id) }}" class="btn-bid">入札する</a>
                    </div>
                @empty
                    <p>現在、出品されている商品はありません。</p>
                @endforelse
            </div>
        </div>

        {{--履歴 --}}
        <div id="history" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">あなたの入札履歴</h2>

                 <form action="" method="GET" class="search-box">
                <input type="hidden" name="tab" value="history">
                <label>検索:</label>
                <input type="text" name="history" value="{{ request('history') }}" placeholder="商品名で検索">
                <input type="submit" value="検索">
            </form>
            

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
                    // 現在時刻と期限を比較
                $isExpired = \Carbon\Carbon::parse($bid->products->end_date)->isPast();
        // 自分が落札者かどうか（won_product_ids配列に現在のproduct_idがあるか）
                $isWon = in_array($bid->product_id, $won_product_ids);
                $rowStatus = 'active'; // デフォルト
        if ($bid->products->status === '出品終了' || $isExpired) {
            $rowStatus = $isWon ? 'sold' : 'unsold';
        }
                @endphp
                   <div class="list-row product-item" data-status="{{ $rowStatus }}">
                        <span class="col-150">{{ $bid->products->product_name }}</span>
                        <span class="col-150">{{ auth()->user()->name }}</span>
                        <span class="col-150">{{ auth()->user()->address }}</span>
                        <span class="col-150" style="color: #e60000; font-weight: bold;">{{ number_format($bid->bid_amount) }} 円</span>
                        <span class="col-180" style="color: #666;">{{ \Carbon\Carbon::parse($bid->bid_at)->format('Y/m/d H:i') }}</span>
                        <!-- <span class="col-150">{{ $bid->products->status }}</span> -->

                 <span class="col-150">
            @if ($isExpired)
                {{-- 出品期間終了 --}}
                @if ($isWon)
                    <span style="color: #e53e3e; font-weight: bold; background-color: #fed7d7; padding: 4px 10px; border-radius: 9999px;">落札</span>
                @else
                    <span style="color: #718096; font-weight: bold; background-color: #edf2f7; padding: 4px 10px; border-radius: 9999px;">未落札</span>
                @endif
            @else
                {{-- 出品期間中 --}}
                <span style="color: #3182ce; font-weight: bold; background-color: #ebf8ff; padding: 4px 10px; border-radius: 9999px;">出品中</span>
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
                   
                    <span></span> <span class="badge-area"></span>
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
                         <div style="background-color: #d4edda; padding: 10px; border-radius: 6px; border: 1px solid #c3e6cb;">
                        <div style="color: #155724; font-weight: bold; margin-bottom: 10px; font-size: 0.95rem;">
                         入金が完了しました！管理者に通知済みです。しばらくお待ちください。
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
                        @elseif($transaction->status == 4)
   
    <div style="background-color: #fff3cd; padding: 10px; border-radius: 6px; border: 1px solid #ffeeba;">
        <div style="color: #856404; font-weight: bold; font-size: 0.95rem;">
            受け取り確認が完了しました。現在、管理者が振込処理を行っていますので、しばらくお待ちください。
        </div>
    </div>
                        
                        @elseif($transaction->status == 5)
                        <div style="background-color: #d4edda; padding: 10px; border-radius: 6px; border: 1px solid #c3e6cb;">
                        <div style="color: #155724; font-weight: bold; margin-bottom: 10px; font-size: 0.95rem;">
                        取引終了しました。落札おめでとうございます。
                         </div>
                         </div>
                    
                        @endif
                    
                    </div>
                
                 
                  @empty
                
            @endforelse
            </div>

            </div>
      

    {{-- JavaScript --}}
 

{{-- ▼ JavaScript ▼ --}}
    <script>
     // 通知JSに渡すための設定データ
        window.AppConfig = {
            userId: "{{ auth()->id() }}",
            csrfToken: "{{ csrf_token() }}",
            hideUrl: "{{ url('buyer/notification/hide') }}"
        };

        // タブの切り替え機能
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none"; // すべてのタブを隠す
            }
            tablinks = document.getElementsByClassName("tab-button");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", ""); // ボタンの色を戻す
            }
            document.getElementById(tabName).style.display = "block"; // 選んだタブだけ表示する
            if (evt) {
                evt.currentTarget.className += " active"; // 押したボタンをアクティブにする
            }
        }

        // ページを読み込んだ時に商品閲覧を開く
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const urlTab = urlParams.get('tab');
            const phpTab = "{{ $activeTab ?? '' }}";
            
            // 優先順位: ①URL ②PHP(コントローラー) ③デフォルト(商品閲覧)
            const lastTab = urlTab || phpTab || 'product-show';
            
            // 指定されたタブのボタンを探してクリックする
            const targetBtn = document.querySelector(`.tab-button[onclick*="${lastTab}"]`);
            if (targetBtn) {
                targetBtn.click();
            } else {
                const firstTab = document.querySelector('.tab-button');
                if (firstTab) firstTab.click();
            }
        });
        //履歴タブの絞り込み機能
        function filterHistory(selectedValue) {
            const items = document.querySelectorAll('.product-item');
            let visibleCount = 0;

            items.forEach(item => {
                if (selectedValue === 'all' || item.getAttribute('data-status') === selectedValue) {
                    item.style.display = 'flex'; // 表示する
                    visibleCount++;
                } else {
                    item.style.display = 'none'; // 隠す
                }
            });

            let emptyMsg = document.getElementById('history-empty-message');
            if (emptyMsg) {
                if (items.length > 0 && visibleCount === 0) {
                    emptyMsg.style.display = "block"; 
                } else {
                    emptyMsg.style.display = "none";  
                }
            }
        }
    </script>

    {{-- 外部に分けた通知用のJSファイルを読み込む --}}
    <script src="{{ asset('js/buyer_notification.js') }}"></script>
</x-buyer>
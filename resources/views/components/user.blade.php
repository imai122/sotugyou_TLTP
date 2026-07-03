<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品者ダッシュボード</title>
  
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>

    <div id="main-content">
        {{ $slot }}
    </div> 

    
    <script>
        console.log(' JS読み込み完了');

        // タブ切り替え処理
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.tab-button');
            if (button) {
                const tabName = button.getAttribute('data-tab');
                window.openTab(e, tabName);
            }
        });

        window.openTab = function(evt, tabName) {

            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.replaceState(null, '', url);
            // 記憶する
            localStorage.setItem('lastActiveTab', tabName);

            // 1. 全コンテンツを非表示
            document.querySelectorAll(".tab-content").forEach(c => {
                c.classList.remove("active");
                c.style.display = "none"; 
            });
            
            // 2. 全ボタンのクラスを外す
            document.querySelectorAll(".tab-button").forEach(b => b.classList.remove("active"));
            
            // 3. 指定タブを表示
            let targetTab = document.getElementById(tabName);
            
            //安全策: もしIDが取れなかったら強制的に product-history を表示
            if (!targetTab) {
                console.warn("タブが見つかりません。デフォルトを表示します:", tabName);
                tabName = 'product-history';
                targetTab = document.getElementById(tabName);
            }

            if (targetTab) {
                targetTab.classList.add("active");
                targetTab.style.display = "block"; 
            }
            
            // 4. クリックしたボタンをアクティブにする
            const btn = document.querySelector(`.tab-button[data-tab="${tabName}"]`);
            if (btn) btn.classList.add("active");
        };

        // --- 通知と履歴の処理 ---
        const storageKey = "notifications_user_{{ auth()->id() ?? 'guest' }}";

        function getNotificationStates() {
            try {
                let data = localStorage.getItem(storageKey);
                return data ? JSON.parse(data) : {};
            } catch (e) { return {}; }
        }

        function saveNotificationStates(states) {
            try { localStorage.setItem(storageKey, JSON.stringify(states)); } catch (e) { console.error("保存失敗", e); }
        }

        window.markNotification = function(txnId, state) {
            let states = getNotificationStates();
            if (state === 'deleted' && !confirm('削除フォルダに移動しますか？')) return;
            
            if (state === 'permanently_delete') {
                if (!confirm('本当にこの通知を一覧から削除しますか？')) return;
                delete states[txnId]; 
                saveNotificationStates(states);
                window.renderNotifications();
                return;
            }
            
            states[txnId] = state;
            saveNotificationStates(states);
            window.renderNotifications();
        };

        window.renderNotifications = function() {
            let states = getNotificationStates();
            let filterElement = document.getElementById('notification-filter');
            let filter = filterElement ? filterElement.value : 'all';
            let cards = document.querySelectorAll('.notification-card');
            let visibleCount = 0; 

            cards.forEach(card => {
                let txnId = card.getAttribute('data-txn-id');
                let status = parseInt(card.getAttribute('data-status')) || 0;
                let currentState = states[txnId] || 'unread';
                let badgeArea = card.querySelector('.badge-area');
                let actionArea = card.querySelector('.action-area');
                if(!badgeArea || !actionArea) return;
                
                let canDelete = (status >= 5);

                if (currentState === 'deleted') {
                    badgeArea.innerHTML = '<span style="color: #999; font-weight: bold; margin-right: 5px;"> 削除済み</span>';
                    actionArea.innerHTML = `<button onclick="markNotification('${txnId}', 'permanently_delete')" style="padding: 6px 12px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">完全に削除</button>`;
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
                        ? `<button onclick="markNotification('${txnId}', 'deleted')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">削除</button>`
                        : `<span style="color: #999; font-size: 12px; font-weight: bold; margin-left: 10px; line-height: 30px;">取引中のため削除不可</span>`;
                    actionArea.innerHTML = `
                        <button onclick="markNotification('${txnId}', 'read')" style="padding: 6px 12px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">既読にする</button>
                        ${deleteBtnHtml}
                    `;
                }

                let isVisible = (filter === 'all') ? (currentState !== 'deleted') : (currentState === filter);
                card.style.display = isVisible ? "block" : "none";
                if (isVisible) visibleCount++;
            });

            let jsEmptyMsg = document.getElementById('js-empty-message');
            if (jsEmptyMsg) jsEmptyMsg.style.display = (cards.length > 0 && visibleCount === 0) ? "block" : "none";
        };

        window.applyFilter = function() { window.renderNotifications(); };

        window.filterHistory = function(selectedValue) {
            const items = document.querySelectorAll('.product-item');
            let visibleCount = 0;
            items.forEach(item => {
                if (selectedValue === 'all' || item.getAttribute('data-status') === selectedValue) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            let emptyMsg = document.getElementById('history-empty-message');
            if (emptyMsg) emptyMsg.style.display = (items.length > 0 && visibleCount === 0) ? "block" : "none";
        };

        // ページロード時の初期化
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const urlTab = urlParams.get('tab');
            const phpTab = "{{ $activeTab ?? '' }}";
            const sessionTab = "{{ session('tab', '') }}";
            
            const lastTab = urlTab || phpTab || sessionTab || localStorage.getItem('buyerLastTab') || 'product-show';
            
            openTab(null, lastTab);
         });
     </script>
</body>
</html>
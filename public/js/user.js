console.log('✅ JS読み込み完了: タブ機能 + 通知機能');

// 1. タブ切り替え機能
window.openTab = function(evt, tabName) {
    let contents = document.querySelectorAll(".tab-content");
    contents.forEach(content => content.classList.remove("active"));
    
    let buttons = document.querySelectorAll(".tab-button");
    buttons.forEach(button => button.classList.remove("active"));
    
    let targetTab = document.getElementById(tabName);
    if (targetTab) {
        targetTab.classList.add("active");
    }
    
    if (evt && evt.currentTarget) {
        evt.currentTarget.classList.add("active");
    } else {
        let activeBtn = document.querySelector(`.tab-button[onclick*="${tabName}"]`);
        if (activeBtn) activeBtn.classList.add("active");
    }
};

// 2. 通知と履歴の処理
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
        renderNotifications();
        return;
    }
    
    states[txnId] = state;
    saveNotificationStates(states);
    renderNotifications();
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
            badgeArea.innerHTML = '<span style="color: #999; font-weight: bold; margin-right: 5px;">🗑️ 削除済み</span>';
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
                ? `<button onclick="markNotification('${txnId}', 'deleted')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">🗑️️削除</button>`
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

window.applyFilter = function() { renderNotifications(); };

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

// 3. ページロード時の初期化
function initDashboard() {
    // 最初のタブを表示
    if (document.getElementById('product-history')) {
        window.openTab(null, 'product-history');
    }
    // 通知の初期化
    window.renderNotifications();
}

document.addEventListener('DOMContentLoaded', initDashboard);
document.addEventListener('turbo:load', initDashboard);
document.addEventListener('livewire:navigated', initDashboard);
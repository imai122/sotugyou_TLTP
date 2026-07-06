const STORAGE_KEY = 'seller_notification_states_' + window.AppConfig.userId;

function getNotificationStates() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {};
}

function saveNotificationStates(states) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(states));
}

function markNotification(txnId, action) {
    let states = getNotificationStates();
    if (action === 'delete') {
        states[txnId] = 'deleted';
    } else if (action === 'read') {
        states[txnId] = 'read';
    }
    saveNotificationStates(states);
    applyFilter();
}

function removeFromStorage(txnId) {
    let states = getNotificationStates();
    delete states[txnId];
    saveNotificationStates(states);
}

function applyFilter() {
    const filterValue = document.getElementById('notification-filter').value;
    const cards = document.querySelectorAll('.notification-card');
    let visibleCount = 0;
    const states = getNotificationStates();
    
    // Bladeから渡されたトークンを使う！
    let csrfToken = window.AppConfig.csrfToken;

    cards.forEach(card => {
        const txnId = card.getAttribute('data-txn-id');
        const status = card.getAttribute('data-status');
        const currentState = states[txnId] || 'unread';
        const badgeArea = card.querySelector('.badge-area');
        const actionArea = card.querySelector('.action-area');

        if (currentState === 'deleted') {
            badgeArea.innerHTML = '<span style="color: #999; font-weight: bold; margin-right: 5px;">削除済み</span>';
            
            // Bladeから渡されたURLを使う！
            let deleteUrl = `${window.AppConfig.hideUrl}/${txnId}`;
            
            actionArea.innerHTML = `
            <form action="${deleteUrl}" method="POST" onsubmit="if(confirm('本当にこの通知を一覧から完全に削除しますか？')) { removeFromStorage('${txnId}'); return true; } else { return false; }" style="margin: 0;">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <button type="submit" style="padding: 6px 12px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    完全に削除
                </button>
            </form>`;
        } else if (currentState === 'read') {
            badgeArea.innerHTML = '<span style="color: #6c757d; font-weight: bold; margin-right: 5px;">既読</span>';
            if (status == 5) {
                actionArea.innerHTML = `<button onclick="markNotification('${txnId}', 'delete')" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">削除</button>`;
            } else {
                actionArea.innerHTML = `<span style="color: #999; font-size: 12px; font-weight: bold;">取引中のため削除不可</span>`;
            }
        } else {
            badgeArea.innerHTML = '<span style="color: #dc3545; font-weight: bold; margin-right: 5px;"> 未読</span>';
            let buttonsHTML = `<button onclick="markNotification('${txnId}', 'read')" style="padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">既読にする</button>`;
            
            if (status == 5) {
                buttonsHTML += `<button onclick="markNotification('${txnId}', 'delete')" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-left: 5px;">削除</button>`;
            } else {
                buttonsHTML += `<span style="color: #999; font-size: 12px; font-weight: bold; margin-left: 10px;">取引中のため削除不可</span>`;
            }
            actionArea.innerHTML = buttonsHTML;
        }

        let show = false;
        if (filterValue === 'all' && currentState !== 'deleted') show = true;
        if (filterValue === 'unread' && currentState === 'unread') show = true;
        if (filterValue === 'read' && currentState === 'read') show = true;
        if (filterValue === 'deleted' && currentState === 'deleted') show = true;

        if (show) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('js-empty-message').style.display = (visibleCount === 0) ? 'block' : 'none';
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', () => {
    applyFilter();
});
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>システム管理者ダッシュボード</title>
</head>
<body>
    <main>
<h1>ダッシュボード</h1>
  <style>
    .tab-button { display: inline-block; cursor: pointer; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    
</style>
        <button class="tab-button" onclick="openTab(event, 'admin.shop')">ショップ管理者情報</button>
        <button class="tab-button" onclick="openTab(event, 'order')">出品者情報</button>
        <button class="tab-button" onclick="openTab(event, 'buyer')">買い手情報</button>


        <div id="admin.shop" class="tab-content">
            <h2>ショップ管理者登録情報</h2>
            <div>
            <label>検索:</label>
            <input type="text" name="name">
            <input type="submit" value="検索">
            </div>

        <div>
           <form action="{{ route('admin.system.dashboard') }}" method="GET">
           <input type="hidden" name="tab" value="admin.shop">
           <label>検索:</label>
           <input type="text" name="name" value="{{ request('tab') == 'admin.shop' ? request('name') : '' }}">
           <input type="submit" value="検索">
        </form>
        </div>
            
            

         
            <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
            <span style="width: 150px;">名前</span>
            <span style="width: 200px;">住所</span>
            <span style="width: 100px;">電話番号</span>
            <span style="width: 200px;">メールアドレス</span>
            </div>
            @foreach ($yic_users as $yic_user)
            @if($yic_user->role == 2)
            <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
            <span style="width: 150px;">{{ $yic_user->name }}</span>
            <span style="width: 200px;">{{ $yic_user->address }}</span>
            <span style="width: 100px;">{{ $yic_user->phone_number }}</span>
            <span style="width: 200px;">{{ $yic_user->email }}</span>
             <a href="{{ route('admin.system.edit', ['id' => $yic_user->user_id]) }}">
                    <button>修正</button>
            </a>
            <button>削除</button>
        </div>
        @endif
        @endforeach
        </div>
            
           
        
           
        

        <div id="order" class="tab-content">
            <h2>出品者登録情報</h2>
               <div>
        <form action="{{ route('admin.system.dashboard') }}" method="GET">
        <input type="hidden" name="tab" value="order">
        <label>検索:</label>
        <input type="text" name="name" value="{{ request('tab') == 'order' ? request('name') : '' }}">
        <input type="submit" value="検索">
        </form>
        </div>
                
            <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
                <span style="width: 150px;">名前</span>
                <span style="width: 150px;">住所</span>
                <span style="width: 150px;">電話番号</span>
                <span style="width: 150px;">メールアドレス</span>
            </div>
            @foreach ($yic_users as $yic_user)
            @if($yic_user->role == 3)
             <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
                <span style="width: 150px;">{{ $yic_user->name }}</span>
                <span style="width: 150px;">{{ $yic_user->address }}</span>
                <span style="width: 150px;">{{ $yic_user->phone_number }}</span>
                <span style="width: 150px;">{{ $yic_user->email }}</span>
                <a href="{{ route('admin.system.edit',  ['id' => $yic_user->user_id]) }}">
                    <button>修正</button>
                </a>
                <form action="{{ route('admin.system.destroy',  ['id' =>$yic_user->user_id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除">
                </form>
            
            </div>
            @endif
            @endforeach
        </div>
    
        

         <div id="buyer" class="tab-content">
            <h2>買い手登録情報</h2>
               <<div>
        <form action="{{ route('admin.system.dashboard') }}" method="GET">
        <input type="hidden" name="tab" value="buyer">
        <label>検索:</label>
        <input type="text" name="name" value="{{ request('tab') == 'buyer' ? request('name') : '' }}">
        <input type="submit" value="検索">
        </form>
        </div>
            <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
                <span style="width: 150px;">名前</span>
                <span style="width: 150px;">住所</span>
                <span style="width: 150px;">電話番号</span>
                <span style="width: 150px;">メールアドレス</span>
            </div>
            @foreach ($yic_users as $yic_user)
            @if($yic_user->role == 4)
             <div style="display: flex; align-items: center; border-bottom: 1px solid #ccc;">
                <span style="width: 150px;">{{ $yic_user->name }}</span>
                <span style="width: 150px;">{{ $yic_user->address }}</span>
                <span style="width: 150px;">{{ $yic_user->phone_number }}</span>
                <span style="width: 150px;">{{ $yic_user->email }}</span>
             <a href="{{ route('admin.system.edit', ['id' => $yic_user->user_id]) }}">
                    <button>修正</button>
            </a>
              <form action="{{ route('admin.system.destroy',  ['id' =>$yic_user->user_id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除">
                </form>
            </div>
            @endif
            @endforeach
         </div>


       <script>
        function openTab(evt, tabName) {
            let contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            // 選択されたコンテンツのみ表示する
            document.getElementById(tabName).style.display = "block";
        }

        // ページ読み込み時に、指定されたタブを自動で開く
        window.onload = function() {
            // セッション('tab')、または URLのクエリパラメータ(?tab=...) からタブ名を取得。
            // どちらもない場合はデフォルトで 'admin.shop' を開く。
            let activeTab = "{{ session('tab', request('tab', 'admin.shop')) }}";
            
            // タブを開く処理を実行
            openTab(null, activeTab);
        }
        </script>
    </main>

</body>
</html>
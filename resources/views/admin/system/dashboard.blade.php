
<x-system>
    
    <h1>登録ユーザー 一覧情報</h1>

 <x-flash-message />
   
    <div class="search-box">
        <form action="{{ route('admin.system.dashboard') }}" method="GET">
            <label>名前検索:</label>
            <input type="text" name="name" value="{{ request('name') }}">
            <input type="submit" value="検索">
            <a href="{{ route('admin.system.dashboard') }}">クリア</a>
            <a href="{{ route('user.logout') }}" style="margin-left: auto; color: #ef4444; font-weight: bold; text-decoration: none; padding: 10px;">
            ログアウト
        </a>
        </form>
    </div>  

    <table>
        <thead>
            <tr>
                <th>ステータス</th>
                <th>名前</th>
                <th>住所</th>
                <th>電話番号</th>
                <th>メールアドレス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($yic_users as $yic_user)
                @if(in_array($yic_user->role, [2,3,4]))
                    <tr>
                        <td style="text-align: center;"> 
                            @if($yic_user->role == 2)
                                <span class="badge badge-shop">ショップ管理者</span>
                            @elseif($yic_user->role == 3)
                                <span class="badge badge-seller">出品者</span>
                            @elseif($yic_user->role == 4)
                                <span class="badge badge-buyer">買い手</span>
                            @endif
                        </td>

                        <td>{{ $yic_user->name }}</td>
                        
                        <td>{{ $yic_user->address }}</td>
                        <td>{{ $yic_user->phone_number }}</td>
                        <td>{{ $yic_user->email }}</td>
                        
                        <td>
                            <a href="{{ route('admin.system.edit', ['id' => $yic_user->user_id]) }}" class="btn-edit">修正</a>
                            
                            @if($yic_user->role != 2)
                                <form action="{{ route('admin.system.destroy', ['id' => $yic_user->user_id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">削除</button>
                                </form>
                            @else
                                <span style="color: #999; font-size: 12px; margin-left: 5px; font-weight: bold;">※削除不可</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; padding: 30px;">登録されているユーザーがいません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</x-system>
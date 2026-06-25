<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\YIC_user;
use App\Models\Product;
use App\Models\Bid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;
use Illuminate\View\View;

class SystemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('name');
        $query = YIC_user::whereIn('role', [2,3,4]);

      if (!empty($keyword)) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
      }
        $yic_users = $query->get();

        return view('admin/system/dashboard', compact('yic_users'));
    }

    public function edit($id): View
    {
        $user = YIC_user::findOrFail($id); 
        return view('admin.system.edit', compact('user')); // 編集画面へ
}

    public function update(Request $request, $id)
    {
        $user = YIC_user::findOrFail($id);
        $user->update($request->all());

        // ユーザーのroleによって戻るタブを決定
        $tab = $this->getTabName($user->role);

        return redirect()->route('admin.system.dashboard')
                         ->with('success', '更新しました。')
                         ->with('tab', $tab); // 開くタブを指定
        // return redirect()->route('admin.system.dashboard')->with('success', '更新しました。');
    }
    // 各roleに対応するタブのIDを返すヘルパーメソッドを追加

    
    private function getTabName($role)
    {
        if ($role == 2) return 'admin.shop';
        if ($role == 3) return 'order';
        if ($role == 4) return 'buyer';
        return 'admin.dashboard'; // デフォルト
    }

    public function destroy($id): RedirectResponse
    {
        $user = YIC_user::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.system.dashboard')->with('message',   'の情報を削除しました。');
    }

    
}

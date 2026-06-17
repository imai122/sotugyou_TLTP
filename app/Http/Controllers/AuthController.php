<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\YSRRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\YIC_user;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use InternalIterator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showUserLogin() 
    {
        return view('user/login');
    }

    public function showAdminLogin()
    {
        return view('admin/system/login');
    }




    public function loginProcess(YSRRequest $request): RedirectResponse
  {
        $credentials = $request->validated();//validationファイルの中を出す
        
    if (Auth::attempt(['user_id' => $credentials['user_id'], 'password' => $credentials['password']])) {

        $request->session()->regenerate();//セッション攻撃対策

            $users = Auth::user();
        

 
             if ($users->role == 1) {
        return redirect()->route('admin.system.dashboard');
    }

     if ($users->role == 2) {
        return redirect()->route('admin.shop.dashboard');
    }

    //買い手画面へ
    if ($users->role == 4) {
        return redirect()->route('buyer.dashboard');
    }

    //出品者画面へ
    if($users->role == 3) {
        return redirect()->route('user.dashboard');
    }
        }
      return back()->withErrors([
            'user_id' => 'ログイン情報が正しくありません。',
        ])->withInput($request->only('user_id'));

    }

    public function store(YSRRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $yic_users = new YIC_user();
        $users = Auth::user();

        $users->user_id = $data['user_id'];
        $users->password = Hash::make($data['password']);

        $users->save();

        return redirect(route('admin/system/dashboard'));
        

    }

    public function logout()
    {
        Auth::logout(); // ログアウトする
        return redirect()->route('user.login.index'); // ログイン画面へ戻る
    }
}

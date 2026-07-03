<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\YSRRequest;
use App\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\YIC_user;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use InternalIterator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Mail\PasswordReissueMail;


class AuthController extends Controller
{
    public function showUserLogin(): View
    {
        return view('user.login');
    }

    public function showAdminLogin(): View
    {
        return view('admin.system.login');
    }

    /**
     * 新規登録画面の表示
     */
    public function create(): View
    {
        return view('user.register');
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
            if ($users->role == 3) {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'user_id' => 'ログイン情報が正しくありません。',
        ])->withInput($request->only('user_id'));
    }

    /**
     * 新規登録の保存処理
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = YIC_user::create([
            'name'          => $data['name'],
            'address'       => $data['address'],
            'postal_code'   => $data['postal_code'],
            'phone_number'  => $data['phone_number'],
            'email'         => $data['email'],
            'bank_account'  => $data['bank_account'],
            'user_id'       => $data['user_id'],
            'password'      => Hash::make($data['password']),
            'role'          => $data['role'],
            'listing_count'  => 0,
            'purchase_count' => 0,
        ]);

        return redirect()->route('user.login.index')
            ->with('success', '新規登録が完了しました。ログインしてください。');
    }

    public function logout()
    {
        Auth::logout(); // ログアウトする
        return redirect()->route('user.login.index'); // ログイン画面へ戻る
    }

    /**
     * パスワード再発行画面の表示
     */
    public function showReissue(): View
    {
        return view('user.reissue');
    }

    /**
     * パスワード再発行処理（ID・新パスワードをメール送信）
     */
    public function processReissue(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'メールアドレスは必須項目です。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
        ]);

       //出品者と買い手のみ再発行可能
        $user = YIC_user::where('email', $request->email)
        ->whereIn('role', [3,4])
        ->first();

         if (!$user) {
        return redirect()->route('user.login.index')
            ->with('error', '入力されたメールアドレスは登録されていません。');
    }

        if (!in_array($user->role, [3, 4])) {
            return redirect()->route('user.login.index')
                ->with('error', 'このメールアドレスはID・パスワードの再発行対象外です。');
        }

        // CreateRequestのパスワードルール（大文字始まり・8文字以上）を満たすランダムパスワードを生成
        $newPassword = $this->generatePassword();

        $user->password = Hash::make($newPassword);
        $user->save();

        Mail::to($user->email)->send(new PasswordReissueMail($user, $newPassword));

        return redirect()->route('user.login.index')
            ->with('success', '入力されたメールアドレス宛にご案内をお送りしました。');
    }

    /**
     * 大文字始まり・8文字以上のランダムパスワードを生成する
     */
    private function generatePassword(): string
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';

        // 先頭は必ず大文字
        $password = $upper[random_int(0, strlen($upper) - 1)];

        // 残り7文字は英数字からランダムに生成（合計8文字）
        $chars = $upper . $lower . $numbers;
        for ($i = 0; $i < 7; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }
}

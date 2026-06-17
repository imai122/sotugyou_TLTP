<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if (!Auth::check()) {//確認
          
            if ($request->is('admin/system*')) {
                return redirect()->guest(route('login.index'));
            }
            return redirect()->guest(route('user.login.index'));
        }
        $roleMapping = [
            'system' => 1, // システム管理者
            'shop'  => 2, // ショップ管理者
            'user'   => 3, // 出品者
            'buyer'  => 4, // 買い手
        ];

        // 2. ログイン中のユーザーの権限を数字として取得
        $userRole = (int) Auth::user()->role;

        if (isset($roleMapping[$role]) && $userRole === $roleMapping[$role]) {
            return $next($request);//roleへ
        }

        // 一致しない場合は弾く
        $currentRole = Auth::user()->role ?? 'NULL(取得失敗)';
        $expectedNum = $roleMapping[$role] ?? '未定義';
        abort(403, 'このページへのアクセス権限がありません。'); 
    }
        // return $next($request);
        }

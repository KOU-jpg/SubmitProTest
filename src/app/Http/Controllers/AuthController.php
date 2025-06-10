<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class AuthController extends Controller
{

//会員登録ページ表示
  public function showRegisterForm()  { return view('auth/register');  }

//会員登録処理
  public function register(RegisterRequest $request)  {
    $form = $request->all();
    $form['password'] = Hash::make($form['password']);
    $user = User::create($form);
    // 認証メール送信
    event(new Registered($user));
    auth()->login($user);
    return redirect()->route('verification.notice');}

//ログインページ表示
public function showLoginForm()  {  return view('auth/login');  }

//ログイン処理
  public function login(LoginRequest $request){
  $credentials = $request->only('email', 'password');
  // 認証成功：セッション再生成（セキュリティ対策）
  if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      return redirect('/');    }
  // 認証失敗：エラーメッセージを付けて元の画面に戻す
  return back()->withErrors([
      'auth.failed' => 'ログイン情報が登録されていません。',
  ])->withInput();}


//ログアウト処理
  public function logout(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
  }


// 認証案内ページ
    public function verifyEmailNotice() {
    return view('auth.verify-email');
  }

// 認証リンク処理
  public function verifyEmail(EmailVerificationRequest $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect('/mypage/profile')->with('status', 'Already verified!');
    }
    $request->fulfill(); // これでemail_verified_atがセットされる
    event(new Verified($request->user()));
    return redirect('/mypage/profile')->with('status', 'Email verified!');
  }

// 認証メール再送信
  public function resendVerificationEmail(Request $request) {
  if ($request->user()->hasVerifiedEmail()) {
      return redirect('/mypage/profile');
  }
  $request->user()->sendEmailVerificationNotification();
  return back()->with('message', 'メールを再送しました');
}
}
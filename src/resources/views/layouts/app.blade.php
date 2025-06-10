<!-- 共通レイアウト -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      @yield('header')
        <a href="{{ route('items.index')}}">
          <img src="{{ asset('images/logo.svg') }}" class="header__image" alt="coachtechロゴ"></a>
        @if(Auth::check() && Auth::user()->hasVerifiedEmail())
        <form class="header-search" method="GET"
        action="{{ ($page ?? '') === 'mylist' ? url('/?page=mylist') : route('items.index') }}">
          <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
        </form>
        <nav class="header-nav">
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="nav-link" style="background:none;border:none;padding:0;cursor:pointer;">
          ログアウト
          </button>
        </form>
          <a href="{{ route('mypage')  }}" class="nav-link">マイページ</a>
          <a href="{{ route('sell.form')  }}" class="nav-link exhibit-btn">出品</a>
        @else
          <a href="{{ route('login') }}" class="nav-link">ログイン</a>
        @endif
      </nav>
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>
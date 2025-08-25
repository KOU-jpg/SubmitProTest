<header class="header">
    <div class="header__inner">
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
            </nav>
        @else
            <nav class="header-nav">
                <a href="{{ route('login') }}" class="nav-link">ログイン</a>
            </nav>
        @endif
    </div>
</header>
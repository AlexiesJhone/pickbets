<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="{{asset('/img/pick.png')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PICKBETS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
	<link href="{{ asset('js2/bootstrap.bundle.js') }}" rel="stylesheet">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/appx.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-dark  shadow-sm">
            <div class="container">
                <a class="navbar-brand text-white" href="{{ url('/') }}">
                    <!--<b class="text-warning">PICKBET</b>-->
					<img src="{{asset('/img/logo.jpeg')}}" class="rounded-circle" width="50" height="50" alt="">
                </a>
                <button class="navbar-toggler font-weight-normal text-white" style="border-color:none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <!-- <span class="navbar-toggler-icon"></span> -->
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    <!-- <i class="fa fa-home" aria-hidden="true"></i> -->
                    Menu
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest

                    @else
                    @if(Auth::user()->role ===9)
                    <ul class="navbar-nav mr-auto">
                      <li class="text-nowrap nav-item {{ (request()->is('home')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('home')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                      </li>
                      <li class="text-nowrap nav-item {{ (request()->is('viewpending')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('viewpending')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('viewpending') }}">{{ __('Pending Bets') }}</a>
                      </li>
                      <li class="text-nowrap nav-item {{ (request()->is('bethistory')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('bethistory')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('bethistory') }}">{{ __('Bets History') }}</a>
                      </li>
                      <!-- <li class="text-nowrap nav-item {{ (request()->is('pick20/claims')) ? 'active' : '' }}">
                        <a class="nav-link text-white" href="{{ route('Withdrawals') }}">{{ __('Withdrawals') }}</a>
                      </li> -->
                      <!-- <li class="text-nowrap nav-item {{ (request()->is('/bethistory')) ? 'active' : '' }}">
                        <a class="nav-link text-white" href="{{ route('bethistory') }}">{{ __('Bet History') }}</a>
                      </li> -->
                      <!-- <li class="text-nowrap nav-item {{ (request()->is('/bethistory')) ? 'active' : '' }}">
                        <a class="nav-link text-white" href="{{ route('bethistory') }}">{{ __('Pending Bets') }}</a>
                      </li> -->
                      <li class="text-nowrap nav-item {{ (request()->is('transactions')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('transactions')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('transactions') }}">{{ __('Transactions') }}</a>
                      </li>
                      <li class="text-nowrap nav-item {{ (request()->is('leaders')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('leaders')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('leaders') }}">{{ __('Leaderboard/Results') }}</a>
                      </li>
                      <li class="nav-item dropdown">
                          <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Cash in/out
                          </a>

                          <div class="dropdown-menu dropdown-menu-dark dropdown-menu-right bg-dark" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item font-weight-bold" href="#" data-toggle="modal" data-target="#cashin"> Cash In</a>
                            <a class="dropdown-item font-weight-bold" href="#" data-toggle="modal" data-target="#cashout"> Cash Out</a>
                          </div>
                      </li>
                      <!-- <li class="text-nowrap nav-item">
                        <a class="nav-link text-white" data-toggle="modal" data-target="#changepassword">{{ __('Change Password') }}</a>
                      </li> -->
                    </ul>
                    @endif
                    @if(Auth::user()->role ===3)
                    <ul class="navbar-nav mr-auto">
                      <li class="text-nowrap nav-item {{ (request()->is('home')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('home')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                      </li>
                      <li class="text-nowrap nav-item {{ (request()->is('viewpending')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('viewpending')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('viewpending') }}">{{ __('Pending Bets') }}</a>
                      </li>
                      <li class="text-nowrap nav-item {{ (request()->is('bethistory')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('bethistory')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('bethistory') }}">{{ __('Bets History') }}</a>
                      </li>
                      <!-- <li class="text-nowrap nav-item {{ (request()->is('bethistory')) ? 'active' : '' }}">
                        <a class="nav-link text-white "style="color:#343a40" href="{{ route('bethistory') }}">{{ __('Bet History') }}</a>
                      </li> -->
                      <li class="text-nowrap nav-item {{ (request()->is('transactionhistory')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('transactionhistory')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('transactionhistory') }}">{{ __('Transactions') }}</a>
                      </li>
                      <!-- <li class="text-nowrap nav-item {{ (request()->is('transferfunds')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('transferfunds')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('transferfunds') }}">{{ __('Transferfunds') }}</a>
                      </li> -->
                      <li class="text-nowrap nav-item {{ (request()->is('withdrawal')) ? 'active' : '' }}">
                        <a class="nav-link {{ (request()->is('withdrawal')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('withdrawal') }}">{{ __('Withdrawal') }}</a>
                      </li>
                      <!-- <li class="text-nowrap nav-item">
                        <a class="nav-link text-white " data-toggle="modal" data-target="#userwithdraw">{{ __('Withdrawal') }}</a>
                      </li> -->
                      <li class="text-nowrap nav-item {{ (request()->is('leaders')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('leaders')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('leaders') }}">{{ __('Leaderboard / Results') }}</a>
                      </li>
                      <!-- <li class="text-nowrap nav-item {{ (request()->is('rules')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('rules')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('rules') }}">{{ __('Rules') }}</a>
                      </li>
                      <li class="text-nowrap nav-item {{ (request()->is('prizes')) ? 'active' : '' }}">
                        <a class="nav-link text-white {{ (request()->is('prizes')) ? 'text-dark font-weight-bold' : 'text-white' }}" href="{{ route('prizes') }}">{{ __('Prizes') }}</a>
                      </li> -->
                      <!-- <li class="text-nowrap nav-item">
                        <a class="nav-link text-white" data-toggle="modal" data-target="#changepassword">{{ __('Change Password') }}</a>
                      </li> -->
                    </ul>
                    @endif
                    @endguest
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item ">
                                    <a class="nav-link btn btn-sm btn-success text-white" href="{{ route('login') }}">{{ __('Click here to Login') }}</a>
                                </li>
                            @endif

                            <!-- @if (Route::has('register'))
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif -->
                        @else
                          <!-- <li class="nav-item"><cash :user="{{ Auth::user() }}"></cash></li> -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                  <!-- <cash :user="{{ Auth::user() }}"></cash> -->
                                  <i class="fa fa-user-circle" aria-hidden="true"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-dark dropdown-menu-right bg-dark " aria-labelledby="navbarDropdown">
                                  <a class="dropdown-item font-weight-bold" href="#" data-toggle="modal" data-target="#changepassword"> Change Password</a>
                                  <a class="dropdown-item  font-weight-bold" href="#" data-toggle="modal" data-target="#changeaccountdetails"> Account Details</a>
                                  <a  class="dropdown-item  font-weight-bold" href="{{ route('rules') }}" > Rules</a>
                                  <a class="dropdown-item  font-weight-bold" href="{{ route('prizes') }}" > Prizes</a>
                                    <a class="dropdown-item font-weight-bold" href="{{ route('logout') }}" id="logout"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }} <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <form id="logout-form2" action="{{ route('logoutforce') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

</body>
</html>

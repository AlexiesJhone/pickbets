<div class="sidebar" data-color="white" >
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a class="simple-text logo-normal text-white">
      {{ Auth::user()->name }}
    </a>
  </div>
  <!-- admin -->
    @if(Auth::user()->role ===1)
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p class="white {{ $activePage == 'dashboard' ? ' active' : '' }}">{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'reports' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('Reports') }}" >
          <i class="material-icons" >stacked_bar_chart</i>
            <p class="white {{ $activePage == 'reports' ? ' active' : '' }}">{{ __('Reports') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'users' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('usermanagement') }}">
          <i class="material-icons" >group</i>
            <p class="white {{ $activePage == 'users' ? ' active' : '' }}">{{ __('User Management') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'groups' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('groups') }}">
          <i class="material-icons">groups</i>
            <p class="white {{ $activePage == 'groups' ? ' active' : '' }} ">{{ __('Groups') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'arena' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('arena') }}">
          <i class="material-icons" >place</i>
            <p class="white {{ $activePage == 'arena' ? ' active' : '' }} ">{{ __('Arena') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'logs' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('logs') }}">
          <i class="material-icons">history</i>
            <p class="white {{ $activePage == 'logs' ? ' active' : '' }} ">{{ __('History Logs') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'awd' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('leaders') }}">
          <i class="material-icons" >leaderboard</i>
            <p class="white {{ $activePage == 'awd' ? ' active' : '' }}">Leaderboard / Results</p>
        </a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white btn-round hidethis" id="logout" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <i class="material-icons">logout</i>
        {{ __('Log out') }}</a>
    </li>
    </ul>
  </div>
  <!-- CSR -->
  @elseif(Auth::user()->role === 6)
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item {{ $activePage == 'users' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('usermanagement') }}">
          <i class="material-icons" >group</i>
            <p class="white {{ $activePage == 'users' ? ' active' : '' }}">{{ __('User Management') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'awd' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('leaders') }}">
          <i class="material-icons" >leaderboard</i>
            <p class="white {{ $activePage == 'awd' ? ' active' : '' }}">Leaderboard / Results</p>
        </a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white btn-round hidethis" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <i class="material-icons">logout</i>
        {{ __('Log out') }}</a>
    </li>
    </ul>
  </div>
  <!-- declarator -->
  @elseif(Auth::user()->role ===5||Auth::user()->role ===8)
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p class="white {{ $activePage == 'dashboard' ? ' active' : '' }}">{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'awd' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('leaders') }}">
          <i class="material-icons" >leaderboard</i>
            <p class="white {{ $activePage == 'awd' ? ' active' : '' }}">Leaderboard / Results</p>
        </a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white btn-round hidethis" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <i class="material-icons">logout</i>
        {{ __('Log out') }}</a>
    </li>
    </ul>
  </div>
  <!-- boss/manager -->
  @elseif(Auth::user()->role ===7)
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item {{ $activePage == 'reports' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('Reports') }}" >
          <i class="material-icons" >stacked_bar_chart</i>
            <p class="white {{ $activePage == 'reports' ? ' active' : '' }}">{{ __('Reports') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'users' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('usermanagement') }}">
          <i class="material-icons" >group</i>
            <p class="white {{ $activePage == 'users' ? ' active' : '' }}">{{ __('User Management') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'awd' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('leaders') }}">
          <i class="material-icons" >leaderboard</i>
            <p class="white {{ $activePage == 'awd' ? ' active' : '' }}">Leaderboard / Results</p>
        </a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white btn-round hidethis" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <i class="material-icons">logout</i>
        {{ __('Log out') }}</a>
    </li>
    </ul>
  </div>
  <!-- cashier -->
  @elseif(Auth::user()->role ===4)
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'cashier' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('cashier') }}">
          <i class="material-icons">dashboard</i>
            <p class="white {{ $activePage == 'cashier' ? ' active' : '' }}">{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'withdraw' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('withdrawcashier') }}">
          <i class="material-icons">money</i>
            <p class="white {{ $activePage == 'withdraw' ? ' active' : '' }}">{{ __('Withdraw') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'deposit' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('deposit') }}">
          <i class="material-icons">payments</i>
            <p class="white {{ $activePage == 'deposit' ? ' active' : '' }}">{{ __('Deposit') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'transactions' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('transactioncashier') }}">
          <i class="material-icons">account_balance</i>
            <p class="white {{ $activePage == 'transactions' ? ' active' : '' }}">{{ __('Transactions') }}</p>
        </a>
      </li>
      <li class="nav-item {{ $activePage == 'awd' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('leaders') }}">
          <i class="material-icons" >leaderboard</i>
            <p class="white {{ $activePage == 'awd' ? ' active' : '' }}">Leaderboard / Results</p>
        </a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white btn-round hidethis" id="logout" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <i class="material-icons">logout</i>
        {{ __('Log out') }}</a>
    </li>
    </ul>
  </div>
  @endif
</div>

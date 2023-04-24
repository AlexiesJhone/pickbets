<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-absolute fixed-top ">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand font-weight-bold">{{ $titlePage }}</a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
    <span class="sr-only">Toggle navigation</span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      <!-- <form class="navbar-form">
        <div class="input-group no-border">
        <input type="text" value="" class="form-control" placeholder="Search...">
        <button type="submit" class="btn btn-white btn-round btn-just-icon">
          <i class="material-icons">search</i>
          <div class="ripple-container"></div>
        </button>
        </div>
      </form> -->
      <ul class="navbar-nav">
        <!-- <li class="nav-item">
          <a class="nav-link" href="{{ route('home') }}">
            <i class="material-icons">dashboard</i>
            <p class="d-lg-none d-md-block">
              {{ __('Stats') }}
            </p>
          </a>
        </li> -->
        <li class="nav-item hidethisx">
          <a class="btn btn-danger btn-sm col-md-12" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

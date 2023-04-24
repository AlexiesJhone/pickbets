<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="{{asset('/img/pick.png')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pick 20</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/appx.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" id="app" style="height: auto;">
  <div class="row align-items-center">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      <form class="form" method="POST" action="{{ route('login') }}">
        @csrf
      <br>
      <br>
      @if($announcement->announcement)
      <div class="alert alert-success" role="alert"><center>
      <h4 class="alert-heading">Announcement</h4><hr>
      <p>{{$announcement->announcement}}</p>
      </div>
      @else
        @endif
        <div class="card card-login card-hidden mb-3" style="border-top-left-radius:20%;border-top-right-radius:20%; ">
          <div class="card-header bg-warning font-weight-bold text-center text-warning" style="border:5px solid #343a40 !important;border-top-left-radius:20%;border-top-right-radius:20%;">
             <img src="{{asset('/img/logo.jpeg')}}" width="80" height="80" style="border-radius:150%;">
          </div>
          <div class="card-body" style='border-right:5px solid #343a40 !important;border-left:5px solid #343a40 !important;'>
            @if (session('info'))
                    <div class="alert alert-info" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        {{ session('info') }}
                    </div>
            @endif
            <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                </div>
                <input type="text" name="username" class="form-control" placeholder="{{ __('Username...') }}" value="{{ old('username') }}" required>
              </div>

              @if ($errors->has('username'))
                <div id="email-error" class="error text-danger pl-3" for="username" style="display: block;">
                  <strong>{{ $errors->first('username') }}</strong>
                </div>
              @endif
            </div>
            <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
              <div class="input-group">
                <div class="input-group-prepend">
                </div>
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password...') }}" required>
              </div>
              <a href="{{ route('passwordreset') }}">Forgot Password?</a>
              @if ($errors->has('password'))
                <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                  <strong>{{ $errors->first('password') }}</strong>
                </div>
              @endif
            </div>
            <!-- <div class="form-check mr-auto ml-3 mt-3">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                <span class="form-check-sign">
                  <span class="check"></span>
                </span>
              </label>
            </div> -->
          </div>
          <div class="card-footer justify-content-center" style='border-right:5px solid #343a40 !important;border-left:5px solid #343a40 !important;border-bottom:5px solid #343a40 !important;border-top:none!important;background-color: white'>
            <button type="submit" class="btn btn-success btn-sm text-white form-control">{{ __('Login') }}</button><br><hr>
            <a href="/register" class="btn btn-info btn-sm text-white form-control">
            Register
            </a><br><br>
            <a href="/leaders">
            <button type="button" class="btn btn-secondary btn-sm form-control" name="button">Go to Results / leaderboards</button>
            </a>
          </div>
        </div>
        <div class="alert alert-success" role="alert"><center>
		<h4 class="alert-heading">Jackpot prize for perfect score</h4><hr>
		<p>Perfect score of 20 : <b>8 Million</b> <br> <b>Take note : </b>if there`s a perfect score you will only get the jackpot prize otherwise if there are more than 1 winner the prize will be divided among the winners equally, Top 1 Highest Score is not included.</p>
        <h4 class="alert-heading">Prizes</h4><hr>
        <p>Top 1 Highest Score : <b>40 percent of total net fees.</b><br>only if there`s no perfect score.</p>
        <p>Top 2 Highest Score : <b>30 percent of total net fees.</b></p>
        <p>top 3 Highest Score : <b>20 percent of total net fees.</b></p>
		<p>top 4 Highest Score : <b>10 percent of total net fees.</b></p>
        <h4 class="alert-heading"><center>Lowest Scores</center></h4><hr>
        <p>0 Lowest Score : <b>10,000 each.</b></p>
        <p>1 Lowest Score : <b>3,000 each.</b></p>
        <p>2 Lowest Score : <b>2,000 each.</b></p>
		<p>3 Lowest Score : <b>500 each.</b></p>
        </div>
      </form>
    </div>
  </div>
</div>
</html>

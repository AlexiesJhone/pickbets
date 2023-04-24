@extends('layouts.app2')

@section('content')
<div class="container h-100">
  <br><br>
  <div class="row justify-content-center">
      <div class="col-lg-7 col-md-8">
          <div class="card card-login card-hidden mb-3" style="border:none">
            <div class="card-header bg-dark text-center text-warning font-weight-bold">
              <strong>{{ __('Verify Your Email Address') }}</strong>
            </div>
            <div class="card-body">
              <p class="card-description text-center"></p>
              <p>
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                {{ __('Before proceeding, please check your email for a verification link.') }}

                @if (Route::has('verification.resend'))
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm text-white">{{ __('Click here to request another') }}</button>
                    </form>
                @endif

              </p>
              @if (session('resent'))
              @else
              <verify :user='{{ Auth::user() }}'></verify>
              @endif
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

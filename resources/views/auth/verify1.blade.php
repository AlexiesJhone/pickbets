
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Kodinger">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Reset Password</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/my-login.css') }}">
  <style media="screen">
  body{
    background: #0F2027;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to bottom, #2C5364, #203A43, #0F2027);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to bottom, #2C5364, #203A43, #0F2027); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    height: 100vh
  }
  </style>
</head>
<body class="my-login-page">
	<section class="h-100">
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
                </div>
              </div>
          </div>
      </div>
		</div>
	</section>

	<!-- <script src="jquery-3.4.1.min.js"></script>
	<script src="bootstrap/js/popper.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="js/my-login.js"></script> -->
</body>
</html>

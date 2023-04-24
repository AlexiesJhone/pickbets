@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
          <logoutcashout :user="{{ Auth::user() }}"></logoutcashout> 
        </div>
    </div>
</div>
@endsection

@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          @guest
          <topplayers></topplayers>
          @else
          <topplayers :user="{{ Auth::user() }}"></topplayers>
          @endguest
        </div>
    </div>
</div>
@endsection

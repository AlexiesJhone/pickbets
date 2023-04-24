@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          @guest
          <boardadmin></boardadmin>
          @else
          <boardadmin :user="{{ Auth::user() }}"></boardadmin>
          @endguest
        </div>
    </div>
</div>
@endsection

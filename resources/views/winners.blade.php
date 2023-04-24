@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          @guest
          <winners></winners>
          @else
          <winners :user="{{ Auth::user() }}"></winners>
          @endguest
        </div>
    </div>
</div>
@endsection

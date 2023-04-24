@extends('layouts.app2')

@section('content')
<div class="container h-100">
  <div class="row justify-content-center">
      <div class="col-md-12">
        <pending :post-route="{{ route('postComment') }}" :user="{{ Auth::user() }}"></pending>
      </div>
  </div>
</div>
@endsection

@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <transactions :user="{{ Auth::user() }}"></transactions> 
        </div>
    </div>
</div>
@endsection

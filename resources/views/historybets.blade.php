@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <history :user="{{ Auth::user() }}"></history>
        </div>
    </div>
</div>
@endsection

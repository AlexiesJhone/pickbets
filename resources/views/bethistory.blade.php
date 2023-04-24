@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <bethistory :user="{{ Auth::user() }}"></bethistory> 
        </div>
    </div>
</div>
@endsection

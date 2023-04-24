@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <transferfunds :userx="{{ Auth::user() }}"></transferfunds>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app20')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <summaryreport :user="{{ Auth::user() }}"></summaryreport>
        </div>
    </div>
</div>
@endsection

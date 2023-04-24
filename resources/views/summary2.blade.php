@extends('layouts.appmaterial', ['activePage' => 'deposit', 'titlePage' => __('Deposit')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <!-- <cashier :user="{{ Auth::user() }}"></cashier> -->
      <summaryreport :user="{{ Auth::user() }}"></summaryreport>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
@endpush

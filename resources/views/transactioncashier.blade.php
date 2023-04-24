@extends('layouts.appmaterial', ['activePage' => 'transactions', 'titlePage' => __('Transactions')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <!-- <cashier :user="{{ Auth::user() }}"></cashier> -->
      <transcashier :user="{{ Auth::user() }}"></transcashier>
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

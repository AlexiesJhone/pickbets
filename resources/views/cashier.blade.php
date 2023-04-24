@extends('layouts.appmaterial', ['activePage' => 'cashier', 'titlePage' => __('Cashier')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <cashier :user="{{ Auth::user() }}"></cashier>
      <!-- <claims :user="{{ Auth::user() }}"></claims> -->
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

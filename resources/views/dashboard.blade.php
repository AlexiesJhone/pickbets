@extends('layouts.appmaterial', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <admin-dashboard :user="{{ Auth::user() }}"></admin-dashboard>
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

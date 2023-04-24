@extends('layouts.appmaterial', ['activePage' => 'reports', 'titlePage' => __('Reports')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <!-- <reports :user="{{ Auth::user() }}"></reports> -->
      <reportdashboard :user="{{ Auth::user() }}"></reportdashboard>
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

@extends('layouts.appmaterial', ['activePage' => 'logs', 'titlePage' => __('History Logs')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <logs :user="{{ Auth::user() }}"></logs>
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

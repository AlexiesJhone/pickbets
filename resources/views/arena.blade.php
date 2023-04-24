@extends('layouts.appmaterial', ['activePage' => 'arena', 'titlePage' => __('Arena')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <arena :user="{{ Auth::user() }}"></arena>
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

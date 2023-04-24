@extends('layouts.appmaterial', ['activePage' => 'groups', 'titlePage' => __('Groups')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <groups :user="{{ Auth::user() }}"></groups>
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

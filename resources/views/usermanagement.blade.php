@extends('layouts.appmaterial', ['activePage' => 'users', 'titlePage' => __('User-Management')])

@section('content')
  <div class="content" id="app">
    <div class="container-fluid">
      <admin-users :user="{{ Auth::user() }}"></admin-users>
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

@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Dashboard')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
    <div class="row">
      <!-- Greetings Content Starts -->
      <div class="col-12 dashboard-greetings">
        <div class="card">
          <div class="card-header">
            <h3 class="greeting-text">Welcome {{session('name')}}!</h3>
            <p class="mb-0"></p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="card-header p-1">
               <h4 class="text-primary border-bottom pb-1">
                  <strong> Announcements </strong>
               </h4>

               <div class="row p-1">
                  <div class="col-xl-6 col-md-3 col-6">
                    Deadline for Submission of PPMP
                  </div>
                  <div class="col-xl-6 col-md-3 col-6 ">
                    Allocated Budget
                  </div>
               </div>

            </div>
          </div>
        </div>

      </div>
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script>
@endsection


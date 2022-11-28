@extends('layouts.fullLayoutMaster')
{{-- title --}}
@section('title','Login Page')
{{-- page scripts --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/authentication.css')}}">
@endsection

@section('content')
<!-- login page start -->
<section id="auth-login" class="row flexbox-container">
  <div class="col-xl-8 col-11">
    <div class="card bg-authentication mb-0">
      <div class="row m-0">
        <!-- left section-login -->
        <div class="col-md-6 col-12 px-0">
          <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
            <div class="card-header pb-1">
              <div class="card-title text-center">
              <img src="{{asset('images/logo/logo.png')}}" alt="SLSU Logo" width="170"><br><br>
                <h4 class="text-center mb-2">Procurement Management Information System</h4>
              </div>
            </div>
            <div class="card-content">
              <div class="card-body">
             
                @if (!empty(session("globalerror")))
                <div class="alert alert-danger" role="alert">
                    {{session("globalerror")}}
                </div>
                <?php Session::forget('globalerror')?>
                @endif
                <div class="d-flex flex-md-row flex-column justify-content-around">
                  <!-- <a href="/auth/google" class="btn btn-social btn-google btn-block font-small-3 mr-md-1 mb-md-0 mb-1">
                    <i class="bx bxl-google font-medium-3"></i>
                    <span class="pl-50 d-block text-center">Google</span>
                  </a> -->
                  <a href="/auth/google" class="btn btn-outline-primary glow w-100 position-relative"> <img src="https://hrmis.southernleytestateu.edu.ph/images/logo/google.png" alt="Sign In" width="20"> Sign-in with Google
                </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- right section image -->
        <div class="col-md-6 d-md-block text-center align-self-center p-3">
          <div class="card-content">
            <img class="img-fluid" src="{{asset('images/pages/login.png')}}" alt="branding logo">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- login page ends -->
@endsection

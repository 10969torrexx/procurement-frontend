@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    // $global = new GlobalDeclare();
@endphp

@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','APP NON - CSE')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/print.min.css')}}">
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
@endsection
@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <div class="card">
    <!-- Greetings Content Starts -->
    <section id="basic-datatable">
      <div class="card-content text-center row" >
        <div class="col-sm-2"></div>
        <div class="col-sm-8 mt-1 mb-1" style="border:1px solid black">
          <div class="card-header text-black mt-5" >
            Republic of the Philippines<br>
            SOUTHERN LEYTE STATE UNIVERSITY<br>
            {{ (new GlobalDeclare)->Campus_location(session('campus')) }}, Southern Leyte
          </div>
          <div class="card-body  " >
            <h5> 
              <strong>BAC RESOLUTION TO JUSTIFY THE USE OF <br>
              ALTERNATIVE METHODS OF PROCUREMENT</strong>
            </h5>
            <div class="row mt-1">
              <div class="col-sm-1"></div>
              <div class="text-justify col-sm-9 " {{-- style="text-align:justify" --}}>  
                Whereas, Article IV. Sec. 10 of RA 9184 of the Procurement Reform Act 
                mandates that all procurement shall be done through competitive public 
                bidding except as provided for in Article XVI of this Act;
                <br><br>
                
                Whereas, Article XVI Sec. 48 of this Act Allows the Procuring Entity to 
                resort to any of the five alternative methods of procurement, subject to
                final approval of the Head of Procuring Entity (HOPE) or his / her duly 
                authorized representatives.
                <br><br>

                Whereas, the use of Alternative Methods of Procurement (AMP) shall be 
                justifies by the conditions provided in this Act and for the purpose of 
                promoting economy and efficiency
                <br><br>

                Whereas, the BAC intends to promote economy and efficiency in all 
                procurement activities and ensures that the most advantegeous and 
                responsive bid price be obtained in the AMP;
                <br><br>

                Whereas, the BAC ensures that the terms and conditions set forth in RA9184 
                will be strictly observed in all procurement undertakings.
                <br><br>

                Now, therefore, we, the members of Bid and Awards Committee (BAC), hereby 
                RESOLVE as it is hereby RESOLVED to recommend the use 
                <div class="col-sm-6 mr-4" style="float:right"><input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="--Click Here--" class="col-sm-12"></div>
                {{-- <div class="col-sm-6 mr-4" style="float:right"><input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="--Click Here--" class="col-sm-12"></div> --}}
                <div class="col-sm-6 mr-4 text-center" style="float:right"><span style="border:none;font-style:italic;text-align:center" >(Type of Alternative Methods)</span></div>
                <br><br><br><br>
                
                In the procurement of &nbsp;<input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="(Type of Procurement)" class="col-sm-3"> &nbsp;as reflected
                in the duty approved Purchase Request No. &nbsp;<input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="" class="col-sm-3"> &nbsp;
                dated &nbsp; <input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="" class="col-sm-3">&nbsp;since there is no sufficient time to conduct competitive
                public bidding and the use of AMP is more advantageous to the Government of the Philippines in the terms of economy and efficiency.
                <br><br>
                RESOLVED, at the Southern Leyte State University, {{ (new GlobalDeclare)->Campus_location(session('campus')) }}, Southern, Leyte, this &nbsp; <input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="" class="col-sm-1">
                day of <input type="text" style="border-bottom:1px solid black;border-top:none;border-left:none;border-right:none;text-align:center" placeholder="" class="col-sm-3">.

              </div>
            </div>
            <div class="row mt-2">
              <div class="col-sm-1"></div>
              <div class="col-sm-9 row">
                <div class="col-sm-4">
                  <select class="form-select col-sm-12 text-uppercase"  style="border:none;text-align:center;" aria-label="Default select example">
                    <option selected>-- Select Name-- </option>
                    @foreach ($users as $user1)   
                      <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                    @endforeach
                  </select> <br>
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
                <div class="col-sm-4">
                  <select class="form-select col-sm-12 text-uppercase"  style="border:none;text-align:center;" aria-label="Default select example">
                    <option selected>-- Select Name-- </option>
                    @foreach ($users as $user1)   
                      <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                    @endforeach
                  </select> <br>
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
                <div class="col-sm-4">
                  <select class="form-select col-sm-12 text-uppercase"  style="border:none;text-align:center;" aria-label="Default select example">
                    <option selected>-- Select Name-- </option>
                    @foreach ($users as $user1)   
                      <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                    @endforeach
                  </select> <br>
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-sm-1"></div>
              <div class="col-sm-9 row">
                <div class="col-sm-4">
                  <select class="form-select col-sm-12 text-uppercase"  style="border:none;text-align:center;" aria-label="Default select example">
                    <option selected>-- Select Name-- </option>
                    @foreach ($users as $user1)   
                      <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                    @endforeach
                  </select> <br>
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
                <div class="col-sm-4">
                  <select class="form-select col-sm-12 text-uppercase"  style="border:none;text-align:center;" aria-label="Default select example">
                    <option selected>-- Select Name-- </option>
                    @foreach ($users as $user1)   
                      <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                    @endforeach
                  </select> <br>
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
                <div class="col-sm-4">
                  <select class="form-select col-sm-12 text-uppercase"  style="border:none;text-align:center;" aria-label="Default select example">
                    <option selected>-- Select Name-- </option>
                    @foreach ($users as $user1)   
                      <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                    @endforeach
                  </select> <br>
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-sm-1"></div>
              <div class="col-sm-9 row text-center">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                  <div class="row">
                    {{-- <div class="col-sm-9  text-right"> --}}
                      <select class="form-select {{-- bg-info --}} col-sm-8 text-uppercase"  style="border:none;" aria-label="Default select example">
                        <option selected>-- Select Name-- </option>
                        @foreach ($users as $user1)   
                          <option value="{{ $user1->id }}">{{ $user1->name }}</option>
                        @endforeach
                      </select>  
                    {{-- </div> --}}
                    <input type="text" style="border:none;text-align:left" placeholder="eg.Phd" class=" col-sm-4"> 
                  </div>
                  {{-- <br> --}}
                  <input type="text" style="border:none;text-align:center" placeholder="-- Enter Position --" class="m1-1">
                </div>
                <div class="col-sm-4"></div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-sm-1"></div>
              <div class="col-sm-9 text-left">
                Approved:
                <div class="row">
                  <div class="col-sm-6"></div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-4 ">
                    @foreach ($president as $pres)
                    <div class="row">
                      <div class="col-sm-9 text-right">{{ $pres->name }} , </div>
                      <input type="text" style="border:none;text-align:left" placeholder="--" class=" col-sm-3"> 
                    </div>
                    <div class="text-center">
                      University President
                    </div>
                    @endforeach
                    {{-- JUDE A. DUARTE, DPA --}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>

<script src="{{asset('js/bac/appnoncse.js')}}"></script>
<script src="{{asset('js/bac/table2excel.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>



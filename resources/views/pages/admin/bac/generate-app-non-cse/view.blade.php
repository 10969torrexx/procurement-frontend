@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Approve PPMP')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <div class="card">
    <!-- Greetings Content Starts -->
        <section id="basic-datatable" >
            <div class="card-content">
              <div class="card-body card-dashboard">
                <div class="table-responsive">
                    <table class="text-center" style="width: 100%; line-height:22px;font-size:15px">
                        <thead>
                            <tr style="border-bottom: 1px solid black; border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                <td  rowspan="2" style="width: 9%;border-right: 1px solid black">CODE (PAP)</td>
                                <td  rowspan="2" style="width: 14%;border-right: 1px solid black">Procurement<br/> Project</td>
                                <td  rowspan="2" style="width: 9%;border-right: 1px solid black">PMO/End-<br/>User</td>
                                <td  rowspan="2" style="width: 10%;border-right: 1px solid black">Is this an Early <br> Procurement <br/>Activity? <br/>(Yes/No)</td>
                                <td  rowspan="2" style="width: 10%;border-right: 1px solid black">MODE OF <br/>PROCUREMENT</td>
                                <td  colspan="4" style="width: 14%;border-left: 1px solid black">Schedule for Each Procurement Activity</td>
                                <td  rowspan="2" style="width: 14%;border-left: 1px solid black">Source of Funds</td>
                                <td  colspan="3" style="width: 14%;border-left: 1px solid black">Estimated Budget (PhP)</td>
                                <td  rowspan="2" style="width: 14%;border-left: 1px solid black">Remarks <br/> (brief <br/>Description <br/>of Project)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                <td style="width: 10%;border-right: 1px solid black">Advertisement/<br/> Posting <br/> of IB/REI</td>
                                <td style="width: 10%;border-left: 1px solid black">Submission / <br/> Opening <br/>of Bids</td>
                                <td style="width: 10%;border-left: 1px solid black">Notice of <br/> Award</td>
                                <td style="width: 10%;border-left: 1px solid black">Contract Signing</td>
                                <td style="width: 10%;border-left: 1px solid black">Total</td>
                                <td style="width: 10%;border-left: 1px solid black">MOOE</td>
                                <td style="width: 10%;border-left: 1px solid black">CO</td>
                            </tr>
                        </thead>
                        <tbody>
                                <tr style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                    <td colspan="2" style="border-right: 1px solid black; font-size: 11px;background-color:rgb(190, 225, 247)" class="text-dark text-bold-600">fgxhgfc</td>
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600"></td>
                                    <td style="border-right: 1px solid black; font-size: 11px;background-color:yellow" class="text-dark text-bold-600">xgfhxgfh</td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600"></td>
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600">xgfhxgfh</td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                </tr>
                     </tbody>
                    </table>
                </div>
                <div class="card mt-1">
                    <button type="button" class="btn btn-success form-control col-sm-1"><i class="fa-sharp fa-solid fa-file-export"></i>Generate</button>
                </div>
            </div>
        </section>
  </div>
  @include('pages.bac.generate-app-non-cse.view-modal')
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('js/bac/unitofmeasurement.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>




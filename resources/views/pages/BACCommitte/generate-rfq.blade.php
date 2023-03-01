@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    use Carbon\Carbon;
    //$global = new GlobalDeclare();
@endphp
@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Generate RFQ')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection
<style>
    .default {
        padding: 0px !important;
        margin: 0px !important;
    }
    .border-solid {
        border: 3px solid black;
    }
    .border-right-solid {
        border-right: 3px solid black;
    }
    .border-left-solid {
        border-left: 3px solid black;
    }
    .border-bottom-solid {
        border-bottom: 3px solid black;
    }
    .text-black {
        color: black !important;
    }
    .text-bold {
        font-weight: bolder;
    }
    .text-italic {
        font-style: italic !important;
    }
    .text-times-new-roman {
        font-family: 'Times New Roman' !important;
    }
    .bg-gray {
        background-color: rgb(221, 221, 221) !important;
    }

    #t-table, #t-th, #t-td  {
        border: 1px solid black;
        font-size: 11px;
        padding: 5px;
        text-align: center;
    }
    #t-table{
        width: 100%;
    }

    .tbg-secondary {
        background-color: rgba(71, 95, 123, 0.9) !important;
    }

</style>
@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title border-bottom p-1">
                <strong>
                    Generate Request for Quotation & Notice
                </strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="row text-black col-12 col-md-12 col-lg-12 col-sm-12 justify-content-center">
                <div class="col-sm-8 col-lg-8 col-md-8 col-8  p-2">
                    
                    <div class="row text-black">
                        <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                            <p style="font-size: 10px !important;">November 30, 2022/ Posted PR / Small Value Procurement</p>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                            <div class="text-times-new-roman default float-right">
                                <p class="default">Doc. Code: SLSU-QF-PRO1</p>
                                <p class="default">Revision: 00</p>
                                <p class="default"> Date: {{ Carbon::now()->format('d F Y')  }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row border-solid">
                        <div class="col-sm-6 col-lg-6 col-md-6 col-6 p-2  border-right-solid row">
                            <img src="{{ asset("images/slsu_logo.png") }}" alt="" height="50" width="50">
                            <p class="text-bold ml-2">SOUTHERN LEYTE STATE UNIVERSITY</p>
                        </div>
                        <div class="col-sm-6 col-lg-6 col-md-6 col-6 p-1 ">
                           <p class="text-bold tex-black text-center">
                                REQUEST FOR QUOTATION FORM & NOTICE
                           </p>
                           <p class="text-bold tex-black text-center">
                                GODDS & SERVICES
                            </p>
                        </div>
                    </div>
                    <div class="row border-bottom-solid">
                        <div class="col-sm-4 col-lg-4 col-md-4 col-4 border-right-solid border-left-solid bg-gray">
                            Office/Campus:
                        </div>
                        <div class="col-sm-8 col-lg-8 col-md-8 col-8 border-right-solid">
                            {{ strtoupper((new GlobalDeclare)->get_department(session('department_id'))[0]->description) }} / {{ strtoupper((new GlobalDeclare)->Campus(session('campus'))) }} CAMPUS
                        </div>
                    </div>
                    <div class="row border-bottom-solid">
                        <div class="col-sm-4 col-lg-4 col-md-4 col-4 border-right-solid border-left-solid bg-gray">
                            Address/Contact Details:
                        </div>
                        <div class="col-sm-8 col-lg-8 col-md-8 col-8 border-right-solid">
                           San Roque, Sogod, Southern Leyte
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6 col-6 p-1"></div>
                        <div class="col-sm-6 col-md-6 col-lg-6 col-6 p-1 row">
                            <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                                <p class="default">Purchase Request No.</p>
                                <p class="default">Date:</p>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                                <div class="col-12 border-solid">
                                    <p class="text-center default">
                                        <input type="text" value="2023-02-0116" class="text-center">
                                    </p>
                                </div>
                                <div class="col-12 border-right-solid border-left-solid border-bottom-solid">
                                    <p class="text-center default">{{ Carbon::now()->format('d F Y')  }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <p>GENTLEMAN:</p>
                        <p class="text-black text-italic">
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; May we request for qouatation on materials enumerated hereunder. If you are interested and in position to furnish the same, we shall be glad to have your best prices.
                        </p>
                        <p class="text-black text-italic">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Devliver within 5 working days upon reciept of approved Purchase Order (PO).
                        </p>
                    </div>

                    <div class="row text-black">
                        <table class="table" id="item-table t-table">
                            <thead>
                                <tr class="bg-gray" id="t-tr">
                                    <th id="t-td">Item #</th>
                                    <th id="t-td" class="text-nowrap">QTY</th>
                                    <th id="t-td">UNIT</th>
                                    <th id="t-td">ITEM/DESCRIPTION</th>
                                    <th id="t-td" class="text-nowrap">APPROVED BUDGET</th>
                                    <th id="t-td">UNIT COST</th>
                                    <th id="t-td">TOTAL COST</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                <tr id="t-tr">
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                </tr>


                                <tr id="t-tr">
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td" class="text-right">TOTAL</td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                    <td id="t-td"></td>
                                </tr>

                                <tr id="t-tr">
                                    <td id="t-td" colspan="2">Delivery Term</td>
                                    <td id="t-td" colspan="5"></td>
                                </tr>
                                <tr id="t-tr">
                                    <td id="t-td" colspan="2">Payment Term</td>
                                    <td id="t-td" colspan="2"></td>
                                    <td id="t-td" colspan="2"></td>
                                    <td id="t-td" colspan="2"></td>
                                </tr>
                                <tr id="t-tr">
                                    <td id="t-td" colspan="7"></td>
                                </tr>
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('js/baccommittee/baccommittee.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

{{-- @include('pages.supervisor.supervisor_check_ppmp') --}}



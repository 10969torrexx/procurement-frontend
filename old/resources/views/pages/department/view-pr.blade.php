<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Track Purchase Request')

{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/animate/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
@endsection
{{-- page-styles --}}

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">

@endsection

@section('content')
    
<!-- Scroll - horizontal and vertical table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Purchase Request</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        {{-- <a href = "#" class = "btn btn-success round mr-1 mb-1" data-flag = "{{ $aes->encrypt('accounts')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> New Account</a> --}}
                        {{-- <a href = "#" class = "AllocateBudget btn btn-success round mr-1 mb-1" data-toggle = "modal" data-target = "#AllocateBudgetModal"><i class="bx bx-plus"></i>Allocate Budget</a> --}}
                        {{-- <a href = "allocate_budget1" class = "AllocateBudget1 btn btn-success round mr-1 mb-1"><i class="bx bx-plus"></i>Allocate Budget 1</a> --}}
                        

                        {{-- {{ session('department_id') }} --}}
                        <style>
                            table.PR{
                              
                              width: 100%;
                              border-collapse: collapse;
                              height: 200px;
                              padding: 3px;
                            }
                            table.PR td{
                              border-right: 1px solid black;
                              border-bottom: 1px solid black;
                              border-top: 1px solid black;
                            }
                            td.first{
                              border-left: 1px solid black;
                            }
                            td.title{
                              text-align:center;
                              border-left: 1px solid black;
                            }
                            .head1{
                              text-align:center;
                            }
                            .head2{
                              text-align:center;
                              font-weight:bold;
                              height:50px;
                            }
                            .blank{
                              /* border-left: 1px solid black; */
                              height:25px;
                            }
                            .purpose{
                              border-left: 1px solid black;
                              height:50px;
                            }
                            input{
                              border: none;
                            }
                          </style>
                          {{-- <div class="table-responsive"> --}}
                            <table class="PR">
                              <thead class="head1">
                                <tr>
                                  <td class="title" colspan="6" style="border-bottom : none;font-weight:bold;padding-bottom:1%">PURCHASE REQUEST</td>
                                </tr>
                                <tr>
                                  <td  style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;">Entity Name:</td>
                                  <td colspan="2" style="border-bottom : none;border-top : none;border-right : none"><div class="" style="margin-top:1%;border-bottom:1px solid black;text-align:left;"> Southern Leyte State University - {{ (new GlobalDeclare)->Campus($details[0]->campus )}} Campus</div></td>
                                  <td colspan="2" style="font-weight:bold;border-bottom : none;border-top : none;border-right : none">Fund Cluster:</td>
                                  <td style="border-top: none"><div class="fund_source_input" value="" style="margin-top:2%;border-bottom:1px solid black;text-align:left">{{$fund_source[0]->fund_source}}</div></td>
                                </tr>
                                <tr>
                                  <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;"> Office / Section:</td>
                                  <td colspan="4" style="border-bottom : none;border-top : none;text-align:left;">
                                    <div class="col-sm-12" >
                                      <div style="float:left;height:100%;width:50%;text-align:left;">{{$details[0]->department_name}}</div>
                                      <div style="float:right;width:50%">
                                          <div style="width:20%;float:left;font-weight:bold;" >PR No.</div>
                                          <div style="width:70%;float:left;border-bottom:1px solid black">0000-00-0000</div>
                                      </div>
                                    </div>
                                  </td>
                                  <td style="border-bottom: none">
                                    <div style="width:20%;float:left;font-weight:bold;" >Date:</div>
                                    <div style="width:80%;float:left;border-bottom:1px solid black">{{$date}}</div>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;"></td>
                                  <td colspan="4" style="border-bottom : none;border-top : none;text-align:left;">
                                    <div class="col-sm-12" >
                                      <div style="float:left;width:49%;border-bottom:1px solid black;border-right:1px solid black;height:17px;"></div>
                                      <div style="float:right;width:50%">
                                          <div style="width:70%;float:left;font-weight:bold;" >Responsibility Center Code: </div>
                                          <div style="width:20%;float:left;border-bottom:1px solid black;"> 0000</div>
                                      </div>
                                    </div>
                                  </td>
                                  <td style="border-top: none"></td>
                                </tr>
                                <tr class="head2">
                                  <td class="first" style="width:20%;">Stock / Property No.</td>
                                  <td style="width:10%;">Unit</td>
                                  <td style="width:30%;">Item Description</td>
                                  <td style="width:10%;">Quantity</td>
                                  <td style="width:10%;">Unit Cost</td>
                                  <td style="width:20%;">Total Cost</td>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $i=0; $counter=15; $total = 0;?>
                                @foreach($ppmps as $ppmps)
                                <?php $i++; $counter--; $total+=$ppmps->estimated_price?>
                                <tr>
                                  <td class="first" style="text-align: right;font-style: italic;">{{ $i }}</td>
                                  <td style="text-align: center">{{ $ppmps->unit_of_measurement }}</td>
                                  <td style="text-align: left">{{ $ppmps->item_description }}</td>
                                  <td style="text-align: center">{{ $ppmps->quantity  }}</td>
                                  <td style="text-align: right">{{number_format($ppmps->unit_price,2,'.',',')}}</td>
                                  <td style="text-align: right">{{number_format($ppmps->estimated_price,2,'.',',')}}</td>
                                </tr>
                                @endforeach
                                @for($a = 1; $a < $counter; $a++)
                                <tr class="blank">
                                  <td class="first"></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                                @endfor
                                @if ($a==$counter)
                                  <td class="first"></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>                
                                  <td  style="text-align: right;font-weight:bold;">{{number_format($total,2,'.',',')}}</td>
                                  @endif
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td class="purpose" style="border-right:none;text-align:left;text-align:left;font-weight:bold;">Purpose: </td>
                                  <td colspan="5" >
                                      <div style="border-bottom:1px solid black">
                                        <input type="text" name="add1" value="" placeholder="Enter purpose here" id="purpose_input" class="purpose_input border-none" style="width: 100%;">
                                      </div>
                                  </td>
                                </tr>
                                <tr >
                                  <td style="border-left:1px solid black; border-right: none;border-bottom: none;"></td>
                                  <td colspan="2" style="border-right: none;border-bottom: none; ;padding-bottom:1%">Requested by:</td>
                                  <td colspan="3" style="border-bottom: none;padding-bottom:1%">Approved by:</td>
                                </tr>
                                <tr>
                                  <td style="border-left:1px solid black; border-bottom: none;border-right: none;border-top: none;font-weight:bold;">Signature:</td>
                                  <td colspan="2" style="border-bottom: none;border-right: none;border-top: none;"><div class="" style="margin-left:5%;margin-top:2%;;width:90%;border-bottom:1px solid black;"></div></td>
                                  <td colspan="3" style="border-bottom: none;border-top: none;"><div class="" style="margin-left:5%;margin-top:2%;width:90%;border-bottom:1px solid black;"></div></td>
                                </tr>
                                <tr>
                                  <td style="border-left:1px solid black; border-bottom: none;border-right: none;border-top: none;font-weight:bold;">Printed Name:</td>
                                  <td colspan="2" style="border-right: none;border-top: none;text-align:center;font-weight:bold;" class="selectEmployee" value="">-- Select Employee --<i class="fa-solid fa-pen-to-square employeeEdit" value="" style="margin-left:5px;"></i></td>
                                  <td colspan="3" style="border-top: none;text-align:center;font-weight:bold;">PROSE IVY G. YEPES, EdD</td>
                                </tr>
                                <tr>
                                  <td style="border-left:1px solid black; border-top: none;border-right: none;font-weight:bold;">Designation:</td>
                                  <td colspan="2" style="border-right: none;text-align:center;">
                                    <div style="border-bottom:1px solid black">
                                    <input type="text" name="add1" value="" placeholder="Enter designation here" class="designation_input border-none" style="width: 100%;text-align:center;">
                                  </div></td>
                                  <td colspan="3" style="text-align:center;">University President</td>
                                </tr>
                                <tr><td colspan="6" style="border-left: 1px solid black;height:20px"></td></tr>
                              </tfoot>
                            </table>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ADD MODAL --}}

{{-- END ADD MODAL --}}



<!--/ Scroll - horizontal and vertical table -->
@endsection
{{-- vendor scripts --}}
@section('vendor-scripts')

<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

{{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

<script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>
{{-- employee JS --}}

<script src="{{asset('js/department/purchase_request.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection


  
  {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
  <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
  {{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}

  
<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>


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


    
<!-- Scroll - horizontal and vertical table -->
<section id="horizontal-vertical">
                {{-- <div class="card-header">
                    <h4 class="card-title" style="text-align: center; font-weight: bold;">ROUTING SLIP (PROCUREMENT PROCESS)</h4>
                </div> --}}
                <div class="card-content">
                    <style type="text/css">
                        table.PR{
                          page-break-after:always;
                          width:100%;
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
                          height:20px;
                        }
                        .purpose{
                          border-left: 1px solid black;
                          height:50px;
                        }
                        input{
                          border: none;
                        }
                        /* .page-break{
                          page-break-inside: avoid;
                          page-break-after:always
                        } */
                        .foot{
                          /* page-break-inside: avoid; */
                          /* page-break-after:always */
                        }
                      </style>

                        <div class="table-responsive" >
                          <table class="PR">
                            <thead class="head1" >
                              <tr class="blank" style="border-right: none; height:30px">
                            </tr>
                            <tr>
                              <td class="text-right" colspan="6" style="border: none;font-style: italic;">Appendix 60</td>
                            </tr>
                              <tr>
                                <td class="title" colspan="6" style="border-bottom : none;font-weight:bold;padding-bottom:20px;font-size: 25px;">PURCHASE REQUEST</td>
                              </tr>
                              @foreach($purchase_request as $data)
                              @endforeach
                              <tr>
                                <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;padding-left:10px;">Entity Name:</td>
                                <td colspan="2" style="border-bottom : none;border-top : none;border-right : none"><div class="" style="margin-top:1%;border-bottom:1px solid black;text-align:left;padding-left:10px"> Southern Leyte State University - {{ (new GlobalDeclare())->Campus(session('campus')) }} Campus</div></td>
                                <td colspan="2" style="font-weight:bold;border-bottom : none;border-top : none;border-right : none;padding-left:50px;">Fund Cluster:</td>
                                <td style="border-top: none"><div class="fund_source_input" value="" style="margin-top:2%;border-bottom:1px solid black;text-align:left">{{ $data->fund_source }}</div></td>
                              </tr>
                              <tr>
                                <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;padding-left:10px"> Office/Section:</td>
                                <td colspan="4" style="border-bottom : none;border-top : none;text-align:left;">
                                    <div style="float:left;height:100%;width:50%;text-align:left;padding-left:10px">{{ $data->department_name }}</div>
                                    <div style="width:20%;float:left;font-weight:bold;padding-left:10px" >PR No.</div>
                                    <div style="width:30%;float:left;border-bottom:1px solid black">{{ $data->pr_no }}</div>
                                </td>
                                <td style="border-bottom: none">
                                    <div style="width:30%;float:left;font-weight:bold;" >Date:</div>
                                    <div style="width:70%;float:left;border-bottom:1px solid black">{{ date('m/d/Y', strtotime($data->created_at))}}</div>
                                </td>
                              </tr>
                              <tr>
                                <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;"></td>
                                <td colspan="4" style="border-bottom : none;border-top : none;text-align:left;">
                                    <div style="float:left;width:50%;border-bottom:1px solid black;height:17px;"></div>
                                    <div style="width:40%;float:left;font-weight:bold;border-left : 1px solid black;padding-left:10px;padding-bottom:5px" >Responsibility Center Code: </div>
                                    <div style="width:10%;float:left;border-bottom:1px solid black;padding-top:17px"> </div>
                                </td>
                                <td style="border-top: none"></td>
                              </tr>
                              <tr class="head2">
                                <td class="first" style="width:10%;">Stock/Property No.</td>
                                <td style="width:6%;">Unit</td>
                                <td style="width:35%;">Item Description</td>
                                <td style="width:5%;">Quantity</td>
                                <td style="width:10%;">Unit Cost</td>
                                <td style="width:15%;">Total Cost</td>
                              </tr>
                            </thead>
                            <tfoot >
                              <tr>
                                <td class="purpose" style="height:30px;border-right:none;border-bottom:none;text-align:left;text-align:left;font-weight:bold;padding-left:10px;padding-top:10px">Purpose: </td>
                                <td colspan="5" style="padding-top:10px">{{ $data->purpose }}</td>
                              </tr>
                              <tr>
                                <td class="purpose" style="border-right:none;border-top:none;text-align:left;text-align:left;font-weight:bold;padding-left:10px;height:20px"></td>
                                <td colspan="5"  style="border-bottom:1px solid black;"></td>
                              </tr>
                              <tr >
                                <td style="border-left:1px solid black; border-right: none;border-bottom: none;"></td>
                                <td colspan="2" style="border-right: none;border-bottom: none; ;padding-bottom:1%">Requested by:</td>
                                <td colspan="3" style="border-bottom: none;padding-bottom:1%">Approved by:</td>
                              </tr>
                              <tr>
                                <td style="border-left:1px solid black; border-bottom: none;border-right: none;border-top: none;font-weight:bold;padding-left:10px">Signature:</td>
                                <td colspan="2" style="border-bottom: none;border-right: none;border-top: none;"><div class="" style="margin-left:5%;margin-top:2%;;width:90%;border-bottom:1px solid black;"></div></td>
                                <td colspan="3" style="border-bottom: none;border-top: none;"><div class="" style="margin-left:5%;margin-top:2%;width:90%;border-bottom:1px solid black;"></div></td>
                              </tr>
                              <tr>
                                <td style="border-left:1px solid black; border-bottom: none;border-right: none;border-top: none;font-weight:bold;padding-left:10px">Printed Name:</td>
                                <td colspan="2" style="border-right: none;border-top: none;text-align:center;font-weight:bold;">{{ strtoupper($data->name) }}</td>
                                <td colspan="3" style="border-top: none;text-align:center;font-weight:bold;">{{ strtoupper($data->ao_name).', '.$data->ao_title }}</td>
                              </tr>
                              <tr>
                                <td style="border-left:1px solid black; border-top: none;border-right: none;font-weight:bold;padding-left:10px">Designation:</td>
                                <td colspan="2" style="border-right: none;text-align:center;">{{ $data->designation }}</td>
                                <td colspan="3" style="text-align:center;">{{ $data->ao_designation }}</td>
                              </tr>
                              <tr><td colspan="6" style="border-left: 1px solid black;height:20px;" ></td></tr>
                              
                            </tfoot>
                                 
                            <?php $i=0; $counter = $itemCount; $total = 0;  $pagebreaker = 41 ?>

                            @foreach($itemsForPR as $itemsForPR)
                              
                              <tbody>
                                {{-- @for($loop = 0; $loop < 150; $loop++) --}}
                              <?php $i++; $counter--;?>
                                
                                @if($pagebreaker == $i)
                                <?php $pagebreaker += 40?>
                                  <tr style="page-break-before: always">
                                    <td class="first" style="text-align: right;font-style: italic;padding-right:10px">{{ $i }}</td>
                                    <td style="text-align: center">{{ $itemsForPR->unit_of_measurement }}</td>
                                    <td style="text-align: left;padding-left:10px">{{ $itemsForPR->item_description }}</td>
                                    <td style="text-align: center">{{ $itemsForPR->quantity  }}</td>
                                    <?php $estimated_price =  $itemsForPR->unit_price * $itemsForPR->quantity; 
                                    $total += $estimated_price?>
                                    <td style="text-align: right;padding-right:10px">{{number_format($itemsForPR->unit_price,2,'.',',')}}</td>
                                    <td style="text-align: right;padding-right:10px">{{number_format($estimated_price,2,'.',',')}}</td>
                                  </tr>
                                  @else
                                  <tr>
                                    <td class="first" style="text-align: right;font-style: italic;padding-right:10px">{{ $i }}</td>
                                    <td style="text-align: center">{{ $itemsForPR->unit_of_measurement }}</td>
                                    <td style="text-align: left;padding-left:10px">{{ $itemsForPR->item_description }}</td>
                                    <td style="text-align: center">{{ $itemsForPR->quantity  }}</td>
                                    <?php $estimated_price =  $itemsForPR->unit_price * $itemsForPR->quantity; 
                                    $total += $estimated_price?>
                                    <td style="text-align: right;padding-right:10px">{{number_format($itemsForPR->unit_price,2,'.',',')}}</td>
                                    <td style="text-align: right;padding-right:10px">{{number_format($estimated_price,2,'.',',')}}</td>
                                  </tr>
                                @endif
                                {{-- @endfor --}}
                            @endforeach

                              @php
                                  $quot = (int)($i / 40);
                                  $prod = $quot * 40;
                                  if($i > $prod && $prod != 0){
                                    $addRow = 40 - ($i - $prod);
                                  }else{
                                    $addRow = 40 - $i;
                                  }
                              @endphp

                              @for($count = 0; $count < $addRow; $count++)
                              <tr class="blank" style=" height:22px">
                                <td class="first"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                              @endfor
                             
                                <td class="first"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>                
                                <td  style="text-align: right;font-weight:bold;padding-right:10px">{{number_format($total,2,'.',',')}}</td>
                            </tbody>
  
                           
                          </table>
                  </div>
                      
            </div>
</section>
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

  <style>
    /* .topp td{
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-bottom: 1px solid black;
        border-top: 1px solid black;
    } */

    .ppmp, .top {
        margin-left: auto;
        margin-right: auto;
    }

    .office td{
        border-left: 1px solid black;
        border-right: 1px solid black;
        /* border-bottom: 1px solid black; */
        border-top: 1px solid black;
        font-family: "Times New Roman",  serif;
    }
    .header td{
        border-left: 1px solid black;
        border-right: 1px solid black;

        border-bottom: 1px solid black;
        border-top: 1px solid black;
    }
    .numbering{
        border-left: 1px solid black;
        padding-right: 20px;
        font-family: "Times New Roman", ;
        /* border-right: 1px solid black; */
        /* border-bottom: 1px solid black; */
        /* border-top: 1px solid black; */
    }
    .last_one{
        /* border-left: 1px solid black; */
        /* border-right: 1px solid black; */
        border-bottom: 1px solid black;
        /* border-top: 1px solid black; */
    }
    .activity{
        /* border-left: 1px solid black; */
        border-right: 1px solid black;
        text-align: justify;
        font-family: "Georgia",serif;
        /* border-bottom: 1px solid black; */
        /* border-top: 1px solid black; */
    }
    .blank{
        /* border-left: 1px solid black; */
        border-right: 1px solid black;
        /* border-bottom: 1px solid black; */
        /* border-top: 1px solid black; */
    }
    .align{
        text-align: left;
    }
    .container {
    border: 1px solid rgba(0, 0, 0, .11);
    ;
    padding: 10px;
    width: 500px
    }
    .office{
        font-weight: bold;
        font-style: italic;
        text-align: left;
    }
    .topp td, .card-title{
        font-family: "Georgia", serif;
    }
    .header{
        text-align: center;
        font-weight: bold;
    }
    .controls-item {
        display: inline-block;
    }

    /* .btn {
        margin: 1px;
    } */
    .button:focus{
        outline: none;
    }
    .button{
        padding: 0;
    }
    .table-responsive{
        padding: 20px;
    }

    
</style>
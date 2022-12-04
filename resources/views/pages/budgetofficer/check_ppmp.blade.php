<style>
    .ppmp td{
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-bottom: 1px solid black;
        border-top: 1px solid black;
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
@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp

@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','New PPMP Request')
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
        {{-- <div class="mb-1" id="">
            <button class="btn btn-outline-success done button">Done</button>
        </div> --}}
        <div class="card-cody">
        <div class="table-responsive">

            <table class="text-center ppmp" style=" line-height:22px;font-size:15px">
                <thead>
                    <tr style=" border-top: 1px solid black;">
                        <td  rowspan="2" style="width: 10%;">CODE</td>
                        <td  rowspan="2" style="width: 16%;">GENERAL DESCRIPTION</td>
                        <td  colspan="2" rowspan="2" style="width: 10%;">QTY/SIZE</td>
                        <td  style="width: 10%;">ESTIMATED</td>
                        <td  rowspan="2" style="width: 16%;">MODE OF PROCUREMENT</td>
                        <td  colspan="12">SCHEDULE/MILESTONE OF ACTIVITIES</td>
                    </tr>
                    <tr>
                        <td >BUDGET</td>
                        <td style="width: 1%;">JAN</td>
                        <td style="width: 1%;">FEB</td>
                        <td style="width: 1%;">MAR</td>
                        <td style="width: 1%;">APR</td>
                        <td style="width: 1%;">MAY</td>
                        <td style="width: 1%;">JUN</td>
                        <td style="width: 1%;">JULY</td>
                        <td style="width: 1%;">AUG</td>
                        <td style="width: 1%;">SEPT</td>
                        <td style="width: 1%;">OCT</td>
                        <td style="width: 1%;">NOV</td>
                        <td style="width: 1%;">DEC</td>
                    </tr>
                    <tr >
                        <td style="font-size: 15px;" class="text-dark text-bold-600" >{{-- {{ $data[0]->code }} --}}
                        <?php
                            if($data[0]->projectStatus != 2){
                        ?>
                            --
                        <?php
                            }else if($data[0]->projectStatus == 2){
                        ?>
                            {{ $data[0]->code }}
                        <?php
                            }
                        ?>
                        </td>
                        <td style="font-size: 15px;" class="text-dark text-bold-600">{{ $data[0]->title }}</td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                    </tr>
                </thead>
                {{-- @foreach($data as $data) --}}
                   @php $e = 0 @endphp
                   @php $index = 0 @endphp
                    @for ($i = 0; $i < count($data); $i++)
                        @php $e += $data[$i]->estimated_price; $index+=count($data);@endphp
                        <input type="text" id="project_code12" class=" form-control d-none index" name="project_code" value="{{ $index }}" >
                        <tbody class="tbody">
                            <tr>
                                <td style="text-align: right"> Status:</td>
                                {{-- <td style="text-align: left; color:blue;" id="status{{ $i }}"> --}}
                                     <?php
                                        if($data[$i]->status == 2){
                                    ?>
                                <td style="text-align: left; color:blue;" id="status{{ $i }}" data-toggle="{{ $data[$i]->status }}">Pending</td>
                                <?php
                                        }else if($data[$i]->status == 4){
                                ?>
                                <td style="text-align: left; color:green;" id="status{{ $i }}" data-toggle="{{ $data[$i]->status }}">Accepted</td>
                                <?php
                                        }if($data[$i]->status == 5){
                                ?>
                                <td style="text-align: left; color:red;" id="status{{ $i }}" data-toggle="{{ $data[$i]->status }}">Rejected</td>
                                <?php 
                                        }/* if($data[$i]->status == 6){
                                ?>
                                <td style="text-align: left; color:yellowgreen;" id="status{{ $i }}" data-toggle="{{ $data[$i]->status }}">Revised</td>
                                <?php 
                                        }if($data[$i]->status == 4){
                                ?>
                                    <td style="text-align: left; color:green;" id="status{{ $i }}" data-toggle="{{ $data[$i]->status }}">Accepted by Budget Officer</td>
                                <?php 
                                        }if($data[$i]->status == 5){
                                ?>
                                    <td style="text-align: left; color:red;" id="status{{ $i }}" data-toggle="{{ $data[$i]->status }}">Rejected by Budget Officer</td>
                                <?php } */   
                                ?>
                                {{-- </td> --}}
                                <td colspan="16"> </td>
                            </tr>
                            <tr >
                                <td style=" font-size: 11px" class="text-dark text-bold-600 ">{{ $i+1 }}</td>
                                <td>
                                    
                                    <?php
                                        if($data[$i]->status == 2 || $data[$i]->status == 4 || $data[$i]->status == 5){
                                    ?>
                                    <div class=""style="float: right;">
                                    <i class="fa-brands fa-red-river edit" data-index="{{ $i }}" title="Edit"></i></div><br>
                                    <?php
                                        }
                                    ?>
                                    {{-- <i class="fa-solid fa-pen-to-square edit" data-index="{{ $i }}" title="Edit"></i> --}}
                                    <label style="font-size: 15px;" class="text-dark mt-1 ">{{ $data[$i]->item_name }} </label><br>
                                    <p style="font-size: 13px; text-align:left;" class="mt-1"> Description: <br>{{$data[$i]->item_description }}</p> <br>
                                    {{-- <?php
                                        if($data[$i]->status == 2 || $data[$i]->status == 4 || $data[$i]->status == 5){
                                    ?>
                                        <div class="mb-1" id="action{{ $i }}">
                                            <button class="btn btn-outline-success approve button" data-toggle ="{{ $data[$i]->id }}" data-id="{{ $data[$i]->pt_id }}" value="{{ $i }}" data-index="{{ $i }}" >Accept</button>
                                            <button class="btn btn-outline-danger disapprove button" data-toggle ="{{ $data[$i]->id }}" data-id="{{ $data[$i]->pt_id }}" value="{{ $i }}" data-index="{{ $i }}">Reject</button> 
                                        </div>
                                    <?php
                                        }
                                    ?> --}}
                                    <?php
                                    if($data[$i]->status == 2){
                                ?>
                                    <div class="mb-1" id="action{{ $i }}">
                                        <button class="btn btn-outline-success approve button" data-button="0" data-toggle ="{{ $data[$i]->id }}" data-id="{{ $data[$i]->pt_id }}" value="2" data-index="{{ $i }}" >Accept</button>
                                        <button class="btn btn-outline-danger disapprove button" data-button="{{ $data[$i]->estimated_price }}" data-toggle ="{{ $data[$i]->id }}" data-id="{{ $data[$i]->pt_id }}" value="3" data-index="{{ $i }}">Reject</button> 
                                    </div>
                                <?php
                                    }else if( $data[$i]->status == 4 || $data[$i]->status == 5 ){
                                ?>
                                    <div class="mb-1" id="action{{ $i }}" style="display:none">
                                        <button class="btn btn-outline-success approve button" data-button="-{{ $data[$i]->estimated_price }}" data-toggle ="{{ $data[$i]->id }}" data-id="{{ $data[$i]->pt_id }}" value="2" data-index="{{ $i }}" >Accept</button>
                                        <button class="btn btn-outline-danger disapprove button" data-button="{{ $data[$i]->estimated_price }}" data-toggle ="{{ $data[$i]->id }}" data-id="{{ $data[$i]->pt_id }}" value="3" data-index="{{ $i }}">Reject</button> 
                                    </div>
                                <?php
                                    }
                                ?>
                                </td>
                                <td  class="quantity " style="width: 40px;">{{$data[$i]->quantity }}</td>
                                <td  class="unit" >{{$data[$i]->unit_of_measurement }}</td>
                                <td  class="budget" >Php {{number_format($data[$i]->estimated_price,2 )}}</td>
                                <td  class="mode align" >{{$data[$i]->mode_of_procurement }}</td>
                                <td  class="jan"+$i>@php 
                                if($data[$i]->expected_month == '1') {
                                    echo '<i class="fa-solid fa-check"></i>';
                                }
                                @endphp
                                </td>
                                <td  class="feb"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '2') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="mar"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '3') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="apr"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '4') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="may"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '5') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="jun"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '6') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="jul"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '7') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="aug"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '8') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="sep"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '9') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="oct"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '10') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="nov"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '11') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                                <td  class="dec"+$i>
                                    @php 
                                        if($data[$i]->expected_month == '12') {
                                            echo '<i class="fa-solid fa-check"></i>';
                                        }
                                    @endphp
                                </td>
                            </tr>
                            {{-- <tr >
                                <td style=" font-size: 11px" class="text-dark text-bold-600 "></td>
                                <td class="description align">{{$data[$i]->item_description }}</td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                            </tr> --}}
                            {{-- <tr> 
                                <td style="border:"> </td>
                                <td >
                                    <button class="btn-outline-success approve button" data-toggle ="{{ $data[$i]->id }}" data-id="2" value="{{ $i }}" data-index="{{ $i }}" style="border-radius: 5px; width:45%;height:30px">Approve</button>
                                    <button class="btn-outline-danger disapprove button" style="border-radius: 5px ; width:45%;height:30px">Disapprove</button>
                                </td>
                                <td colspan="19"> </td>
                            </tr> --}}
                        </tbody>
                    @endfor
                {{-- @endforeach --}}
                <tfoot>
                    <tr >
                        <td colspan="4" style="text-align: right; color:black; font-size:12px" class="text-bold-600">SUB-TOTAL:</td>
                        <td class="total1">Php @php echo number_format($e,2) @endphp</td>
                        <td colspan="13"></td>
                    </tr>
                </tfoot>
            </table>
            
        </div>
        <div class="row" hidden>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <fieldset class="form-group">
                  <label for="SubTotal">SubTotal</label>
                  <input type="number" class="sub_total form-control" id="sub_total" placeholder="" value ={{$e}} >
              </fieldset>
            </div>
          </div>
        <div class="col-md-12 text-right">
            <a href = "{{ route('view_ppmp') }}" class = "btn btn-primary round mr-1 mb-1"><i class="bx bx-left-arrow"></i> Back</a>
            <a href = "#" class = "done btn btn-success round mr-1 mb-1"><i class="bx bx-save"></i> Save</a>
            {{-- <button type="button" class="btn btn-success col-md-1 mt-1 ppmpDone" data-id="{{ $data[0]->pt_id }}">Done</button> --}}
        </div>
    </div>
    </div>
    @include('pages.budgetofficer.disapproved_ppmp_modal')
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('js/budgetofficer/appdis.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/datatables/datatable.js')}}"></script> --}}
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>


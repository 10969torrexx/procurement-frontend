@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    // $global = new GlobalDeclare();
@endphp
<style>
    table.Appnoncse_table {
      width: 100%;
      border-collapse: collapse;
      color: black
    }
    
    table.Appnoncse_table td {
      /* border-width: 2px; */
      border-right: 1px solid black;
      border-left: 1px solid black;
      border-top: 1px solid black;
      border-bottom: 1px solid black;
      padding: 3px;
    }
    
    table.Appnoncse_table thead {
      text-align: center;
      font-size: 13px;
    }
    tr.body{
        font-size: 10px;
    }
    .est{
      width:50px;
    }
    td.campus {
      font-style: italic;
      font-size: 10px;
      background-color:rgb(190, 225, 247);
      text-align:left;
      text-transform: capitalize;
    }
    td.category{
      font-size: 10px;
      background-color:yellow;
      text-transform: capitalize;
    }
    td.schedule{
      font-size: 10px;
    }
    .table-div{

    }
    div.header{
      width:100%;
      height:50px;
      margin-bottom: 2%;
    }
    div.header2{
      width:100%;
      height:50px;
      /* margin-bottom: 2%; */
    }
    div.image{
      /* background-color:yellow; */
      width:70px;
      float: left;
      height:100%;
      margin-left: 30%;
    }
    div.slsu{
      /* background-color:yellow; */
      width:25%;
      font-size: 10px;
      float:left;
      margin-left: 3px;
      height:100%;
      text-align:center;
    }
    div.image2{
      /* background-color:yellow; */
      width:70px;
      float: left;
      margin-left: 3px;
      height:100%;
    }
    div.Title{
      text-align: center;
      font-size: 20px;
      text-transform: capitalize;
      font-weight: bold;
      /* margin-left: 10%; */
    }
    div.site{
      text-align: left;
      margin-left: 20%;
    }
    div.p{
        text-align: right;
    }
    tfoot{
      font-size: 10px;
    }
    div.tfoot-title{
      margin-top: 1px;
      margin-bottom: 1px;
      text-align: center;
      font-size: 25px;
      font-family: Edwardian Script ITC;
      color: blue;
      float:top-left;
    }
    div.tfoot-body{
      margin-top: 1px;
      margin-bottom: 1px;
      text-align: center;
    }
    div.personel{
      margin-top: 3%;
      width:100%;
    }
    div.personel2{
      margin-top: 3%;
      width:100%;
      float: left;
    }
    div.personel3{
      margin-top: 3%;
      width:100%;
      float: left;
    }
    div.person{
      float:left;
      margin-left: 6%;
      width: 25%;
      text-align: center
    }
    div.person3{
      float:left;
      margin-left: 6%;
      width: 40%;
      text-align: center;
      margin-bottom: 2%;
    }
    div.person5{
      float:left;   
      text-align: center;
      /* margin-bottom: 30%; */
      width: 100%;
      height: 100%;
    }
    div.name{
      font-weight:bold;
      font-size: 12px;
      text-align: center;
    }
    div.bor{
      /* margin-top: 6%; */
      text-align: center;
    }
    </style>

@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Items')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/print.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <div class="card">
    <!-- Greetings Content Starts -->
        <section id="basic-datatable">
                <div class="card-content">
                    {{-- <div class="card-header">
                    </div> --}}
                    <div class="card-body card-dashboard" >
                      <div class="">
                        <a href ="{{ route('app-non-cse-generate') }}"><button  type="submit" class="btn btn-danger form-control col-sm-1 mt-1 generate" >PDF</button></a>
                        <a href ="{{ route('app-non-cse-generate') }}"><button  type="submit" class="btn btn-success form-control col-sm-1 mt-1 generate" >EXCEL</button></a>
                        <a href ="{{ route('app-non-cse-generate') }}"><button  type="submit" class="btn btn-secondary form-control col-sm-1 mt-1 generate" >CSV</button></a>
                      </div>
                        <div class="table-responsive">
                            <table class="Appnoncse_table" id="table">
                                <thead>
                                  <tr style=" border-right: 1px solid black">
                                      <td  rowspan="2"  class="code">CODE (PAP)</td>
                                      <td  rowspan="2"  class="procurement">Procurement Project</td>
                                      <td  rowspan="2"  class="end_user">PMO / End-User&nbsp;&nbsp;</td>
                                      <td  rowspan="2"  class="activity">Is this <br>an Early<br> Procurement Activity? <br>(Yes/No)</td>
                                      <td  rowspan="2"  class="mode">MODE OF PROCUREMENT</td>
                                      <td  colspan="4"  >Schedule for Each Procurement Activity</td>
                                      <td  rowspan="2"  class="funds">Source of Funds</td>
                                      <td  colspan="3"  class="est">Estimated Budget (PhP)</td>
                                      <td  rowspan="2"  class="budget">Remarks&nbsp;(brief Description of Project)</td>
                                  </tr>
                                  <tr >
                                      <td  class="schedule">Advertisement/<br>Posting of IB/REI&nbsp;&nbsp;</td>
                                      <td  class="schedule">&nbsp;&nbsp;&nbsp;Submission&nbsp;&nbsp;&nbsp; /<br>&nbsp;&nbsp;Opening of Bids &nbsp;&nbsp;</td>
                                      <td  class="schedule">&nbsp;&nbsp;&nbsp;&nbsp;Notice&nbsp;of&nbsp;&nbsp;&nbsp;&nbsp;  Award</td>
                                      <td  class="schedule">&nbsp;&nbsp;Contract&nbsp;Signing&nbsp;&nbsp;&nbsp;</td>
                                      <td  class="budget">Total</td>
                                      <td  class="budget">MOOE</td>
                                      <td  class="budget">CO</td>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                      $oldCampus = "";
                                  ?>
                                  @foreach($Categories as $category)
                                  <?php
                                        if ($oldCampus != $category->campus){
                                  ?>
                                            <tr >
                                              <td colspan="2"  class="text-dark text-bold-600 campus">{{ (new GlobalDeclare)->Campus($category->campus) }}</td>
                                              <td class="text-dark text-bold-600 "></td>
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
                                  <?php 
                                            foreach($Categories as $cat){
                                                if ($cat->campus == $category->campus){
                                  ?>
                                                      <tr >
                                                        <td></td>
                                                        <td class="category">{{ $cat->project_type }}</td>
                                                        <td></td>
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
                                  <?php
                                                    $oldProject = "";
                                              
                                                    foreach($ppmps as $row){
                                                      if ($row->campus == $cat->campus){
                                                        if ($oldProject != $row->project_code){
                                                         
                                  ?>
                                                        <tr class="body">
                                                          <td>{{ $row->ProjectCode}}</td>
                                                          <td>{{ $row->project_title}}</td>
                                                          <td>{{ $row->department_name}}</td>
                                                          <td >No</td>
                                                          <td >{{ $row->mode_of_procurement}}</td>
                                                          <td >1st to 4th Quarter</td>
                                                          <td >1st to 4th Quarter</td>
                                                          <td >1st to 4th Quarter</td>
                                                          <td >1st to 4th Quarter</td>
                                                          <td >{{ $row->fund_source}}</td>
                                                              <?php
                                                                  $total = 0;
                                                                  $totalMOOE = 0;
                                                                  $totalCO = 0;
                                                                  foreach($ppmps as $project){
                                                                     if ($project->campus == $cat->campus && $project->project_code == $row->project_code){
                                                                        $total += $project->estimated_price;
                                                                        if ($project->unit_price <= 50000){
                                                                          $totalMOOE += $project->estimated_price;
                                                                        }else{
                                                                          $totalCO += $project->estimated_price;
                                                                        }
                                                                     }
                                                                  }
                                                              ?>
                                                          <td >{{ $total}}</td>
                                                          <td >{{ $totalMOOE}}</td>
                                                          <td >{{ $totalCO}}</td>
                                                          <td ></td>
                                                        </tr>
                                  <?php
                                                            $oldProject = $row->project_code;
                                                        }
                                                      }
                                                    }
                                                        
                                                }
                                            }
                                            $oldCampus = $category->campus;
                                        }
                                  ?>
                                  
                                 @endforeach
                                </tbody>
                                <tfoot>
                                  <tr >
                                    <td colspan="2">
                                      <div class="head">Prepared by:</div>
                                      <div class="person5" >
                                        <div class="name">
                                          MA. DELIA ONG-MANCA
                                        </div>
                                        <div class="profession">
                                          Secretariat
                                        </div>
                                        <div class="bor">
                                          Date:_____________
                                        </div>
                                      </div> 
                                    </td>
                                    <td colspan="8">
                                      <div class="head">Prepared by:</div>
                                      <div class="tfoot-title">
                                          Bids & Awards Committee
                                      </div>
                                      {{-- <div class="tfoot-body"> --}}
                                        <div class="personel">
                                          <div class="person" >
                                            <div class="name">
                                              MABEL R. CALVA
                                            </div>
                                            <div class="profession">
                                              Chairperson - UBAC Infra<br>
                                              Chairperson - BAC Sogod Campus
                                            </div>
                                          </div> 
                                          <div class="person" >
                                            <div class="name">
                                              EPHRAIM L. CALOPE
                                            </div>
                                            <div class="profession">
                                              Vice Chairperson - UBAC Infra<br>
                                              Vice Chairperson - BAC Infra - Sogod Campus<br>
                                              BAC Regular Member, Goods - Sogod Campus
                                            </div>
                                          </div> 
                                          <div class="person" >
                                            <div class="name">
                                              TAMAR B. MEJIA, JR
                                            </div>
                                            <div class="profession">
                                              Vice Chairperson, BAC Goods - Sogod Campus<br>
                                              BAC Regular Member, Infra - Sogod Campus
                                            </div>
                                          </div>
                                        </div>
                        
                                        <div class="personel2">
                                          <div class="person" >
                                            <div class="name">
                                              JIMSON A. OLAYBAR
                                            </div>
                                            <div class="profession">
                                              BAC Regular Member, Goods, Sogod Campus
                                            </div>
                                          </div> 
                                          <div class="person" >
                                            <div class="name">
                                              RAYMART BULAGSAC
                                            </div>
                                            <div class="profession">
                                              BAC Regular Member, Infra, Sogod Campus
                                            </div>
                                          </div> 
                                          <div class="person" >
                                            <div class="name">
                                              CHARITO V. RUFIN
                                            </div>
                                            <div class="profession">
                                              BAC Alternate Member, Goods - Sogod Campus<br>
                                              BAC Provisional Member, Infra, Sogod Campus
                                            </div>
                                          </div>
                                        </div>
                        
                                        <div class="personel3">
                                          <div class="person3" >
                                            <div class="name">
                                              JOMMAR V. TAGALOG
                                            </div>
                                            <div class="profession">
                                              BAC Alternate Member, Infra- Sogod Campus
                                            </div>
                                          </div> 
                                          <div class="person3" >
                                            <div class="name">
                                              MEIZL M. ANCHETA
                                            </div>
                                            <div class="profession">
                                              BAC Provisional Member, Goods - Sogod Campus
                                            </div>
                                          </div> 
                                        </div>
                        
                                      {{-- </div> --}}
                                    </td>
                                    <td colspan="4">
                                      <div class="head">Approved by:</div>
                                      <div class="person5" >
                                        <div class="name">
                                          PROSE IVY G YEPES, Ed.D.
                                        </div>
                                        <div class="profession">
                                          University President
                                        </div>
                                        <div class="bor">
                                          BOR Resolution No.:______________<br>
                                          Date:_____________
                                        </div>
                                      </div> 
                                  </td>
                                  </tr>
                                </tfoot>
                            </table>
                            {{-- @include('pages.bac.generate-app-non-cse.view-modal') --}}
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
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>




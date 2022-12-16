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
    </style>

@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Items')
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
        <section id="basic-datatable">
                <div class="card-content">
                    <div class="card-header">
                        <form action="{{ route('app-non-cse-generate') }}">
                        <button type="submit" class="btn btn-success form-control col-sm-1 generate" ><i class="fa-sharp fa-solid fa-file-export"></i>Generate</button>
                        </form>
                    </div>
                    <div class="card-body card-dashboard" >
                        <div class="table-responsive" style="border-top: 1px solid black;">
                            <table class="Appnoncse_table" id="table">
                                <thead>
                                  <tr style=" border-right: 1px solid black">
                                      <td  rowspan="2"  class="code">CODE (PAP)</td>
                                      <td  rowspan="2"  class="procurement">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Procurement&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Project</td>
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
                                                      if ($row->project_code == $cat->id && $row->campus == $cat->campus){
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
                                                                     if ($project->project_code == $cat->id && $project->campus == $cat->campus && $project->project_code == $row->project_code){
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

<script src="{{asset('js/bac/appnoncse.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>




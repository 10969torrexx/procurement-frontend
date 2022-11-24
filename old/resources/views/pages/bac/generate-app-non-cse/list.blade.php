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
      border-left: 1px solid black;
      border-top: 1px solid black;
      /* border-bottom: 1px solid black; */
      padding: 3px;
    }
    .remarks{
      border-right: 1px solid black;
    }
    .last{
      border-bottom: 1px solid black;
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
      height:70px;
      /* margin-bottom: 3%; */
      text-align: center;
       /* background-color:blue;  */
    }
    div.header2{
      width:100%;
      height:50px;
      text-align: center;
      /* margin-bottom: 2%; */
    }
    div.image{
      /* background-color:yellow; */
      width:90px;
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
       /* background-color:yellow;  */
      width:90px;
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
    label.link{
      color: dodgerblue;
      text-transform: lowercase;
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
      margin-left: 15%;
      width: 25%;
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
    label.signatoriesName{
      text-transform: uppercase;
      color: black;
    }
    div.bor{
      /* margin-top: 6%; */
      text-align: center;
    }
    img.logo{
      width: 90%;
      height:100%;
    }
    div.logo{
      width: 90%;
      height: 90%;
    }
  #tbl-content{
    width:790px;
    table-layout:fixed;
    background:#fff;
  }
  td.year{
    border-color: white;
  }
  .selectpicker {
     /* color: whitesmoke !important; */
     background: #bf5279 !important;
 }
 div.persons{
      float:left;
      margin-left: 6%;
      width: 25%;
      text-align: center;
      /* border: 1px solid black; */
      height:40px
 }
  div.person13{
    float:left;
    margin-left: 15%;
    width: 25%;
    text-align: center;
    margin-bottom: 2%;
    /* border: 1px solid black; */
    height:40px
  }
  div.recommending_approval_add{
    /* background-color: black; */
    height: 200px;
    display: none;
  }
    </style>

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
      <div class="card-content">
          <div class="card-header">
          <div class="generate">
            <button  type="button" class="btn btn-danger form-control col-sm-1 mt-1 generatepdf" >PDF</button>
           {{-- <form action="{{ route('app-non-cse-generate') }}" method="POST">
            @csrf
            <input type="hidden" class="Year" name="year" value="{{ $Project_title[0]->project_year }}">
            <button  type="submit" class="btn btn-danger form-control col-sm-1 mt-1 " >PDF</button>
            </form> --}}
            {{-- <button  type="submit" class="btn btn-success form-control col-sm-1 mt-1 generateexcel" id="downloadexcel" >EXCEL</button> --}}
            <a href ="{{ route('app-non-cse-generate-excel') }}"><button  type="submit" class="btn btn-success form-control col-sm-1 mt-1 generate" >EXCEL</button></a>
          </div>
          </div>
        <div class="card-body card-dashboard" >
          {{-- <div class="mb-2"style="border-top:1px solid black"></div> --}}
          <div class="table-responsive " >
            <table class="zero-configuration Appnoncse_table" id="table">
                <thead >
                  <tr class="head" style="text-align: center;color:black;">
                    <div class="header" >
                      <div class="image">
                        <i class="fa-solid fa-pen-to-square campuslogo" value="<?=$aes->encrypt($campusinfo[0]->id)?>" style="margin-left:5px;float:right;"></i>
                        <div class="logo">
                          <img src="{{{asset('storage/PMIS/APPNONCSE/image/logo/'.$campusinfo[0]->slsu_logo)}}}" class="logo">
                        </div>
                      </div>
                      <div class="slsu">
                        <div class="">
                          Republic of the Philippines
                          <i class="fa-solid fa-pen-to-square campusinfoEdit" value="<?=$aes->encrypt($campusinfo[0]->id)?>" style="margin-left:5px;"></i><br>
                          SOUTHERN LEYTE STATE UNIVERSITY<br>
                          {{ $campusinfo[0]->address }}<br>
                        </div>
                        <div class="site">
                          Website: <label class="link">{{ $campusinfo[0]->website }}</label><br>
                          Email: <label class="link">{{ $campusinfo[0]->email }}</label><br>
                          Contact Number: <label class="link">{{ $campusinfo[0]->contact_number }}</label>
                        </div>
                        </div>
                      <div class="image2">
                        <i class="fa-solid fa-pen-to-square logoEdit" value="<?=$aes->encrypt($campusinfo[0]->id)?>" style="margin-left:5px;float:right;"></i>
                        <div class="logo">
                          <img src="{{{asset('storage/PMIS/APPNONCSE/image/logo/'.$campusinfo[0]->logo2)}}}" class="logo">
                        </div> 
                      </div>
                    </div>
                    <div class="header2" style="margin-top:2%;">
                      <div class="Title" >
                        University Annual Procurement Plan for FY {{ $Project_title[0]->project_year }}
                      </div>
                    </div>
                  </tr>
                  <tr>
                    <label for="year"> Year: </label>
                    {{-- <select class="custom-select col-sm-1 ml-1 " >
                      <option selected> {{ $Project[0]->project_year }} </option>
                      @foreach ($Project as $Project)
                        <option value="{{ $Project->project_year }}" class="year">{{ $Project->project_year }}</option>
                      @endforeach
                    </select> --}}

                    <div class="btn-group dropright ml-1">
                      <input type="hidden" class="Year" value="{{ $Project_title[0]->project_year }}">
                      <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ $Project_title[0]->project_year }}
                      </button>
                    
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        @foreach ($Project as $Project)
                          <form action="{{ route('app-non-cse-year') }}">
                            <button class="dropdown-item year" type="submit" value="{{ $Project->project_year }}" name="year">{{ $Project->project_year }}</button>
                          </form>
                          {{-- <option value="{{ $Project->project_year }}" >{{ $Project->project_year }}</option> --}}
                        @endforeach
                      </div>
                    </div>
                  </tr>
                  <tr style=" border-right: 1px solid black">
                      <td  rowspan="2"  class="code">CODE (PAP)</td>
                      <td  rowspan="2"  class="procurement">Procurement Project</td>
                      <td  rowspan="2"  class="end_user">PMO / End-User&nbsp;&nbsp;</td>
                      <td  rowspan="2"  class="activity">Is this <br>an Early<br> Procurement Activity? <br>(Yes/No)</td>
                      <td  rowspan="2"  class="mode">MODE OF PROCUREMENT</td>
                      <td  colspan="4" >Schedule for Each Procurem bordfer-right:1px solid black;ent Activity</td>
                      <td  rowspan="2"  class="funds">Source of Funds</td>
                      <td  colspan="3"  class="est">Estimated Budget (PhP)</td>
                      <td  rowspan="2"  class="remarks">Remarks&nbsp;(brief Description of Project)</td>
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
                <tbody id="tbl-content" cellpadding="3" cellspacing="0">
                  <?php
                      $oldCampus = "";
                  ?>
                  @foreach($Categories as $category)
                  <?php
                        if ($oldCampus != $category->campus){
                  ?>
                            <tr >
                              <td colspan="2" class="text-dark text-bold-600 campus">{{ (new GlobalDeclare)->Campus($category->campus) }}</td>
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
                              <td  class="remarks"></td>
                          </tr>
                  <?php 
                            foreach($Categories as $cat){
                                if ($cat->campus == $category->campus){
                  ?>
                                      <tr >
                                        <td></td>
                                        <td class="category">{{ $cat->item_category }}</td>
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
                                        <td class="remarks"></td>
                                      </tr>
                  <?php
                                    $oldProject = "";
                              
                                    foreach($ppmps as $row){
                                      if ($row->item_category == $cat->item_category && $row->campus == $cat->campus){
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
                                                      if ($project->item_category == $cat->item_category && $project->campus == $cat->campus && $project->project_code == $row->project_code){
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
                                          <td  class="remarks"></td>
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
                <tfoot >
                  <?php
                  if($signatures -> isEmpty()){
                  ?>
                  <tr>
                    <td colspan="2" class="last" style="border-bottom : none;"><div class="child">Prepared by:</div></td>
                    <td colspan="8" class="last" style="border-bottom : none;"><div class="child">Recommending Approval:</div></td>
                    <td colspan="4" class="last" style="border-bottom : none; border-right:1px solid black;"><div class="child" style="height:10px">Approved by:</div></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="last" style="border-top : none;">
                      <div class="col-md-12 mb-1 text-center">
                        {{-- <i class="fa-solid fa-plus"></i> --}}
                        <button type="button" class="btn btn-outline-secondary newpreparedby">Add New</button>
                      </div>
                    </td>
                    <td colspan="8" class="last" style="border-top : none;">
                      <div class="col-md-12 mb-1 text-center">
                        {{-- <i class="fa-solid fa-plus"></i> --}}
                        <button type="button" class="btn btn-outline-secondary newrecommendingapproval" id="newrecommendingapproval">Add New</button>
                      </div>
                      <div class="recommending_approval_add" id="recommending_approval_add" >
                        <div class="tfoot-title">
                          Bids & Awards Committee
                        </div>
                        <div class="personel">
                          <div class="persons" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="31"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div> 
                          <div class="persons" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="32"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div> 
                          <div class="persons" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="33"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div>
                        </div>
        
                        <div class="personel2">
                          <div class="persons" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="34"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div> 
                          <div class="persons" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="35"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div> 
                          <div class="persons" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="36"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div>
                        </div>
        
                        <div class="personel3">
                          <div class="person13" >
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="37"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div> 
                          <div class="person13" >
                            {{-- <i class="fa-solid fa-pen-to-square signaturiesEdit" style=""></i> --}}
                            <div class="name">
                              <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="38"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="profession">
                            </div>
                          </div> 
                        </div>
                      </div>
                    </td>
                    <td colspan="4" class="last" style="border-top : none; border-right:1px solid black;   ">
                    
                      <div class="col-md-12 mb-1 text-center">
                        {{-- <i class="fa-solid fa-plus"></i> --}}
                        <button type="button" class="btn btn-outline-secondary newapprovedby">Add New</button>
                      </div>
                    </td>
                  </tr>
                  <?php
                  }else{
                  ?>
                    <tr>
                      <td colspan="2" class="last" style="border-bottom : none;"><div class="child">Prepared by:</div></td>
                      <td colspan="8" class="last" style="border-bottom : none;"><div class="child">Recommending Approval:</div></td>
                      <td colspan="4" class="last" style="border-bottom : none; border-right:1px solid black;"><div class="child" style="height:10px">Approved by:</div></td>
                    </tr>
                    <tr >
                      <td colspan="2" class="last" style="border-top : none;">
                        {{-- <i class="fa-solid fa-pen-to-square signaturiesEdit" style="float: right"></i> --}}
                        <?php
                          if($prepared_by -> isEmpty()){
                        ?>
                          <div class="col-md-12 mb-1 text-center">
                            {{-- <i class="fa-solid fa-plus"></i> --}}
                            <button type="button" class="btn btn-outline-secondary newpreparedby">Add New</button>
                          </div>
                        <?php
                          }else{
                        ?>
                          <div class="person5" style="height: 200px;margin-top:60px">
                            <div class="name">
                              <label class="signatoriesName">{{$prepared_by[0]->Name}}</label>
                              <?php
                                $title = $prepared_by[0]->Title;
                                if($title!="0")
                                {
                                  echo ", $title";
                                }
                              ?>
                              <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($prepared_by[0]->id)?>" style="margin-left:5px;"></i>
                            </div>
                            <div class="profession">
                              <?php
                                  $i =   explode(".",$prepared_by[0]->Profession);
          
                                  for($a = 0 ; $a < count($i) ; $a++){
                                    echo "$i[$a]<br>";
                                  }
                              ?>
                            </div>
                            <div class="bor">
                              Date:_____________
                            </div>
                          </div> 
                        <?php
                          }
                        ?>
                      </td>
                      <td colspan="8" class="last" style="border-top : none;">
                        <?php
                          if($recommending_approval -> isEmpty()){
                        ?>
                          <div class="col-md-12 mb-1 text-center">
                            <button type="button" class="btn btn-outline-secondary newrecommendingapproval" id="newrecommendingapproval">Add New</button>
                          </div>
                          <div class="recommending_approval_add" id="recommending_approval_add" >
                            <div class="tfoot-title">
                              Bids & Awards Committee
                            </div>
                            <div class="personel">
                              <div class="persons" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="31"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div> 
                              <div class="persons" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="32"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div> 
                              <div class="persons" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="33"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div>
                            </div>
            
                            <div class="personel2">
                              <div class="persons" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="34"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div> 
                              <div class="persons" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="35"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div> 
                              <div class="persons" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="36"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div>
                            </div>
            
                            <div class="personel3">
                              <div class="person13" >
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="37"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div> 
                              <div class="person13" >
                                {{-- <i class="fa-solid fa-pen-to-square signaturiesEdit" style=""></i> --}}
                                <div class="name">
                                  <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="38"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="profession">
                                </div>
                              </div> 
                            </div>
                          </div>
                        <?php
                          }else{
                        ?>
                          <div class="tfoot-title">
                              Bids & Awards Committee
                          </div>
                          <div class="personel">
                            <div class="person" >
                              <?php
                              $checker = 0;
                              $Position = 31;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval1){
                                    if($Position == $recommending_approval1->Position){
                                      $checker=1;
                                      $Name = $recommending_approval1->Name;
                                      $Title = $recommending_approval1->Title;
                                      $id = $recommending_approval1->id;
                                      $Profession = $recommending_approval1->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="31"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div> 
                            <div class="person" >
                              <?php
                              $checker = 0;
                              $Position = 32;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval2){
                                    if($Position == $recommending_approval2->Position){
                                      $checker=1;
                                      $Name = $recommending_approval2->Name;
                                      $Title = $recommending_approval2->Title;
                                      $id = $recommending_approval2->id;
                                      $Profession = $recommending_approval2->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="32"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div>
                            <div class="person" >
                              <?php
                              $checker = 0;
                              $Position = 33;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval3){
                                    if($Position == $recommending_approval3->Position){
                                      $checker=1;
                                      $Name = $recommending_approval3->Name;
                                      $Title = $recommending_approval3->Title;
                                      $id = $recommending_approval3->id;
                                      $Profession = $recommending_approval3->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="33"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div>  
                          </div>
          
                          <div class="personel2">
                            <div class="person" >
                              <?php
                              $checker = 0;
                              $Position = 34;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval4){
                                    if($Position == $recommending_approval4->Position){
                                      $checker=1;
                                      $Name = $recommending_approval4->Name;
                                      $Title = $recommending_approval4->Title;
                                      $id = $recommending_approval4->id;
                                      $Profession = $recommending_approval4->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="34"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div> 
                            <div class="person" >
                              <?php
                              $checker = 0;
                              $Position = 35;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval5){
                                    if($Position == $recommending_approval5->Position){
                                      $checker=1;
                                      $Name = $recommending_approval5->Name;
                                      $Title = $recommending_approval5->Title;
                                      $id = $recommending_approval5->id;
                                      $Profession = $recommending_approval5->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="35"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div> 
                            <div class="person" >
                              <?php
                              $checker = 0;
                              $Position = 36;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval6){
                                    if($Position == $recommending_approval6->Position){
                                      $checker=1;
                                      $Name = $recommending_approval6->Name;
                                      $Title = $recommending_approval6->Title;
                                      $id = $recommending_approval6->id;
                                      $Profession = $recommending_approval6->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="36"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div> 
                          </div>
          
                          <div class="personel3">
                            <div class="person3" >
                              <?php
                              $checker = 0;
                              $Position = 37;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval7){
                                    if($Position == $recommending_approval7->Position){
                                      $checker=1;
                                      $Name = $recommending_approval7->Name;
                                      $Title = $recommending_approval7->Title;
                                      $id = $recommending_approval7->id;
                                      $Profession = $recommending_approval7->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="37"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div> 
                            <div class="person3" >
                              <?php
                              $checker = 0;
                              $Position = 38;
                              $Name = "";
                              $Title = "";
                              $id = "";
                              $Profession = "";
                              foreach($recommending_approval as $recommending_approval8){
                                    if($Position == $recommending_approval8->Position){
                                      $checker=1;
                                      $Name = $recommending_approval8->Name;
                                      $Title = $recommending_approval8->Title;
                                      $id = $recommending_approval8->id;
                                      $Profession = $recommending_approval8->Profession;
                                    }
                                  }
                              ?>
                              <?php   
                                if($checker == 1){
                              ?>
                                <div class="name">
                                  <label class="signatoriesName">{{$Name}}</label>
                                  <?php
                                    $title = $Title ;
                                    if($title!="0")
                                    {
                                      echo ", $title";
                                    }
                                  ?>
                                  <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($id)?>" style="margin-left:5px;"></i>
                                </div>
                                <div class="profession">
                                  <?php
                                      $i =   explode(".",$Profession);

                                      for($a = 0 ; $a < count($i) ; $a++){
                                        echo "$i[$a]<br>";
                                      }
                                  ?>
                                </div>
                              <?php
                                }else{
                              ?>
                                  <div class="name">
                                    <button type="button" class="btn btn-outline-secondary form-control add_recommendingapproval" value="38"><i class="fa-solid fa-plus"></i></button>
                                  </div>
                                  <div class="profession">
                                  </div>
                              <?php
                                }
                              ?>
                                  
                            </div> 
                          </div>
                        <?php
                          }
                        ?>
                        {{-- <div class="tfoot-body"> --}}
          
                        {{-- </div> --}}
                      </td>
                      <td colspan="4" class="last" style="border-top : none; border-right:1px solid black;" > 
                        {{-- <i class="fa-solid fa-pen-to-square approvedByEdit" style="float: right"></i> --}}

                        <?php
                        if($approved_by -> isEmpty()){
                      ?>
                        <div class="col-md-12 mb-1 text-center">
                          {{-- <i class="fa-solid fa-plus"></i> --}}
                          <button type="button" class="btn btn-outline-secondary newapprovedby">Add New</button>
                        </div>
                      <?php
                        }else{
                      ?>
                        <div class="person5" style="height: 200px;margin-top:60px">
                          <div class="name">
                            <label class="signatoriesName">{{$approved_by[0]->Name}}</label>
                            <?php
                              $title = $approved_by[0]->Title;
                              if($title!="0")
                              {
                                    echo ", $title";
                              }
                            ?>
                            <i class="fa-solid fa-pen-to-square signaturiesEdit" value="<?=$aes->encrypt($approved_by[0]->id)?>" style="margin-left:5px;"></i>
                          </div>
                          <div class="profession">
                            <?php
                                $i =   explode(".",$approved_by[0]->Profession);

                                for($a = 0 ; $a < count($i) ; $a++){
                                  echo "$i[$a]<br>";
                                }
                            ?>
                          </div>
                          <div class="bor">
                            BOR Resolution No.:______________<br>
                            Date:_____________
                          </div>
                        </div> 
                      <?php
                        }
                      ?>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tfoot>
            </table>
              {{-- @include('pages.bac.generate-app-non-cse.view-modal') --}}
          </div>

        </div>
      </div>
    </section>

  </div>
@include('pages.bac.generate-app-non-cse.edit-campusinfo-modal')
@include('pages.bac.generate-app-non-cse.edit-signatories-modal')
@include('pages.bac.generate-app-non-cse.edit-newprepared-modal')
@include('pages.bac.generate-app-non-cse.edit-newrecommendingapproval-modal')
@include('pages.bac.generate-app-non-cse.edit-newapproval-modal')
@include('pages.bac.generate-app-non-cse.edit-campuslogo-modal')
@include('pages.bac.generate-app-non-cse.edit-otherlogo-modal')

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

{{-- <script>
document.getElementById('downloadexcel').AddEventListener('click', function(){
  var table2excel = new Table2Excel();
  table2excel.export(document.querySelectorAll("#table"));
});
</script> --}}



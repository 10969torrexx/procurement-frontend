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
      border-right: 1px solid black;
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
      width:120px;
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
      <div class="card-content" >
        <?php $campuscount = count($campusCheck); $camp = 0; $endorse = 0; $bac_committee_status = ""; $campusload = "";$project_category="";$project_year=""; $appType="";?>
            @foreach($campusCheck as $campusload)
              <?php $project_category = $campusload->project_category; 
                    $appType = $campusload->app_type; 
                    $bac_committee_status = $campusload->bac_committee_status;
                    $endorse = $campusload->endorse;
                    $project_year = $campusload->project_year;?>
                @if($campusload->campus == 1)
                    <?php $camp++;?>
                @endif
            @endforeach
          <div class="card-header" >
            @if (session('role') == 14)
              <div class="row col-sm-4" >
                @if($bac_committee_status == 0 || $bac_committee_status == 2)
                  <button type="button" class="btn btn-success form-control col-sm-4  approve" value="1" active>Recommend</button>
                @else
                  <button type="button" class="btn btn-success form-control col-sm-4  approve" value="0" active><i class="fa-solid fa-rotate-left"></i></button>
                @endif

                {{-- @if($Categories[0]->bac_committee_status == 1 || $Categories[0]->bac_committee_status == 2)
                <button  type="button" class="btn btn-danger form-control col-sm-4 ml-1 disapprove" value="3">Disapprove</button>
                @else
                <button  type="button" class="btn btn-danger form-control col-sm-4 ml-1 disapprove" value="1"><i class="fa-solid fa-rotate-left"></i></button>
                @endif --}}
              </div>
              <hr>
              <div class="row col-sm-4">
                <p>Status: <span style="color: {{ (new GlobalDeclare)->bac_committee_status_color($bac_committee_status) }};text-transform: uppercase;">{{ (new GlobalDeclare)->bac_committee_status($bac_committee_status) }}</span></p> 
              </div>
            @endif
            <div class="generate" {{-- style="background-color: #bf5279" --}}>
                <div class="dropdown" style="float: right">
                  <span
                    class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                  <div class="dropdown-menu dropdown-menu-left">
                      @if(session('campus') == 1)
                        {{-- <a class="dropdown-item univ_wide" id="univ_wide" href = "{{ route('show-all') }}"> University Wide</a>
                        <a class="dropdown-item main" href = "/bac/app-non-cse">
                          Main Campus Only
                        </a> --}}
                      @endif
                    <form action="{{ route('pres_generatepdf') }}" method="POST">
                    @csrf
                      <input type="hidden" name="project_category" class="project_category" value="{{ $project_category }}">
                      <input type="hidden" name="app_type" class="app_type" value="{{ $appType }}">
                      <input type="hidden" class="Year" name="year" value="{{ $project_year }}">
                      <input type="hidden" class="campusCheck" name="campusCheck" value="{{ $campuscount }}">
                      <a href=""><button  type="submit" class="dropdown-item" ><i class="fa-solid fa-download"></i>&nbsp; PDF</button></a>
                    </form>

                    <button  type="button" class="dropdown-item print" ><i class="fa-solid fa-print"></i>&nbsp; Print</button>
                  </div>
                </div> 
            </div>
          </div>
        <div class="card-body card-dashboard" >
          {{-- <div class="mb-2"style="border-top:1px solid black"></div> --}}
          <div class="table-responsive " >
            <table class="zero-configuration Appnoncse_table" id="table">
              <thead >
                <tr class="head" style="text-align: center;color:black;">
                  <td colspan="14" style="border: none">
                    <div class="header" >
                      @foreach($campusinfo as $campusinfo)
                        <div class="image">
                          <div class="logo">
                            <img src="{{{asset('images/logo/'.$campusinfo->slsu_logo)}}}" class="logo">
                            {{-- <img src="{{('public/images/logo/'.$campusinfo->slsu_logo)}}" class="logo"> --}}
                          </div>
                        </div>
                        <div class="slsu">
                          <div class="">
                            Republic of the Philippines
                            SOUTHERN LEYTE STATE UNIVERSITY<br>
                            {{ $campusinfo->address }}<br>
                          </div>
                          <div class="site">
                            Website: <label class="link">{{ $campusinfo->website }}</label><br>
                            Email: <label class="link">{{ $campusinfo->email }}</label><br>
                            Contact Number: <label class="link">{{ $campusinfo->contact_number }}</label>
                          </div>
                          </div>
                        <div class="image2">
                          <div class="logo">
                            <img src="{{{asset('images/logo/'.$campusinfo->logo2)}}}" class="logo">
                          </div> 
                        </div>
                      @endforeach
                    </div>
                    <div class="header2" style="margin-top:2%;">
                      <div class="Title" >
                        University Annual Procurement Plan for FY {{ $project_year }}
                      </div>
                    </div>
                  </td>
                </tr>
                <tr style=" border-right: 1px solid black">
                    <td  rowspan="2"  class="code">CODE (PAP)</td>
                    <td  rowspan="2"  class="procurement">Procurement Project</td>
                    <td  rowspan="2"  class="end_user">PMO / End-User&nbsp;&nbsp;</td>
                    <td  rowspan="2"  class="activity">Is this <br>an Early<br> Procurement Activity? <br>(Yes/No)</td>
                    <td  rowspan="2"  class="mode">MODE OF PROCUREMENT</td>
                    <td  colspan="4" >Schedule for Each Procurement Activity</td>
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
                              <td colspan="2" class="text-dark text-bold-600 campus">{{ (new GlobalDeclare)->Campus($category->campus) }} Campus</td>
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
                                          <td >{{ $row->procurementName}}</td>
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
                                          <td >{{ number_format(str_replace(",","",$total),2,'.',',')}}</td>
                                          <td >{{ number_format(str_replace(",","",$totalMOOE),2,'.',',')}}</td>
                                          <td >{{ number_format(str_replace(",","",$totalCO),2,'.',',')}}</td>
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
                  <tr>
                    <td colspan="2" class="last" style="border-bottom : none;"><div class="child">Prepared by:</div></td>
                    <td colspan="8" class="last" style="border-bottom : none;"><div class="child">Recommending Approval:</div></td>
                    <td colspan="4" class="last" style="border-bottom : none; border-right:1px solid black;"><div class="child" style="height:10px">Approved by:</div></td>
                  </tr>
                  <tr >
                    <td colspan="2" class="last" style="border-top : none;">
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
                    </td>
                    <td colspan="8" class="last" style="border-top : none;">
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
                              }
                            ?>
                                
                          </div> 
                        </div>
                    </td>
                    <td colspan="4" class="last" style="border-top : none; border-right:1px solid black;" > 
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
{{-- @include('pages.bac.generate-app-non-cse.edit-campusinfo-modal') --}}
{{-- @include('pages.bac.generate-app-non-cse.edit-signatories-modal')
@include('pages.bac.generate-app-non-cse.edit-newprepared-modal')
@include('pages.bac.generate-app-non-cse.edit-newrecommendingapproval-modal')
@include('pages.bac.generate-app-non-cse.edit-newapproval-modal')
@include('pages.bac.generate-app-non-cse.edit-campuslogo-modal')
@include('pages.bac.generate-app-non-cse.edit-otherlogo-modal') --}}

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

<script src="{{asset('js/baccommittee/baccommittee.js')}}"></script>
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



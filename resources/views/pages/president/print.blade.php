<!-- CSS Code: Place this code in the document's head (between the 'head' tags) -->

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
      font-size: 14px;
    }
    tr.body{
        font-size: 12px;
    }
    .est{
      width:50px;
    }
    td.campus {
      font-style: italic;
      font-size: 12px;
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
      font-size: 8px;
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
      width:80px;
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
    img{
      width: 100%;
      height:100%;
    }
    label.link{
      color: dodgerblue;
    }

    div.site{
      text-align: left;
      margin-left: 20%;
    }
    
    tfoot{
      font-size: 9px;
    }
    div.name{
      font-weight:bold;
    }
    div.profession{
      font-size: 9px;
    }
    label.signatoriesName{
      text-transform: uppercase;
      color: black;
    }
    div.logo{
      width: 90%;
      height: 100%;
    }

</style>
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
{{-- <link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}"> --}}

    <div class="header">
        <div class="image">
          <div class="logo">
            <img class="logo" src="{{{asset('images/logo/'.$campusinfo[0]->slsu_logo)}}}" alt="">
            {{-- <img src="{{{asset('storage/PMIS/APPNONCSE/image/logo/'.$campusinfo[0]->slsu_logo)}}}" class="logo"> --}}
          </div>
        </div>
        <div class="slsu">
          <div class="">
            Republic of the Philippines<br>
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
          <img class="logo" src="{{{asset('images/logo/'.$campusinfo[0]->logo2)}}}" alt="">
        </div>
    </div>
    <div class="header2" style="margin-top:60px">
      <div class="Title">
        University Annual Procurement Plan for FY {{ $signatures[0]->Year }}
      </div>
    </div>
    <div class="table-div">
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
                      <td colspan="2"  class="text-dark text-bold-600 campus" style="background: blue;">{{ (new GlobalDeclare)->Campus($category->campus) }}</td>
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
                                <td ></td>
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
              <div class="" style="height: 260px;width:100%">
                <div class="" style="height:30%;width:100% ">Prepared by:</div>
                <div class="" style="height:70%;width:100%;text-align:center">
                  <?php
                      if($prepared_by -> isEmpty()){
                    ?>
                        <div class="name">
                        <label class="signatoriesName">________________</label>
                        </div>
                        <div class="profession">
                        </div>
                  <?php
                      }else{
                  ?>
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
                  <?php      
                      }
                  ?>
                  <div class="" style="margin-top:2%;">
                    Date:_____________
                  </div>
                </div>
              </div>
              {{-- Prepared By:<br>
               <p class="name">MA. DELIA ONG-MANCA</p>
                 <p class="profession"> Secretariat <br/>
                      Date:_____________<br/>
                  </p> --}}
              {{-- <div class="head">Prepared by:</div><br/>
              <div class="approved" >
                <div class="name">
                  MA. DELIA ONG-MANCA
                </div>
                <div class="profession">
                  Secretariat
                </div>
                <div class="bor">
                  Date:_____________
                </div>
              </div>   --}}
            </td>
            <td colspan="8">
              <div class="" style="height:260px;width:100%;">
                <div class="" style="height:10%;width:100% ">Recommending Approval:</div>
                <div class="" style="height:10%;width:100%;text-align:center;font-size: 25px;font-family: Edwardian Script ITC;color:blue">Bids & Awards Committee</div>
                <div class="" style="height:79%;width:100%;text-align:center;margin-top:1%">
                  <div class="" style="height:30%;width:100%;text-align:center;  ">
                    <div class="person" style="width:30%; height:100%;float:left; margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div> 
                    <div class="person" style="width:30%; height:100%;float:left; margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div> 
                    <div class="person" style="width:30%; height:100%;float:right; margin-right:1%">
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
                        <div class="name" style="width:100%; height:20%">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div>
                  </div>

                  
                  <div class="" style="height:30%;width:100%;text-align:center; clear: left; margin-top:1%; ">
                    <div class="person" style="width:32%; height:100%;float:left; margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div> 
                    <div class="person" style="width:32%; height:100%;float:left;margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div> 
                    <div class="person" style="width:32%; height:100%;float:left;margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%;">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%;">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div>
                  </div>

                  
                  
                  <div class="" style="height:30%;width:100%;text-align:center; clear: left; margin-top:1%; overflow: auto;">
                    <div class="person" style="width:48%; height:100%;float:left; margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%;">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%;">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div> 
                    <div class="person" style="width:48%; height:100%;float:left;margin-left:1%">
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
                        <div class="name" style="width:100%; height:20%;">
                          <label class="signatoriesName">{{$Name}}</label>
                          <?php
                            $title = $Title ;
                            if($title!="0")
                            {
                              echo ", $title";
                            }
                          ?>
                        </div>
                        <div class="profession" style="width:100%; height:80%;">
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
                          <div class="name" style="width:100%; height:20%;">
                            ________________
                          </div>
                          <div class="profession" style="width:100%; height:80%;">
                          </div>
                      <?php
                        }
                      ?>
                    </div> 
                  </div>

                </div>
              </div>
            </td>
            <td colspan="4">
              <div class="" style="height: 260px;width:100%">
                <div class="" style="height:30%;width:100% ">Approved By:</div>
                <div class="" style="height:70%;width:100%; text-align:center">
                  <div class="" style="height:70%;width:100%;text-align:center">
                    <?php
                      if($approved_by -> isEmpty()){
                    ?>
                        <div class="name">
                        <label class="signatoriesName">________________</label>
                        </div>
                        <div class="profession">
                        </div>
                    <?php
                      }else{
                    ?>
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
                    <?php      
                      }
                    ?>
                    <div class="" style="margin-top:2%;">
                      BOR Resolution No.:______________<br>
                      Date:_____________
                    </div>
                  </div> 
                </div>
              </div>
          </td>
          </tr>
        </tfoot>
      </table>
  </div>
    <!-- Codes by Quackit.com -->
    
    @section('vendor-scripts')
    <script>
      $(document).ready(function(){
      var table2excel = new Table2Excel();
      table2excel.export(document.querySelectorAll("#table"));
    });
    </script>

    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>
    
    <script src="{{asset('js/bac/table.js')}}"></script>
    <script src="{{asset('js/bac/table2excel.js')}}"></script>
    @endsection
    
    @section('page-scripts')
    <script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
    @endsection 
    
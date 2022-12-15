
@php
    use App\Http\Controllers\AESCipher;
use App\Http\Controllers\GlobalDeclare;
$aes = new AESCipher();
// $global = new GlobalDeclare();
@endphp

@section('content')
<table >
    <thead>
      <tr style=" border-right: 1px solid black">
          <td  rowspan="2"  >CODE (PAP)</td>
          <td  rowspan="2" >Procurement Project</td>
          <td  rowspan="2"  >PMO / End-User</td>
          <td  rowspan="2"  >Is this <br>an Early<br> Procurement Activity? <br>(Yes/No)</td>
          <td  rowspan="2"  >MODE OF PROCUREMENT</td>
          <td  colspan="4"  >Schedule for Each Procurement Activity</td>
          <td  rowspan="2"  >Source of Funds</td>
          <td  colspan="3"  >Estimated Budget (PhP)</td>
          <td  rowspan="2"  >Remarks(brief Description of Project)</td>
      </tr>
      <tr >
          <td  >Advertisement/<br>Posting of IB/REI</td>
          <td  >Submission /<br>Opening of Bids </td>
          <td  >Noticeof  Award</td>
          <td  >ContractSigning</td>
          <td  >Total</td>
          <td  >MOOE</td>
          <td  >CO</td>
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
                  <td colspan="2" >{{ (new GlobalDeclare)->Campus($category->campus) }}</td>
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
      <?php 
                foreach($Categories as $cat){
                    if ($cat->campus == $category->campus){
      ?>
                          <tr >
                            <td></td>
                            <td >{{ $cat->item_category }}</td>
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
                            <tr >
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
    <tfoot></tfoot>
  </table>


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
  }
</style>
<div class="table-responsive">
  <table class="PR">
    <thead class="head1">
      <tr>
        <td class="title" colspan="6" style="border-bottom : none;font-weight:bold;padding-bottom:1%">PURCHASE REQUEST</td>
      </tr>
      <tr>
        <td  style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;">Entity Name:</td>
        <td colspan="2" style="border-bottom : none;border-top : none;border-right : none"><div class="" style="margin-top:1%;border-bottom:1px solid black;text-align:left;"> Southern Leyte State University - {{ $campusinfo[0]->address }}</div></td>
        <td colspan="2" style="font-weight:bold;border-bottom : none;border-top : none;border-right : none">Fund Cluster:</td>
        <td style="border-top: none"><div class="" style="margin-top:2%;border-bottom:1px solid black;text-align:left">{{$ppmpss[0]->fund_source  }}</div></td>
      </tr>
      <tr>
        <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;"> Office / Section:</td>
        <td colspan="4" style="border-bottom : none;border-top : none;text-align:left;">
          <div class="col-sm-12" >
            <div style="float:left;height:100%;width:50%;font-weight:bold;">PPDMO</div>
            <div style="float:right;width:50%">
                <div style="width:20%;float:left;font-weight:bold;" >PR No.</div>
                <div style="width:70%;float:left;border-bottom:1px solid black">2022-09-0480</div>
            </div>
          </div>
        </td>
        <td style="border-bottom: none">
          <div style="width:20%;float:left;font-weight:bold;" >Date:</div>
          <div style="width:80%;float:left;border-bottom:1px solid black">2022-09-0480</div>
        </td>
      </tr>
      <tr>
        <td style="font-weight:bold;border-left:1px solid black;border-bottom : none;border-top : none;border-right : none;text-align:left;"></td>
        <td colspan="4" style="border-bottom : none;border-top : none;text-align:left;">
          <div class="col-sm-12" >
            <div style="float:left;width:49%;border-bottom:1px solid black;border-right:1px solid black;height:17px;"></div>
            <div style="float:right;width:50%">
                <div style="width:70%;float:left;font-weight:bold;" >Responsibility Center Code: </div>
                <div style="width:20%;float:left;border-bottom:1px solid black;margin-top:3%"></div>
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
      <?php $i=0; ?>
      @foreach($ppmpss as $ppmps)
      <?php $i++; ?>
      <tr>
        <td class="first" style="text-align: right;font-style: italic;">{{ $i }}</td>
        <td style="text-align: center">{{ $ppmps->unit_of_measurement }}</td>
        <td style="text-align: left">{{ $ppmps->item_description }}</td>
        <td style="text-align: center">{{ $ppmps->quantity  }}</td>
        <td style="text-align: right">{{ $ppmps->unit_price }}</td>
        <td style="text-align: right">{{ $ppmps->estimated_price }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td class="first"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td  style="text-align: right">9000000</td>
      </tr>
      <tr>
        <td class="first" style="border-right:none;text-align:left;text-align:left;">Purpose: </td>
        <td colspan="5" >
          <div class="row" >
            <div class="" style="border-bottom:1px solid black">srtstg</div>
            <div class="">srtstg</div>
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
        <td colspan="2" style="border-right: none;border-top: none;text-align:center;font-weight:bold;" >JOSE V. PALOGOD</td>
        <td colspan="3" style="border-top: none;text-align:center;font-weight:bold;">PROSE IVY G. YEPES, EdD</td>
      </tr>
      <tr>
        <td style="border-left:1px solid black; border-top: none;border-right: none;font-weight:bold;">Designation:</td>
        <td colspan="2" style="border-right: none;text-align:center;">PPDMO - Designate</td>
        <td colspan="3" style="text-align:center;">University President</td>
      </tr>
      <tr><td colspan="6" style="border-left: 1px solid black;height:20px"></td></tr>
    </tfoot>
  </table>
</div>
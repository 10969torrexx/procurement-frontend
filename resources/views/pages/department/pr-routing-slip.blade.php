<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Routing Slip')

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
                <div class="col-md-12" style="padding-top:30px;padding-left:190px">
                    <a href = "{{ route('routing_slip') }}" class = "btn btn-primary mr-1 mb-1"><i class="bx bx-left-arrow"></i> Back</a>
                
                </div>
                <div class="card-header">
                    <h4 class="card-title" style="text-align: center; font-weight: bold;">ROUTING SLIP (PROCUREMENT PROCESS)</h4>
                </div>
               

                @foreach($response as $oldData)
                @endforeach
                
                <div class="card-content">
                    <table class="top">
                    @foreach($purchase_request as $data)
                        <thead>
                            <tr class="topp" style="text-align: left;line-height:22px;font-size:15px">
                                <td  style="width: 70px;">PR NO.:</td>
                                <td  style="width: 185px;border-bottom: 1px solid black;" class="pr_no">{{ $data->pr_no }}</td>
                                <td  style="width: 40px;padding-left: 60px">Date:</td>
                                <td  style="width: 185px;padding-left: 10px; border-bottom: 1px solid black;">{{ date('M. j, Y', strtotime($data->created_at)) }}</td>
                                <td  style="width: 145px;padding-left: 60px">End-User:</td>
                                <td  style="width: 185px;border-bottom: 1px solid black;">{{ $data->name }}</td>
                                <td  style="width: 155px;padding-left: 60px">Control No.:</td>
                                <td  style="width: 185px;border-bottom: 1px solid black;"></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 20px"></td>
                            </tr>
                        </thead>
                    @endforeach
                    </table>
                    <table class="ppmp" style=" line-height:22px;font-size:15px">
                        <thead>
                            <tr class="header" style=" border-top: 1px solid black;">
                                <td  colspan="2" style="width: 500px;">OFFICE / ACTIVITY</td>
                                <td  style="width: 80px;">CHECK FOR VERIFICATION</td>
                                <td  style="width: 100px;">DATE & TIME RECEIVED</td>
                                <td  style="width: 100px;">DATE & TIME RELEASED</td>
                                <td  style="width: 200px;">REMARKS</td>
                                <td  style="width: 150px;">NAME & SIGNATURE</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">BAC Secretariat</td>
                                <td  style="width: 80px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Receive approved PR.</td>
                                <td  class="blank" style="width: 80px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox1" value="1"> </td>
                                <td  class="blank" style="width: 100px;" ><div class="received1 text-center"></div></td>
                                <td  class="blank" style="width: 100px;" ><div class="released1 text-center"></div></td>
                                <td  class="blank" style="width: 200px;"><div class="remark1 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name1 text-center"></td>
                            </tr>
                            <tr  style="">
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px; " >Attach BAC Resolution and facilitate signatures of the BAC members and forward to HOPE.</td>
                                <td  class="blank" style="width: 100px;text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox2" value="2" > </td>
                                <td  class="blank" style="width: 100px;"><div class="received2 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released2 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark2 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name2 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">HoPE or Authorized Representative</td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Approve BAC Resolution</td>
                                <td  class="blank" style="width: 100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox3" value="3"></td>
                                <td  class="blank" style="width: 100px;"><div class="received3 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released3 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark3 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name3 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">BAC Secretariat</td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Post PR in PhilGEPS for PRs amounting to 50,000.00 and above.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox4" value="4"></td>
                                <td  class="blank" style="width: 100px;"><div class="received4 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released4 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark4 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name4 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Procurement Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Prepare Agency Purchase Request.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox5" value="5"></td>
                                <td  class="blank" style="width: 100px;"><div class="received5 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released5 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark5 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name5 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Prepare RFQ and distribute/email to suppliers.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox6" value="6"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received6 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released6 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark6 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name6 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Retrieve RFQs from suppliers.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox7" value="7"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received7 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released7 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark7 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name7 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">BAC Secretariat</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Prepare Abstract of Bids as Read/Calculated form and assist the BAC in the opening.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox8" value="8"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received8 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released8 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark8 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name8 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">BAC Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Open & evaluate RFQs.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox9" value="9"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received9 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released9 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark9 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name9 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Check mandatory requirements.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox10" value="10"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received10 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released10 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark10 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name10 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Sign abstract of bids.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox11" value="11"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received11 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released11 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark11 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name11 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">HoPE</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Approve abstract of bids.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox12" value="12"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received12 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released12 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark12 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name12 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Procurement Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Prepare Purchase Order and forward to Accounting Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox13" value="13"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received13 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released13 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark13 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name13 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Accounting Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Pre-audit PO & its supporting documents.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox14" value="14"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received14 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released14 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark14 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name14 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Review and sign PO as to its funds availability.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox15" value="15"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received15 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released15 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark15 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name15 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">HoPE or Authorized Representative</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Approve Purchase Order</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox16" value="16"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received16 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released16 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark16 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name16 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Procurement Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Serve PO to winning suppliers/bidders for confirmation.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox17" value="17"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received17 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released17 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark17 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name17 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Furnish copy of confirmed PO to COA and Supply Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox18" value="18"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received18 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released18 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark18 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name18 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">BAC Secretariat</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Post confirmed POs of posted PRs in PhilGEPS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox19" value="19"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received19 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released19 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark19 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name19 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Procurement Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Follow-up deliveries.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox20" value="20"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received20 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released20 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark20 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name20 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Submit complete documents to Supply Office after complete delivery/acceptance of the items.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox21" value="21"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received21 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released21 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark21 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name21 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;" >Supply and Property Management</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Receive approved Purchase Order with mandatory supporting documents with complete delivery (AMP).</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox22" value="22"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received22 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released22 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark22 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name22 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Prepare Inspection and acceptance report.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox23" value="23"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received23 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released23 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark23 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name23 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Prepare RIS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox24" value="24"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received24 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released24 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark24 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name24 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Prepare ICS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox25" value="25"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received25 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released25 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark25 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name25 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Prepare PAR.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox26" value="26"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received26 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released26 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark26 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name26 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">6</td>
                                <td  class="activity" style="width: 500px;">Prepare Sticker.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox27" value="27"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received27 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released27 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark27 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name27 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">7</td>
                                <td  class="activity" style="width: 500px;">Prepare Voucher.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox28" value="28"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received28 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released28 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark28 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name28 text-center"></td>
                            </tr>
                            <tr>
                                <td  class="numbering" style="width: 3px;">8</td>
                                <td  class="activity" style="width: 500px;">Submit voucher to Budget Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox29" value="29"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received29 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released29 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark29 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name29 text-center"></td>
                            </tr>

                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Budget Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Receive & Log documents for Obligation.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox30" value="30"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received30 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released30 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark30 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name30 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Make initial pre audit then determine the fund source / Assign Obligation no.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox31" value="31"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received31 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released31 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark31 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name31 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Facilitate signature of the ORS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox32" value="32"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received32 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released32 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark32 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name32 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Signs the ORS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox33" value="33"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received33 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released33 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark33 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name33 text-center"></td>
                            </tr>
                            <tr>
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Release the documents with signed ORS to the Accountinbgs' pre audit section.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox34" value="34"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received34 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released34 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark34 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name34 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Accounting Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Check the disbursement vouchers as to its computation and completeness of its supporting</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox35" value="35"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received35 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released35 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark35 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name35 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Input tax withheld in the BIR Data Entry System.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox36" value="36"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received36 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released36 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark36 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name36 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Post tax withheld in the supplier's ledger cards.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox37" value="37"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received37 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released37 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark37 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name37 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Print and attach BIR Forms 2307 to the disbursement voucher.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox38" value="38"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received38 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released38 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark38 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name38 text-center"></td>
                            </tr>
                            <tr>
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Review and certify the propriety of the amount claimed and the supporting documents attached.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox39" value="39"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received39 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released39 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark39 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name39 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">6</td>
                                <td  class="activity" style="width: 500px;">Prepare and sign the JEV.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox40" value="40"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received40 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released40 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark40 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name40 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">7</td>
                                <td  class="activity" style="width: 500px;">Forward to the Approving Authority for signature and retrieval of the DV.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox41" value="41"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received41 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released41 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark41 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name41 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">8</td>
                                <td  class="activity" style="width: 500px;">Forward to the Cashier's Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox42" value="42"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received42 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released42 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark42 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name42 text-center"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  class="office_title" colspan="2" style="width: 500px;">Cash Office</td>
                                <td  style="width:100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">1</td>
                                <td  class="activity" style="width: 500px;">Receive approved DV.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox43" value="43"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received43 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released43 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark43 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name43 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Segregate DV as to fund cluster.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox44" value="44"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received44 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released44 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark44 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name44 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Prepare and Issue check.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox45" value="45"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received45 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released45 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark45 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name45 text-center"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Forward check to the signatories.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox46" value="46"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received46 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released46 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark46 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name46 text-center"></td>
                            </tr>
                            <tr class="last_one">
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Pay either check, cash or through bank account to payees.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" class="myCheckbox" id="myCheckbox47" value="47"> </td>
                                <td  class="blank" style="width: 100px;"><div class="received47 text-center"></td>
                                <td  class="blank" style="width: 100px;"><div class="released47 text-center"></td>
                                <td  class="blank" style="width: 200px;"><div class="remark47 text-center"></td>
                                <td  class="blank" style="width: 150px;"><div class="name47 text-center"></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 50px"></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('pages.department.routing-slip-modal')


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

<script>
    // var checkboxes = $('input[type="checkbox" class="myCheckbox"]').length;
    // var checkboxes = $('input:checkbox:checked').length;
    // var checkboxes = $('input:checkbox:not(":checked")').length;
    // today = new Date();
    // var dd = today.getDate();
    // var mm = String(today.getMonth()+1).padStart(2, '0'); //As January is 0.
    // var yyyy = today.getFullYear();
    // var date = yyyy+'-'+mm+'-'+'01';
    // document.getElementById("received").min = date;
    // $('#received').val(date); 
    // console.log(checkboxes);

</script>

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

    input.myCheckbox{
        width: 20px;
        height: 20px;
    }
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
        padding-left: 10px;
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
    .office_title{
        padding-left: 10px
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
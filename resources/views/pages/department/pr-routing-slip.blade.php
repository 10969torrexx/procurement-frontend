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
               

                <div class="card-content">
                    <table class="top">
                    @foreach($purchase_request as $data)
                        <thead>
                            <tr class="topp" style="text-align: left;line-height:22px;font-size:15px">
                                <td  style="width: 70px;">PR NO.:</td>
                                <td  style="width: 185px;border-bottom: 1px solid black;">{{ $data->pr_no }}</td>
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
                                <td  class="blank" style="width: 80px; text-align:center; vertical-align: middle;"><input type="checkbox"  name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr  style="">
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px; " >Attach BAC Resolution and facilitate signatures of the BAC members and forward to HOPE.</td>
                                <td  class="blank" style="width: 100px;text-align:center; vertical-align: middle;"><input type="checkbox" id="checkbox"  name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width: 100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Prepare RFQ and distribute/email to suppliers.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Retrieve RFQs from suppliers.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Check mandatory requirements.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Sign abstract of bids.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Review and sign PO as to its funds availability.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Furnish copy of confirmed PO to COA and Supply Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Submit complete documents to Supply Office after complete delivery/acceptance of the items.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Prepare Inspection and acceptance report.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Prepare RIS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Prepare ICS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Prepare PAR.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">6</td>
                                <td  class="activity" style="width: 500px;">Prepare Sticker.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">7</td>
                                <td  class="activity" style="width: 500px;">Prepare Voucher.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr>
                                <td  class="numbering" style="width: 3px;">8</td>
                                <td  class="activity" style="width: 500px;">Submit voucher to Budget Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Make initial pre audit then determine the fund source / Assign Obligation no.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Facilitate signature of the ORS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Signs the ORS.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr>
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Release the documents with signed ORS to the Accountinbgs' pre audit section.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Input tax withheld in the BIR Data Entry System.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Post tax withheld in the supplier's ledger cards.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Print and attach BIR Forms 2307 to the disbursement voucher.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr>
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Review and certify the propriety of the amount claimed and the supporting documents attached.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">6</td>
                                <td  class="activity" style="width: 500px;">Prepare and sign the JEV.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">7</td>
                                <td  class="activity" style="width: 500px;">Forward to the Approving Authority for signature and retrieval of the DV.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">8</td>
                                <td  class="activity" style="width: 500px;">Forward to the Cashier's Office.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
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
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">2</td>
                                <td  class="activity" style="width: 500px;">Segregate DV as to fund cluster.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">3</td>
                                <td  class="activity" style="width: 500px;">Prepare and Issue check.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr >
                                <td  class="numbering" style="width: 3px;">4</td>
                                <td  class="activity" style="width: 500px;">Forward check to the signatories.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr class="last_one">
                                <td  class="numbering" style="width: 3px;">5</td>
                                <td  class="activity" style="width: 500px;">Pay either check, cash or through bank account to payees.</td>
                                <td  class="blank" style="width:100px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="received" name="received" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 100px;"><input type="date" id="released" name="released" value="" min="2023-02-16"></td>
                                <td  class="blank" style="width: 200px;"></td>
                                <td  class="blank" style="width: 150px;"></td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 50px"></td>
                            </tr>
                            {{-- <tr class="office" style=" border-top: 1px solid black;">
                                <td  style="width: 500px;">HoPe or Authorized Representative</td>
                                <td  style="width: 8px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr class="activity3">
                                <td  style="width: 500px;">1 &emsp;Approve BAC Resolution</td>
                                <td  style="width: 8px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr class="office" style=" border-top: 1px solid black;">
                                <td  style="width: 500px;">BAC SECRETARIAT</td>
                                <td  style="width: 8px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr>
                            <tr class="activity4">
                                <td  style="width: 500px;">1 &emsp;Post PR in PhilGEPS for PRs amounting to 50,000.00 and above</td>
                                <td  style="width: 8px; text-align:center; vertical-align: middle;"><input type="checkbox" name="query_myTextEditBox"> </td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 100px;"></td>
                                <td  style="width: 200px;"></td>
                                <td  style="width: 150px;"></td>
                            </tr> --}}
                            {{-- <tr>
                                <td >BUDGET</td>
                                <td style="width: 1px;">JAN</td>
                                <td style="width: 1px;">FEB</td>
                                <td style="width: 1px;">MAR</td>
                                <td style="width: 1px;">APR</td>
                                <td style="width: 1px;">MAY</td>
                                <td style="width: 1px;">JUN</td>
                                <td style="width: 1px;">JULY</td>
                                <td style="width: 1px;">AUG</td>
                                <td style="width: 1px;">SEPT</td>
                                <td style="width: 1px;">OCT</td>
                                <td style="width: 1px;">NOV</td>
                                <td style="width: 1px;">DEC</td>
                            </tr> --}}
                            
                            {{-- <tr style=" border-top: 1px solid black;">
                                <td  class="office" rowspan="12" style="width: 500px;">BAC SECRETARIAT</td>
                                <td  rowspan="2" style="width: 8px;"></td>
                                <td  colspan="2" rowspan="2" style="width: 100px;"></td>
                                <td  colspan="2" rowspan="2" style="width: 100px;"></td>
                                <td  rowspan="2" style="width: 200px;"></td>
                                <td  rowspan="2" style="width: 150px;"></td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- @include('pages.department.preview-PR-modal') --}}
{{-- @include('pages.department.view-pr') --}}


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
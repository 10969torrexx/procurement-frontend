@php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
@endphp
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Submit PPMP')

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">
@endsection
@section('content')
<!-- Zero configuration table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   <div class="row p-1 border-bottom">
                        <h3>My PPMP</h3>
                   </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                       <div class="row justify-content-center">
                            <div class="row justify-content-center col-sm-8">
                                <div class="form-group col-sm-4">
                                    <label for="">Select Year</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">-- Choose Option --</option>
                                    </select>
                                </div>
                                <div class="row col-sm-8">
                                    <div class="col-sm-6">
                                        <label for="">Select Project Code</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">-- Choose Option --</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-success mt-2">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                  <div class="card-header p-1">
                     <h4 class="text-primary border-bottom pb-1">
                        <strong> Announcements </strong>
                     </h4>
                   
                     
                     <table class="text-center" style="width: 100%; line-height:22px;font-size:15px">
                        <thead>
                            <tr style="border-bottom: 1px solid black; border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                <td  rowspan="2" style="width: 5%;border-right: 1px solid black">CODE</td>
                                <td  rowspan="2" style="width: 30%;border-right: 1px solid black">GENERAL DESCRIPTION</td>
                                <td  colspan="2" rowspan="2" style="width: 10%;border-right: 1px solid black">QTY/SIZE</td>
                                <td  style="width: 16%;border-right: 1px solid black">ESTIMATED BUDGET</td>
                                <td  rowspan="2" style="width: 16%;border-right: 1px solid black">MODE OF PROCUREMENT</td>
                                <td  colspan="12"style="width: 16%;border-left: 1px solid black">SCHEDULE/MILESTONE OF ACTIVITIES</td>
                            </tr>
                            <tr style="border-bottom: 1px solid black;font-weight: 900;border-left: 1px solid black;border-right: 1px solid black">
                                <td style="width: 18%;border-right: 1px solid black">BUDGET</td>
                                <td style="width: 1%;border-left: 1px solid black">JAN</td>
                                <td style="width: 1%;border-left: 1px solid black">FEB</td>
                                <td style="width: 1%;border-left: 1px solid black">MAR</td>
                                <td style="width: 1%;border-left: 1px solid black">APR</td>
                                <td style="width: 1%;border-left: 1px solid black">MAY</td>
                                <td style="width: 1%;border-left: 1px solid black">JUN</td>
                                <td style="width: 1%;border-left: 1px solid black">JULY</td>
                                <td style="width: 1%;border-left: 1px solid black">AUG</td>
                                <td style="width: 1%;border-left: 1px solid black">SEPT</td>
                                <td style="width: 1%;border-left: 1px solid black">OCT</td>
                                <td style="width: 1%;border-left: 1px solid black">NOV</td>
                                <td style="width: 1%;border-left: 1px solid black">DEC</td>
                            </tr>
                        </thead>
                        <tbody>
                                <tr style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                    {{-- this table data is for the project code of the ppmp | 2022-1 --}}
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600">fgxhgfc</td>
                                    {{-- this table data is for the general description of the project of the ppmp | ICT FUNDS  --}}
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600">xgfhxgfh</td>
                                    {{-- this table data is for the quantity of the project of the ppmp | ICT FUNDS  --}}
                                    <td style="border-left: 1px solid black;"></td>
                                    {{-- this table data is for the size of the project of the ppmp | ICT FUNDS  --}}
                                    <td style="border-left: 1px solid black;"></td>
                                    {{-- this table data is for the estimated budge for each item of the project of the ppmp --}}
                                    <td style="border-left: 1px solid black;">P 1,0000.00</td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                </tr>
                                <tr style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600">fgxhgfc</td>
                                    <td style="border-right: 1px solid black; font-size: 11px" class="text-dark text-bold-600">xgfhxgfh</td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black;"></td>
                                </tr>
                     </tbody>
                    </table>
      
                  </div>
                </div>
              </div>
      
        </div>
    </div>
    
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endsection

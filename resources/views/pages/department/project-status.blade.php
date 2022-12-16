{{-- Torrexx Additionals --}}
@include('pages.department.timeline-style')
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $status = 0;
@endphp

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Create PPMP')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/wizard.css')}}">
@endsection
@section('content')
<style>
   
</style>
<!-- Zero configuration table -->
<section id="horizontal-vertical">
    <div class="row">
        {{-- end --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                    <h4 class="text-primary border-bottom pb-1">
                       <strong>Project Title</strong>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive col-12 container">
                        <table class="table zero-configuration " id="item-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <!-- <th>Project Code</th> -->
                                    <th>Project Title</th>
                                    <th>Year</th>
                                    <th>IMmEDIATE SUPERVISOR</th>
                                    <th>Project Type</th>
                                    <th>Fund Source</th>
                                    <th>status</th>
                                    <th>Date Added</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                    @for ($i = 0; $i < count(json_decode($project_titles)); $i++)
                                        <tr>
                                            <td>{{ ($i) + 1 }}</td>
                                            <!-- <td>{{ json_decode($project_titles)[$i]->project_code }}</td> -->
                                            <td>{{ json_decode($project_titles)[$i]->project_title }}</td>
                                            <td>{{ json_decode($project_titles)[$i]->project_year }}</td>
                                            <td>{{ json_decode($project_titles)[$i]->immediate_supervisor }}</td>
                                            <td>{{ json_decode($project_titles)[$i]->project_type }}</td>
                                            <td>{{ json_decode($project_titles)[$i]->fund_source }}</td>
                                            <td>{{ (new GlobalDeclare)->status(json_decode($project_titles)[$i]->status) }}</td>
                                            <td> {{ explode('-', date('j F, Y-', strtotime($project_titles[$i]->updated_at)))[0] }}</td>
                                        </tr>
                                    @endfor
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                    </div>
                    
                </div>
              </div>
        </div>
    </div>

    {{-- the project timeline --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                    <h4 class="text-primary border-bottom pb-1">
                        <strong>Project Timeline</strong>
                    </h4>
                </div>
                <div class="card-body" style="padding-bottom: 70px;">
                    @for ($i = (count($project_timeline) - 1); $i >= 0; $i--)
                        <div class="row">
                            <div class="col-2 d-flex justify-content-end">
                                <span class="datetime">
                                    <i style="color: rgb(122, 120, 120)">{{ explode('-', date('M j, Y', strtotime($project_timeline[$i]->created_at)))[0] }}</i><br>
                                    <i style="color: rgb(122, 120, 120)" class="d-flex justify-content-end">{{ explode('-', date('H:m', strtotime($project_timeline[$i]->created_at)))[0] }}</i>
                                </span>
                            </div>
                            <div class="col-1 d-flex justify-content-center">
                                {{-- Creating Logo --}}
                                @if ($i == count($project_timeline) - 1)
                                    <div class="events mt-1">
                                        <div class="circle" style="height: 20px; width: 20px;">
                                            <div class="icon">
                                                <h3 class="text-white" style="left: 42%;">
                                                    
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="events mt-1">
                                        <div class="circle" style="background-color: rgb(223, 219, 219)">
                                            <div class="icon">
                                                <h3 class="text-white">
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-9 border-bottom">
                                
                                @if ($i == (count($project_timeline) - 1) )
                                    <h5 class="text-primary">
                                        <strong>
                                            {{ (new GlobalDeclare)->Status($project_timeline[$i]->status) }}
                                        </strong>
                                    </h5>
                                @else
                                    <h5 class="">
                                        <strong style="color: rgb(151, 150, 150)">
                                            {{ (new GlobalDeclare)->Status($project_timeline[$i]->status) }}
                                        </strong>
                                    </h5>
                                @endif
                                @if ($i == (count($project_timeline) - 1) )
                                    <p class="text-primary">
                                        {{$project_timeline[$i]->remarks }}
                                    </p>
                                @else
                                    <p style="color: gray">
                                        {{$project_timeline[$i]->remarks }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    {{-- the project timeline --}}
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                    <h4 class="text-primary border-bottom pb-1">
                       <strong> Project Item</strong>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item name</th>
                                    <th>Item Category</th>
                                    <th>Item Description</th>
                                    <th>quantity</th>
                                    <th>unit price</th>
                                    <th>estimated price</th>
                                    <th>mode procurement</th>
                                    <th>status</th>
                                    <th>expected date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                @for ($i = 0; $i < count($ppmp_response); $i++)
                                    <tr>
                                        <td>{{ ($i) + 1 }}</td>
                                        <td>{{ $ppmp_response[$i]->item_name }}</td>
                                        <td>{{ $ppmp_response[$i]->item_category }}</td>
                                        <td>{{ $ppmp_response[$i]->item_description }}</td>
                                        <td>{{ $ppmp_response[$i]->quantity }}</td>
                                        <td>₱{{ number_format($ppmp_response[$i]->unit_price,2,'.',',')  }}</td>
                                        <td>₱{{ number_format($ppmp_response[$i]->estimated_price,2,'.',',')  }}</td>
                                        <td>{{ $ppmp_response[$i]->mode_of_procurement }}</td>
                                        <td>{{ (new GlobalDeclare)->status($ppmp_response[$i]->status) }}</td>
                                        <td>{{ (new GlobalDeclare)->Month($ppmp_response[$i]->expected_month) }}</td>
                                    </tr>
                                @endfor
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                  </div>
                </div>
              </div>
        </div>
    </div>
    
</section>
{{-- Torrexx | Code not mine --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @section('vendor-scripts')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
    {{-- <script src="{{asset('js/bac/item.js')}}"></script> --}}
    @endsection
    @section('page-scripts')
    <script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/jquery.steps.min.js')}}"></script>
    <script src="{{asset('js/admin/account.js')}}"></script>
    @endsection
    {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script> --}}
{{-- Torrexx | Code not mine --}}
@endsection

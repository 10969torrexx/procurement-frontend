@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


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
                    <div class="card-body card-dashboard" >
                        <div class="table-responsive">
                                    
                            <table class="table zero-configuration item-table" id="item-table">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Project Code</th>
                                    <th>Project Procurement</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
    
                            
                            @foreach ($data as $line)
                                    {{-- @for ($i = 0; $i < count($data->0); $i++) --}}
                            <tbody>
                                <tr>
                                    <td>{{ $line->department_name }}</td>
                                    <td>{{ $line->project_code }}</td>
                                    <td>{{ $line->project_title }}</td>
                                    <td>Php {{  number_format($line->Total,2,'.',',') }}</td>
                                    <td>
                                        {{-- <a href="" class="view" style="background-color: aquamarine"><i class="fa-regular fa-eye"  title="View" href=""></i> </a> --}}
                                        <button type="button" class="btn btn-outline-secondary view"  href="<?=$aes->encrypt($line->id)?>" >view</button>
                                        {{-- <button class="btn-success view" data-toggle = "modal" id="view_modal"></button> --}}
                                    </td>
                                    
                                </tr>
                            </tbody>
                            @endforeach
                            </table>
                        </div>
                    </div>
                </div>
        </section>
                            @include('pages.admin.view_approved_ppmp_modal')
                            {{-- @include('pages.bac.ppmp.loading') --}}
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('js/admin/approvedppmp.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>




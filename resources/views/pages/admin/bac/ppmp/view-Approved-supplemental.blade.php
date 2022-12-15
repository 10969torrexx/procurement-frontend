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
                            @foreach ($data as $data)

                            
                            {{-- @foreach ($data2 as $data2) --}}
                                    {{-- @for ($i = 0; $i < count($data[); $i++) --}}
                            <tbody>
                                <tr>
                                    <td>{{ $data->department_name }}</td>
                                    <td>{{ $data->project_code }}</td>
                                    <td>{{ $data->project_title }}</td>
                                    <td>Php {{  number_format($data->Total,2,'.',',') }}</td>
                                    {{-- <td></td> --}}
                                    <td>
                                        {{-- <a href="" class="view" style="background-color: aquamarine"><i class="fa-regular fa-eye"  title="View" href=""></i> </a> --}}
                                        <button type="button" class="btn btn-outline-secondary view"  href="<?=$aes->encrypt($data->project_code)?>" data-toggle = "<?=$aes->encrypt($data->employee_id)?>" data-id="<?=$aes->encrypt($data->department_id)?>">view</button>
                                        {{-- <button class="btn-success view" data-toggle = "modal" id="view_modal"></button> --}}
                                    </td>
                                    
                                </tr>
                                {{-- @include('pages.bac.edit-item-modal') --}}
                            </tbody>
                                    {{-- @endfor --}}
                                    {{-- @endforeach --}}
                            @endforeach
                            {{-- <tfoot>
                                <tr>
                                    <th>Department</th>
                                    <th>Project Code</th>
                                    <th>Project Procurement</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
        </section>
                            @include('pages.bac.ppmp.view-Approved-Supplemental-modal')
                            @include('pages.bac.ppmp.loading')
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('js/bac/approvedsupplemental.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>



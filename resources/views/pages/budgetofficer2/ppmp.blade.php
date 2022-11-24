@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','New PPMP Request')
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
        <div class="card">
            <section id="basic-datatable">
                    <div class="card-content">
                        <div class="card-body card-dashboard" >
                            <div class="table-responsive">
                                        
                                <table class="table zero-configuration item-table" id="item-table">
                                <thead>
                                    <tr>
                                        <th>Project Code</th>
                                        <th>Project Procurement</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($ppmp as $data)
                                        {{-- @for ($i = 0; $i < count($data->0); $i++) --}}
                                <tbody>
                                    <tr>
                                        <td>{{ $data->project_code }}</td>
                                        <td>{{ $data->project_title }}</td>
                                        <?php
                                        if($data->status == 1)
                                        {
                                            ?>
                                        <td style="text-align: left; color:blue;">Pending</td>
                                        <?php
                                        }
                                        elseif ($data->status == 2) {
                                        ?>
                                        <td style="text-align: left; color:green;">Approved</td>
                                        <?php
                                        }elseif($data->status == 3){
                                        ?>
                                        <td style="text-align: left; color:red;">Disapproved</td>
                                        <?php
                                        }
                                        ?>
                                        <td>
                                            <form action="{{ route('show-ppmp') }}" method="post">
                                                @csrf
                                                <input type="text" id="project_code12" class=" form-control d-none" name="project_code" value="<?=$aes->encrypt($data->id)?>">
                                                {{-- <input type="text" class="form-control d-none" name="employee_id" value="<?=$aes->encrypt($data->employee_id)?>">
                                                <input type="text" class="form-control d-none" name="department_id" value="<?=$aes->encrypt($data->department_id)?>">
                                                <input type="text" class="form-control d-none" name="item_status" value="<?=$aes->encrypt(0)?>"> --}}
                                                <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                            </form>
                                        </td>
                                        {{-- <td>{{ $data->project_code }}</td>
                                        <td>{{ $data->project_title }}</td> --}}
                                        {{-- <td>
                                            <a href="" class="view" style="background-color: aquamarine"><i class="fa-regular fa-eye"  title="View" href=""></i> </a> --}}
                                            {{-- <button type="button" class="btn btn-outline-secondary view"  href="<?=$aes->encrypt($data->id)?>" data-toggle = "modal" id="view_modal">view</button> --}}
                                            {{-- <button class="btn-success view" data-toggle = "modal" id="view_modal"></button> 
                                        </td>--}}
                                        
                                    </tr>
                                    {{-- @include('pages.bac.edit-item-modal') --}}
                                </tbody>
                                @endforeach
                                        {{-- @endfor --}}
                                {{-- <tfoot>
                                    <tr>
                                        <th>Project Code</th>
                                        <th>Project Procurement</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot> --}}
                                </table>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
                            {{-- @include('pages.supervisor.supervisor_ppmp_modal') --}}
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('js/bac/supervisor.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

{{-- @include('pages.supervisor.supervisor_check_ppmp') --}}



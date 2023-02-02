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

                                    {{-- {{ session('department_id') }} --}}
                                    <tr>
                                        <th>#</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($app as $data)
                                <tbody>
                                    <tr>
                                        <td>{{-- {{ $data->year_created }}- --}}{{ $loop->iteration  }}</td>
                                        <td>University Annual Procurement Plan For FY {{ $data->project_year }}</td>
                                        {{-- @php
                                            $bac_committee_status = "";
                                        @endphp
                                        @foreach ($recommending_approval as $bac_committee_Status)
                                            @if ($data->project_year == $bac_committee_Status->Year) --}}
                                                <td style="text-align: left; color:{{ (new GlobalDeclare)->bac_committee_status_color($data->bac_committee_status) }}">{{ (new GlobalDeclare)->bac_committee_status($data->bac_committee_status) }}</td>
                                            {{-- @endif
                                        @endforeach --}}
                                        <td>
                                            <form action="{{ route('bac_committee_app_noncse') }}" method="post">
                                                @csrf
                                                <input type="text" class=" form-control d-none" name="year" value="<?=$aes->encrypt($data->project_year)?>">
                                                <input type="text" class=" form-control d-none" name="category" value="<?=$aes->encrypt($data->project_category)?>">
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

<script src="{{asset('js/baccommittee/baccommittee.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

{{-- @include('pages.supervisor.supervisor_check_ppmp') --}}



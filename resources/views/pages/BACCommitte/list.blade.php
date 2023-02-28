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
                            @if (session('campus') == 1)
                                <div class="card-body">
                                    <div class="card-header p-1">
                                    <h5 class="card-title">
                                        <strong>Category</strong>
                                    </h5>
                                    
                                    <ul class="nav nav-tabs m-1" role="tablist">
        
                                        {{-- 1st Tab | PPMP --}}
                                            <li class="nav-item">
                                                <a class="nav-link active" id="my-ppmp-tab" data-toggle="tab" href="#my-ppmp" aria-controls="regular" role="tab" aria-selected="true">
                                                    University Wide
                                                </a>
                                            </li>
                                        {{-- end --}}
                                        {{-- 2nd Tab | Indicative --}}
                                            <li class="nav-item">
                                                <a class="nav-link" id="dissapproved-ppmp-tab" data-toggle="tab" href="#dissapproved" aria-controls="regular9" role="tab" aria-selected="false">
                                                    Main Campus
                                                </a>
                                            </li>
                                        {{-- end --}}
                                    </ul>
                                </div>
                                <div class="card-body p-1">
                                    <div class="tab-content p-1">
                                        {{-- 1st Tab--}}
                                        <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel">
                                            {{-- list of uploaded app --}}
                                            <div class="table-responsive">
                                                <table class="table zero-configuration item-table" id="item-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Year</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $count = 0;
                                                    @endphp
                                                    @foreach ($app1 as $data)
                                                        @php
                                                            $count += 1;
                                                        @endphp
                                                        <tbody>
                                                            <tr>
                                                                <td>{{-- {{ $data->year_created }}- --}}{{ $count  }}</td>
                                                                <td>University Annual Procurement Plan For FY {{ $data->project_year }}</td>
                                                                <td>
                                                                    <div class="badge badge-pill badge-light-{{ (new GlobalDeclare)->app_status_color($data->univ_wide_status,"list") }} mr-1">{{ (new GlobalDeclare)->app_status($data->univ_wide_status,"list") }}</div>
                                                                </td>
                                                                <td>
                                                                    <form action="{{ route('bac_committee_app_noncse') }}" method="post">
                                                                        @csrf
                                                                        <input type="text" class=" form-control d-none" name="year" value="<?=$aes->encrypt($data->project_year)?>">
                                                                        <input type="text" class=" form-control d-none" name="scope" value="0">
                                                                        <input type="text" class=" form-control d-none" name="category" value="<?=$aes->encrypt($data->project_category)?>">
                                                                        <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                                                    </form>
                                                                </td>
                                                                
                                                            </tr>
                                                        </tbody>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                        {{-- 2nd Tab--}}
                                        <div class="tab-pane" id="dissapproved" aria-labelledby="dissapproved-ppmp-tab" role="tabpanel">
                                            {{-- list of uploaded app --}}
                                            <div class="table-responsive">
                                                <table class="table zero-configuration item-table" id="item-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Year</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $count = 0;
                                                    @endphp
                                                    @foreach ($app as $data)
                                                        @php
                                                            $count += 1;
                                                        @endphp
                                                        <tbody>
                                                            <tr>
                                                                <td>{{-- {{ $data->year_created }}- --}}{{ $count  }}</td>
                                                                <td> Annual Procurement Plan For FY {{ $data->project_year }}</td>
                                                                <td {{-- style="text-align: left; color:{{ (new GlobalDeclare)->bac_committee_status_color($data->bac_committee_status) }}" --}}>
                                                                    <div class="badge badge-pill badge-light-{{ (new GlobalDeclare)->app_status_color($data->per_campus_status,"list") }} mr-1">{{ (new GlobalDeclare)->app_status($data->per_campus_status,"list") }}</div>
                                                                </td>
                                                                <td>
                                                                    <form action="{{ route('bac_committee_app_noncse') }}" method="post">
                                                                        @csrf
                                                                        <input type="text" class=" form-control d-none" name="year" value="<?=$aes->encrypt($data->project_year)?>">
                                                                        <input type="text" class=" form-control d-none" name="scope" value="1">
                                                                        <input type="text" class=" form-control d-none" name="category" value="<?=$aes->encrypt($data->project_category)?>">
                                                                        <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                                                    </form>
                                                                </td>
                                                                
                                                            </tr>
                                                        </tbody>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table zero-configuration item-table" id="item-table">
                                        <thead>
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
                                                    <td> Annual Procurement Plan For FY {{ $data->project_year }}</td>
                                                    <td {{-- style="text-align: left; color:{{ (new GlobalDeclare)->bac_committee_status_color($data->bac_committee_status) }}" --}}>
                                                        <div class="badge badge-pill badge-light-{{ (new GlobalDeclare)->app_status_color($data->per_campus_status,"list") }} mr-1">{{ (new GlobalDeclare)->app_status($data->per_campus_status,"list") }}</div>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('bac_committee_app_noncse') }}" method="post">
                                                            @csrf
                                                            <input type="text" class=" form-control d-none" name="year" value="<?=$aes->encrypt($data->project_year)?>">
                                                            <input type="text" class=" form-control d-none" name="scope" value="1">
                                                            <input type="text" class=" form-control d-none" name="category" value="<?=$aes->encrypt($data->project_category)?>">
                                                            <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                                        </form>
                                                    </td>
                                                    
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
            </section>
        </div>
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




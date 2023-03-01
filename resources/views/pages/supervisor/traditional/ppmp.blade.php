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
                                        <th>Project Procurement</th>
                                        <th>Prepared By</th>
                                        <th>Allocated Budget</th>
                                        <th>Fund Source</th>
                                        <th>Remaining Balance</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($ppmp as $data)
                                        {{-- @for ($i = 0; $i < count($data->0); $i++) --}}
                                <tbody>
                                    <tr>
                                        <td>{{-- {{ $data->year_created }}- --}}{{ $loop->iteration  }}</td>
                                        <td>{{ $data->project_title }}</td>
                                        <td>{{ $data->username }}</td>
                                        <td>Php {{ number_format($data->allocated_budget,2 )}}</td>
                                        <td>{{ $data->fund_source }}</td>
                                        <td>Php {{ number_format($data->remaining_balance,2) }}</td>
                                        <td>
                                            <?php $total = 0;
                                                foreach($item as $items){
                                                    if($items->project_code == $data->id){
                                                        $total += $items->estimated_price;
                                                    }
                                            }
                                           ?>

                                                Php {{ number_format($total) }}
                                        </td>
                                        <td> 
                                            <div class="badge badge-pill badge-light-{{ (new GlobalDeclare)->ppmp_status_color($data->status) }} mr-1">{{ (new GlobalDeclare)->ppmp_status($data->status) }}</div>
                                        </td>
                                        <td>
                                            <form action="{{ route('show-traditional-ppmp') }}" method="post">
                                                @csrf
                                                <input type="text" id="project_code12" class=" form-control d-none" name="project_code" value="<?=$aes->encrypt($data->id)?>">
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

<script src="{{asset('js/supervisor/supervisor.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

{{-- @include('pages.supervisor.supervisor_check_ppmp') --}}



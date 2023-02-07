@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','New Requests')
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
                                        <th>#</th>
                                        <th>Department</th>
                                        <th>Project Procurement</th>
                                        <th>Allocated Budget</th>
                                        <th>Fund Source</th>
                                        {{-- <th>Remaining Balance</th>
                                        <th>Total</th> --}}
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($ppmp as $data)
                                        {{-- @for ($i = 0; $i < count($data->0); $i++) --}}
                                <tbody>
                                    <tr>
                                        <td>{{-- {{ $data->year_created }}- --}}{{ $loop->iteration  }}</td>
                                        <td>{{ $data->department_name }}</td>
                                        <td>{{ $data->project_title }}</td>
                                        <td>Php {{ number_format($data->allocated_budget,2 )}}</td>
                                        <td>{{ $data->fund_source }}</td>
                                        {{-- <td>Php {{ number_format($data->remaining_balance,2) }}</td> --}}
                                        {{-- <td>
                                            <?php $total = 0;
                                                foreach($item as $items){
                                                    if($items->project_code == $data->id){
                                                        $total += $items->estimated_price;
                                                    }
                                            }
                                           ?>

                                                Php {{ number_format($total) }}
                                        </td> --}}
                                        <?php
                                        if($data->status == 2)
                                        {
                                            ?>
                                        <td style="text-align: left; color:blue;">Pending</td>
                                        <?php
                                        }
                                        elseif ($data->status == 4) {
                                        ?>
                                        <td style="text-align: left; color:green;">Approved</td>
                                        <?php
                                        }elseif($data->status == 5){
                                        ?>
                                        <td style="text-align: left; color:red;">Disapproved</td>
                                        <?php
                                        
                                        }/* elseif($data->status == 6){
                                        ?>
                                        <td style="text-align: left; color:yellowgreen;">Revised</td>
                                        <?php
                                        }elseif($data->status == 4){
                                        ?>
                                        <td style="text-align: left; color:green;">Accepted by Budget Officer</td>
                                        <?php
                                        }elseif($data->status == 5){
                                        ?>
                                        <td style="text-align: left; color:red;">Rejected by Budget Officer</td>
                                        <?php
                                        } */
                                        ?>
                                        <td>
                                            @if($data->project_category == 0)
                                            <form action="/budgetofficer/view_indicative/showPPMP" method="post">
                                                @csrf
                                                <input type="text" id="project_code12" class=" form-control d-none" name="project_code" value="<?=$aes->encrypt($data->id)?>">
                                                <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                            </form>
                                            {{-- @endif --}}

                                            @elseif($data->project_category == 1)
                                            <form action="/budgetofficer/view_ppmp/showPPMP" method="post">
                                                @csrf
                                                <input type="text" id="project_code12" class=" form-control d-none" name="project_code" value="<?=$aes->encrypt($data->id)?>">
                                                <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                            </form>
                                            {{-- @endif --}}

                                            @elseif($data->project_category == 2)
                                            <form action="/budgetofficer/view_supplemental/showPPMP" method="post">
                                                @csrf
                                                <input type="text" id="project_code12" class=" form-control d-none" name="project_code" value="<?=$aes->encrypt($data->id)?>">
                                                <button type="submit" class="btn btn-outline-secondary view"  >view</button>
                                            </form>
                                            @endif
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

<script src="{{asset('js/budgetofficer/appdis.js?id=500')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

{{-- @include('pages.supervisor.supervisor_check_ppmp') --}}



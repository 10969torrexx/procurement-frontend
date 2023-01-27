{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $project_category;
@endphp
<style>
    .icons:hover {
        color: rgba(90, 141, 238);
        transition: 0.5;
    }
</style>
<script src="https://cdn.lordicon.com/qjzruarw.js"></script>

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
@endsection
@section('content')
<!-- Zero configuration table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="card col-12">
            <div class="card-header">
                <h4 class="text-primary"><strong>Project Category</strong></h4>
            </div>
            <div class="card-header">
                <div class="row col-12">
                    <div class="col-sm-4 shadow-sm p-1 ">
                        <div class="card">
                            <div class="card-body">
                                <div class="icons p-2 text-center">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/mgmiqlge.json"
                                        trigger="hover"
                                        style="width:250px;height:250px">
                                    </lord-icon>
                                </div>
                                <a href="{{ route('department-showCreatetPPMP', ['project_category' => (new AESCipher)->encrypt(0)]) }}" class="btn btn-primary col-12">
                                    <strong class="text-white">Indicative PPMP</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 shadow-sm p-1 ">
                        <div class="card">
                            <div class="card-body">
                                <div class="icons p-2 text-center">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/ajkxzzfb.json"
                                        trigger="hover"
                                        style="width:250px;height:250px;"
                                        >
                                    </lord-icon>
                                </div>
                                <a href="{{ route('department-showCreatetPPMP', ['project_category' => (new AESCipher)->encrypt(1)]) }}" class="btn btn-primary col-12">
                                    <strong>PPMP</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 shadow-sm p-1 ">
                        <div class="card">
                            <div class="card-body">
                                <div class="icons p-2 text-center">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/oegrrprk.json"
                                        trigger="hover"
                                        style="width:250px;height:250px">
                                    </lord-icon>
                                </div>
                                <a href="{{ route('department-showCreatetPPMP', ['project_category' => (new AESCipher)->encrypt(2)]) }}" class="btn btn-primary col-12">
                                    <strong class="text-white">Supplemental PPMP</strong>
                                </a>
                            </div>
                        </div>
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
    @endsection
    {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script> --}}
{{-- Torrexx | Code not mine --}}
<script>
</script>
@endsection

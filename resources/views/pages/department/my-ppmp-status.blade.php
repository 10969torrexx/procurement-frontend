{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $status = 0;
@endphp

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','My PPMP')
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
        {{-- modal --}}
            <!-- Modal -->
            <div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Project Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-body">
                           <div id="project-status">
                                {{-- <h1> Status: {{ $status }} </h1>  --}}
                           </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        {{-- end --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                    <h4 class="text-primary border-bottom pb-1">
                       <strong> My PPMPs</strong>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table">
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
                                    <th>Action</th>
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
                                            <td>
                                                <div class="dropdown">
                                                    <span
                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                    </span>
                                                    <div class="btn dropdown-menu dropdown-menu-left">
                                                       <form action="{{ route('department-showProjectStatus') }}" method="post"> @csrf @method('POST')
                                                            <input type="text" class="form-control d-none" name="id" value="{{ (new AESCipher)->encrypt(json_decode($project_titles)[$i]->id) }}">
                                                            <button class="dropdown-item" type="submit" id="show-modal">View Status</button>
                                                       </form>
                                                    </div>
                                                </div>
                                            </td>
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
    @endsection
    {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script> --}}
{{-- Torrexx | Code not mine --}}

@endsection

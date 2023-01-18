{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    use Carbon\Carbon;
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
                    <div class="col-5 col-sm-5 col-md-5">
                        {{-- search ppmp by year | Disapproved --}}
                            <form action="{{ route('department-year-created') }}" method="POST"  class="row col-12 col-sm-12 col-md-12">
                                @csrf @method('POST')
                                <div class="col-6 p-1">
                                    <input type="text" name="status" class="form-control d-none" value="1">
                                    <select name="year_created" id="" class="form-control">
                                        <option value="">-- Select Year --</option>
                                        @for ($i = 0; $i < 5; $i++)
                                            <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->addYear($i)->format('Y') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6 p-1">
                                    <button type="submit" class="btn btn-success col-6">Search</button>
                                </div>
                            </form>
                        {{-- end --}}
                    </div>

                    <ul class="nav nav-tabs m-1" role="tablist">
                        {{-- 1st Tab | My PPMP --}}
                            <li class="nav-item">
                                <a class="nav-link active" id="my-ppmp-tab" data-toggle="tab" href="#my-ppmp" aria-controls="regular" role="tab" aria-selected="true">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    My PPMP
                                </a>
                            </li>
                        {{-- end --}}
                        {{-- 2nd Tab | Dissapproved PPMP --}}
                            <li class="nav-item">
                                <a class="nav-link" id="dissapproved-ppmp-tab" data-toggle="tab" href="#dissapproved" aria-controls="regular9" role="tab" aria-selected="false">
                                    <i class="fa-regular fa-thumbs-down"></i>
                                    Disapproved PPMP &nbsp; <strong>({{ count($pt_show_disapproved) }})</strong>
                                </a>
                            </li>
                        {{-- end --}}
                    </ul>


                </div>
                <div class="card-body">
                    <div class="row justify-content-center form-group">
                        <div class="container">
                            {{-- Displaying Error Messages --}}
                                {{-- displaying error message for appended element with null values --}}
                                    @if($nullValues = Session::get('nullValues'))
                                        <div class="alert alert-damger alert-dismissible fade show" role="alert">
                                            <strong>Failed!</strong> Please fill all the required fields
                                            <p>
                                                @foreach ($nullValues as $error)
                                                    {{ $error  }}
                                                @endforeach
                                            </p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                {{-- displaying error message for laravel form validation --}}
                                    @if($errors->all())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Failed!</strong> Please fill all the required fields
                                            <p>
                                                @foreach ($errors->all() as $error)
                                                    <div>{{ $error }}</div>
                                                @endforeach
                                            </p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                {{-- Displaying Success Message --}}
                                {{-- this will display success message if ppmp was successfully created --}}
                                    @if($message = Session::get('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>Success!</strong> 
                                            <p>
                                                {{ $message }}
                                            </p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                    @if($message = Session::get('failed'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Failed!</strong> 
                                            <p>
                                                {{ $message }}
                                            </p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                {{-- Displaying Success Message --}}
                            {{-- Displaying Error Messages --}}
                        </div>
                    </div>

                    <div class="tab-content p-1">
                        {{-- 1st Tab | My PPMP --}}
                            <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel"> 
                                {{-- my ppmp data --}}
                                    <div class="table-responsive col-12 container">
                                        <table class="table zero-configuration item-table" id="item-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <!-- <th>Project Code</th> -->
                                                    <th>Project Title</th>
                                                    <th>Year</th>
                                                    <th>Immediate SUPERVISOR</th>
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
                                {{-- end --}}
                            </div>
                        {{-- end --}}
                        
                        {{-- 2nd Tab | Dissapproved PPMP --}}
                            <div class="tab-pane" id="dissapproved" aria-labelledby="dissapproved-ppmp-tab" role="tabpanel">
                               
                                {{-- my disapproved ppmp --}}
                                    <div class="row justify-content-center">
                                        <div class="table-responsive col-md-12 col-sm-12">
                                            <table class="table zero-configuration item-table" id="item-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Project Title</th>
                                                        <th>Year</th>
                                                        <th>Status</th>
                                                        <th>IMmEDIATE SUPERVISOR</th>
                                                        <th>Project Type</th>
                                                        <th>Fund Source</th>
                                                        <th>Date Added</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- showing ppmp data based on department and user --}}
                                                        @foreach ($pt_show_disapproved as $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $item->project_title }}</td>
                                                                <td>{{ $item->year_created }}</td>
                                                                <td>{{ Str::ucfirst((new GlobalDeclare)->status($item->status)) }}</td>
                                                                <td>{{ $item->immediate_supervisor }}</td>
                                                                <td>{{ $item->project_type }}</td>
                                                                <td>{{ $item->fund_source }}</td>
                                                                <td>{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <span
                                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                        </span>
                                                                        <div class="btn dropdown-menu dropdown-menu-left">
                                                                            <a href = "" data-id="{{ $aes->encrypt($item->id) }}" id="edit-title-btn" class="dropdown-item">
                                                                                <i class="bx bx-edit-alt mr-1"></i>Edit
                                                                            </a>
                                                                            <a href = "{{ route('department-destroy-project', ['id' => $aes->encrypt($item->id) ]) }}" class="dropdown-item">
                                                                                <i class="bx bx-trash mr-1"></i> delete
                                                                            </a>
                                                                            <a href = "{{ route('dept_disapproved-items', ['id' => $aes->encrypt($item->id), 'allocated_budget' => $aes->encrypt($item->allocated_budget) ]) }}" class="dropdown-item">
                                                                                <i class="fa-regular fa-eye mr-1"></i>View Item
                                                                            </a>
                                                                        </div>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    {{-- showing ppmp data based on department and user --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                               {{-- end --}}
                            </div>
                        {{-- end --}}
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

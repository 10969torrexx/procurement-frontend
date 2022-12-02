{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    use Carbon\Carbon;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $current_year = Carbon::now();
@endphp
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
        <div class="col-12">
            <div class="card">
                <ul class="nav nav-tabs m-1" role="tablist">
                    {{-- 1st Tab | Create PPMP --}}
                    <li class="nav-item">
                        <a class="nav-link active" id="create-ppmp-tab" data-toggle="tab" href="#create-ppmp" aria-controls="regular" role="tab" aria-selected="true">
                            <i class="fa-solid fa-cart-plus"></i>
                            Create PPMP
                        </a>
                    </li>
                    {{-- 2nd Tab | Dissapproved PPMP --}}
                    <li class="nav-item">
                        <a class="nav-link" id="dissapproved-ppmp-tab" data-toggle="tab" href="#dissapproved" aria-controls="regular9" role="tab" aria-selected="false">
                            <i class="fa-regular fa-thumbs-down"></i>
                            Dissapproved PPMP &nbsp; <strong>({{ count($pt_show_disapproved) }})</strong>
                        </a>
                    </li>
                </ul>
                    {{-- edit project title modal --}}
                        <div class="modal fade" id="viewmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog" role="document">
                            <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-header">Edit -</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('department-update-project') }}" method="POST"> @csrf @method('POST')
                                        <input type="text" class="form-control d-none" name="id" id="project-id">
                                        <div class="modal-body">
                                            <div class="row col-12">
                                                <div class="form-group col-6">
                                                    <label for="">Project Title</label>
                                                    <input type="text" class="form-control" name="project_title" id="project-title">
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="">For what year</label>
                                                    <select id="" class="form-control" name="project_year">
                                                        <option value="" id="default-year" selected></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row col-12">
                                                <div class="form-group col-6">
                                                    {{-- <label for="">Immediate Supervisor</label>
                                                    <input type="text" class="form-control" name="new_immediate_supervisor" id="new-immediate-supervisor"> --}}
                                                    <label for="">Project Type</label>
                                                    <select id="" class="form-control" name="project_type">
                                                        <option value="" id="default-project-type" selected></option>
                                                        @if (count($categories) > 0)
                                                            @foreach ($categories as $item)
                                                                <option value="{{ $item->category }}"> {{ $item->category }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="">Found Source</label>
                                                    <select id="" class="form-control" name="fund_source">
                                                        <option value="" id="default-fund-source" selected></option>
                                                    @foreach ($fund_sources as $item)
                                                        <option value="{{ $item->id }}**{{ $item->fund_source_id }}">{{ $item->fund_source }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="modal-footer" id = "footModal">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                                            <button type="submit" class="btn btn-success">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    {{-- END --}}
                <div class="tab-content p-1">
                    {{-- create PPMP Tab --}}
                    <div class="tab-pane active" id="create-ppmp" aria-labelledby="create-ppmp-tab" role="tabpanel">
                        {{-- Creat PPMP Tab --}}
                        <div class="tab-pane active" id="create-ppmp-div" aria-labelledby="create-ppmp-div-tab" role="tabpanel">
                            <div class="row justify-content-center mb-2">
                                @if (count($fund_sources) > 0)
                                    <form class="col-10 row" action="{{ route('department-createProjectTitle') }}" method="post">@csrf @method('POST')
                                        <input type="text" name="project_category" value="{{ $project_category }}" class="form-control d-none">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for=""> Project Title</label>
                                                <input type="text" class="form-control @error('project_title') is-invalid @enderror"
                                                name="project_title" autocomplete="project_title" autofocus required>
                                                    @error('project_title')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="">Budget Allocation</label>
                                                <select name="fund_source" id="" class="form-control">
                                                    <option value="">-- Choose Option --</option>
                                                    @foreach ($fund_sources as $item)
                                                        <option value="{{ (new AESCipher)->encrypt($item->fund_source_id) }}**{{ (new AESCipher)->encrypt($item->year) }}**{{ (new AESCipher)->encrypt($item->allocated_id) }}"> {{ $item->year }} | {{ $item->fund_source }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="" class="">Project Type</label>
                                                <select name="project_type" id="" class="form-control" required>
                                                    <option value="">-- Choose Option --</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->category }}"> {{ $item->category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <button class="btn btn-primary mt-2" type="submit" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" 
                                                    class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                </svg>
                                                Create
                                            </button>
                                        </div>
                                    </form>
                                @else
                                   <div class="container">
                                        <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                                            <strong>Failed!</strong> No allocated budget. Please contact campus budget officer!
                                        </div>
                                   </div>
                                @endif
                            </div>

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
                                            @if($message = Session::get('error'))
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
                                        {{-- Displaying Success Message --}}
                                    {{-- Displaying Error Messages --}}
                                </div>
                            </div>

                            {{-- creating data tables for the list of project titles --}}
                                <div class="table-responsive col-10 container">
                                    <table class="table zero-configuration item-table" id="item-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                {{-- <th>Project Code</th> --}}
                                                <th>Project Title</th>
                                                <th>Project Year</th>
                                                <th>Status</th>
                                                <th>Immediate SUPERVISOR</th>
                                                <th>Project Type</th>
                                                <th>Fund Source</th>
                                                <th>Date Added</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- showing ppmp data based on department and user --}}
                                                @foreach ($project_titles as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->project_title }}</td>
                                                        <td>{{ $item->project_year }}</td>
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
                                                                    {{-- edit button --}}
                                                                        <a href = "" data-id="{{ $aes->encrypt($item->id) }}" id="edit-title-btn" class="dropdown-item">
                                                                            <i class="bx bx-edit-alt mr-1"></i>Edit
                                                                        </a>
                                                                    {{-- END --}}
                                                                    {{-- delete title --}}
                                                                        <a href = "{{ route('department-destroy-project', ['id' => $aes->encrypt($item->id) ]) }}" class="dropdown-item">
                                                                            <i class="bx bx-trash mr-1"></i> delete
                                                                        </a>
                                                                    {{-- END --}}
                                                                    {{-- add item form --}}
                                                                        <a href = "{{ route('department-addItem', ['id' => $aes->encrypt($item->id), 'allocated_budget' => $aes->encrypt($item->allocated_budget) ]) }}" class="dropdown-item">
                                                                            <i class="fa-regular fa-plus mr-1"></i>Add Item
                                                                        </a>
                                                                    {{-- end add item form --}}
                                                                </div>
                                                            </div> 
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            {{-- showing ppmp data based on department and user --}}
                                        </tbody>
                                    </table>
                                </div>
                            {{-- creating data tables for the list of project titles --}}
                        </div>
                    </div>
                    {{-- Dissapproved PPMP Tab--}}
                    <div class="tab-pane" id="dissapproved" aria-labelledby="dissapproved-ppmp-tab" role="tabpanel">
                        <div class="row justify-content-center">
                            <div class="col-10">
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
                    </div>
                </div>
            </div>
        </div>
    </div>\
    </strong>
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
    $(document).on('click', '#edit-title-btn', function(e) {
       e.preventDefault();
       $('#viewmodal').modal('show');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            },
            url: "{{ route('department-get-project') }}",
            method: 'GET',
            data: {
                'id' : $(this).attr('data-id')
            }, success: function(response) { 
                // console.log(response[0]['id']);
                console.log(response);
                $('#project-id').val(response[0]['id']);
                $('#modal-header').text('Edit - ' + response[0][['project_title']]);
                $('#project-title').val(response[0]['project_title']);
                $('#default-project-type').val(response[0]['project_type']);
                $('#default-project-type').text(response[0]['project_type']);
                $('#default-year').val(response[0]['project_year']);
                $('#default-year').text(response[0]['project_year']);
                $('#default-fund-source').val(response[0]['allocated_budget']+'**'+response[0]['fund_source_id']);
                $('#default-fund-source').text(response[0]['fund_source']);
            } 
        });
    });
</script>
@endsection

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

        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                    <div class="col-6 col-sm-6 col-md-6">
                        {{-- search ppmp by year | Disapproved --}}
                            <form action="{{ route('department-year-created') }}" method="POST"  class="row col-12 col-sm-12 col-md-12">
                                @csrf @method('POST')
                                <div class="col-4 p-1">
                                    <input type="text" name="status" class="form-control d-none" value="1">
                                    <select name="year_created" id="" class="form-control">
                                        <option value="">-- Select Year --</option>
                                        @for ($i = 5; $i > 0; $i--)
                                            <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->subYear($i)->format('Y') }}</option>
                                        @endfor
                                        @for ($i = 0; $i < 5; $i++)
                                            <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->addYear($i)->format('Y') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-4 p-1">
                                    <input type="text" name="status" class="form-control d-none" value="1">
                                    <select name="project_category" id="" class="form-control">
                                        <option value="">-- Select Project Category --</option>
                                        @for ($i = 0; $i < 3; $i++)
                                            <option value="{{ $i }}">{{ (new GlobalDeclare)->project_category($i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-4 p-1">
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
                        {{-- 2nd Tab | Dissapproved PPMP --}}
                            <li class="nav-item">
                                <a class="nav-link" id="approved-ppmp-tab" data-toggle="tab" href="#approved" aria-controls="regular9" role="tab" aria-selected="false">
                                    <i class="fa-regular fa-thumbs-up"></i>
                                    Approved PPMP &nbsp; <strong>({{ count($pt_show_approved) }})</strong>
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
                                                    <th>Project Category</th>
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
                                                            <td>{{ (new GlobalDeclare)->project_category(json_decode($project_titles)[$i]->project_category) }}</td>
                                                            <td>{{ json_decode($project_titles)[$i]->fund_source }}</td>
                                                            <td>{{ (new GlobalDeclare)->status(json_decode($project_titles)[$i]->status) }}</td>
                                                            <td class="text-nowrap"> {{ explode('-', date('j F, Y-', strtotime($project_titles[$i]->updated_at)))[0] }}</td>
                                                            <td>
                                                                <form action="{{ route('department-showProjectStatus') }}" method="post"> @csrf @method('POST')
                                                                    <input type="text" class="form-control d-none" name="id" value="{{ (new AESCipher)->encrypt(json_decode($project_titles)[$i]->id) }}">
                                                                    <button class="dropdown-item btn-primary text-center" type="submit" id="show-modal">View Status</button>
                                                                </form>
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
                                                    <th>Project Category</th>
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
                                                            <td>{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                                            <td>{{ $item->fund_source }}</td>
                                                            <td  class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
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
                        {{-- end --}}
                        {{-- 3rd Tab | Approved PPMP --}}
                            <div class="tab-pane" id="approved" aria-labelledby="approved-ppmp-tab" role="tabpanel">
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
                                                    <th>Project Category</th>
                                                    <th>Fund Source</th>
                                                    <th>Date Added</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- showing ppmp data based on department and user --}}
                                                    @foreach ($pt_show_approved as $item)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->project_title }}</td>
                                                            <td>{{ $item->year_created }}</td>
                                                            <td>{{ Str::ucfirst((new GlobalDeclare)->status($item->status)) }}</td>
                                                            <td>{{ $item->immediate_supervisor }}</td>
                                                            <td>{{ $item->project_type }}</td>
                                                            <td>{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                                            <td>{{ $item->fund_source }}</td>
                                                            <td class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <span
                                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                                    </span>
                                                                    <div class="btn dropdown-menu dropdown-menu-left">
                                                                        <form action="{{ route('department-showProjectStatus') }}" method="post"> @csrf @method('POST')
                                                                            <input type="text" class="form-control d-none" name="id" value="{{ (new AESCipher)->encrypt($item->id) }}">
                                                                            <button class="dropdown-item" type="submit" id="show-modal">
                                                                                <i class="fa-regular fa-eye mr-1"></i>View Status
                                                                            </button>
                                                                        </form>
                                                                        <a href = "{{ route('export_ppmp', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" class="dropdown-item">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                            </svg> &nbsp; &nbsp;
                                                                           Export PDF
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

<script>
    // edit project title details
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

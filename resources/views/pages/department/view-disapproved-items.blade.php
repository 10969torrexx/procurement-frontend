{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
    use App\Http\Controllers\GlobalDeclare;
    $current_project_code ='';
    $project_title_id = '';
    $total_estimated_price = 0.0;
    $allocated_budget = $allocated_budgets[0]->allocated_budget;
    $remaining_balance = doubleVal($allocated_budgets[0]->remaining_balance);
@endphp

{{-- calculations  --}}
    @php
        $total_estimated_price = 0.0;
    @endphp
    @foreach ($ppmp_response as $item)
        @php
            //number_format($total_estimated_price,2,'.',','
            $total_estimated_price += doubleVal($item->estimated_price);
        @endphp
    @endforeach
    @php
        $remaining_balance = $remaining_balance - $total_estimated_price;
        if($remaining_balance <= 0) {
            $remaining_balance = 0.0;
        }
    @endphp
{{-- end --}}
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
<style>
    #t-table, #t-th, #t-td  {
        border: 1px solid;
        font-size: 11px;
        padding: 5px;
        text-align: center;
    }
    #t-table{
        width: 100%;
    }

    .tbg-secondary {
        background-color: rgba(71, 95, 123, 0.9) !important;
    }
</style>
<!-- Zero configuration table -->
<section id="horizontal-vertical">
    {{-- item names modal --}}
        <div class="modal fade" id="items-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Choose from items</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="p-1" id="item-result">
                                {{-- appended buttons will be placed here --}}
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    {{-- end --}}
    {{-- view templates modal --}}
        <div class="modal fade" id="templates-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-header">Item descriptions for: &nbsp;<strong id="item-name-template"></strong> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body p-1">
                            <div id="templates-container">
                                {{-- appended buttons will be placed here --}}
                                
                            </div>
                        </div>
                </div>
            </div>
        </div>
    {{-- view templates modal --}}

    {{-- edit ppmps modal --}}
        <div class="modal fade" id="edit-ppmps-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-header">Edit Item Detail </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-1">
                        <form action="{{ route('department-update-item') }}" method="post">
                            @csrf
                            <div class="form-group p-2">
                                <input type="text" name="id" id="edit-id" class="form-control d-none">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for=""> Item Name</label>
                                                <input type="text" class="form-control d-none" name="item_name" id="edit-item-name" required>
                                                <div class="row justify-content-center">
                                                    <label for="" class="form-control col-sm-10" id="edit-selected-item-name" data-item-name=""></label>
                                                    <a class="btn btn-primary text-white text-center" style="width: 10%; height:40px; padding:8px;" id="edit-item-name-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bag-plus-fill" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0zM8.5 8a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V12a.5.5 0 0 0 1 0v-1.5H10a.5.5 0 0 0 0-1H8.5V8z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=""> APP Type</label>
                                                <input type="text" class="form-control d-none" name="item_category" id="edit-item-category">
                                                <input type="text" class="form-control d-none" name="app_type" id="edit-app-type">
                                                <p class="form-control" id="edit-app-type-p">-- SSS --</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Unit of Measure</label>
                                                <select name="unit_of_measurement" id="" class="form-control" required>
                                                    <option value="" id="edit-default-unit-of-measurement">-- Choose Option --</option>
                                                    @foreach ($unit_of_measurements as $item)
                                                        <option value="{{ $item->unit_of_measurement }}">{{ $item->unit_of_measurement }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for=""> Quantity </label>
                                                <div class="row justify-content-center">
                                                    <input type="text" class="form-control col-sm-10" name="quantity" id="edit-quantity">
                                                    <a class="btn btn-primary text-white text-center" style="width: 10%; height:40px; padding:8px;" id="edit-calculate-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                                                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5zm0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zM4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zM7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5h-1z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=""> Unit Price </label>
                                                <input type="text" class="form-control d-none" name="unit_price" id="edit-final-unit-price">
                                                <input type="text" class="form-control" id="edit-unit-price" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=""> Estimated Price</label>
                                                <input type="text" class="form-control d-none" id="edit-estimated-price" name="estimated_price" required>
                                                <p class="form-control" id="edit-estimated-price-p">-- None --</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="">Item Description</label>
                                                <textarea name="item_description" id="edit-item-description-textarea" cols="15" rows="5" class="form-control" required></textarea>
                                            </div>
                                           
                                            <button class="btn btn-primary col-12" type="button" id="edit-view-templates-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                                  </svg>
                                                &nbsp; View Templates
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="">Mode of Procurement</label>
                                                <input type="text" class="form-control d-none" value="" id="edit-mode-procurement-input" name="mode_of_procurement">
                                                <p class="form-control" id="edit-mode-procurement-p" style="display: none"></p>
                                                <select name="mode_of_procurement" id="edit-mode-procurement-select" class="form-control">
                                                    <option value="" id="edit-default-mode-of-procurement">-- Choose Option --</option>
                                                    @foreach ($mode_of_procurements as $item)
                                                        <option value="{{  $item->id }}">{{ $item->mode_of_procurement }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <label for="">Expected Month</label>
                                                <select name="expected_month" class="form-control" required>
                                                    <option value="" id="edit-default-expected-month">-- Choose Option --</option>
                                                    @for ($i = 0; $i < 12; $i++)
                                                        <option value="{{ ($i) + 1 }}">{{ (new GlobalDeclare)->Month(($i) + 1) }}</option>
                                                    @endfor
                                            </select>

                                            <div class="form-group row p-1">
                                                <button class="btn btn-success col-sm-12 col-md-12 col-12">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {{-- edit ppmps modal --}}
    
    {{-- edit item names modal --}}
        <div class="modal fade" id="edit-items-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Choose from items</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="p-1" id="edit-item-result">
                                {{-- appended buttons will be placed here --}}
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    {{-- end --}}


    {{-- edit| view templates modal --}}
        <div class="modal fade" id="edit-templates-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-header">Item descriptions for: &nbsp;<strong id="edit-item-name-template"></strong> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body p-1">
                            <div id="edit-templates-container">
                                {{-- appended buttons will be placed here --}}
                                
                            </div>
                        </div>
                </div>
            </div>
        </div>
    {{-- edit|  view templates modal --}}

    
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <div class="row border-bottom p-1">
                        <div class="form-group col-6 text-left">
                            <h4 class="text-primary"><strong>Disapproved PPMP</strong></h4>
                        </div>
                        <div class="form-group col-6 text-right">
                            <h4 class=""><strong class="text-danger">Deadline of Submission:</strong> {{ explode('-', date('F j, Y', strtotime($allocated_budgets[0]->end_date)))[0] }}  </h4>
                            
                        </div>
                    </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="tab-content">
                                    <div class="tab-pane active" id="create-ppmp-div" aria-labelledby="create-ppmp-div-tab" role="tabpanel">
                                        <div class="row justify-content-center p-1">
                                            <div class="col-12 col-sm-12 col-md-12 row">
                                                <table class="table">
                                                    <tr class="border-zero">
                                                        <th>Project Title</th>
                                                        <th>Project Type</th>
                                                        <th>Immediate Supervisor</th>
                                                        <th>Fund Source</th>
                                                        <th>Project Year</th>
                                                        <th>Year Created</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    <tbody>
                                                        <tr class="border-zero">
                                                            @foreach ($project_titles as $item)
                                                                <tr class="border-zero">
                                                                    @php  $project_title_id = $item->id; @endphp
                                                                    <td>{{ $item->project_title }}</td>
                                                                    <td>{{ $item->project_type }}</td>
                                                                    <td>{{ $item->immediate_supervisor }}</td>
                                                                    <td>{{ $item->fund_source }}</td>
                                                                    <td>{{ $item->project_year }}</td>
                                                                    <td>{{ $item->year_created }}</td>
                                                                    <td>{{ (new GlobalDeclare)->status($item->status) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- message and errors --}}
                                            @if(Session::has('success'))
                                                <script>
                                                    Swal.fire({
                                                        title: 'Success',
                                                        icon: 'success',
                                                        html: "{{ Session::get('success') }}",
                                                    });
                                                </script>
                                            @endif
                                            @if($errors->all())
                                                <script>
                                                    Swal.fire({
                                                        title: 'Error',
                                                        icon: 'error',
                                                        html: "Please make sure fields are well accounted for!",
                                                    });
                                                </script>
                                            @endif
                                            @if(Session::has('failed'))
                                                <script>
                                                    Swal.fire({
                                                        title: 'Error',
                                                        icon: 'error',
                                                        html: "{{ Session::get('failed') }}",
                                                    });
                                                </script>
                                            @endif
                                        {{-- message and errors --}}
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
 
    <div class="row">
        <div class="col-12 ">
            <div class="card p-1">
                <div class="row p-1 bg-secondary">
                    <div class="col-4 text-center">
                        <h5 class="text-success"><strong>Allocated Budget:</strong>&nbsp; ₱ {{ number_format($allocated_budgets[0]->remaining_balance,2,'.',',') }}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5 class="text-white"><strong>Total Estimated Price:</strong>&nbsp; ₱ {{ number_format($total_estimated_price,2,'.',',') }}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h5 class="text-warning"><strong>Remaining Balance:</strong>&nbsp; ₱ {{ number_format($remaining_balance,2,'.',',') }}</h5>
                    </div>
                </div>
                
                {{-- creating data tables for the list of project titles --}}
                    <div class="table-responsive col-12 container scrollable">
                        <table class="table  scrollable zero-configuration item-table " id="item-table t-table">
                            <thead>
                                <tr id="t-tr">
                                    <th id="t-td">#</th>
                                    <th id="t-td">Item name</th>
                                    <th id="t-td" class="text-nowrap">app type</th>
                                    <th id="t-td">quantity</th>
                                    <th id="t-td">Unit Price</th>
                                    <th id="t-td" class="text-nowrap">estimated price</th>
                                    <th id="t-td">item description</th>
                                    <th id="t-td">unit of measurement</th>
                                    <th id="t-td">mode of procurement</th>
                                    <th id="t-td" class="text-nowrap">expected month</th>
                                    <th id="t-td">status</th>
                                    <th id="t-td">date created</th>
                                    <th id="t-td">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                    @foreach ($ppmp_response as $item)
                                        <tr id="t-tr">
                                            <td id="t-td">{{ $loop->iteration }}</td>
                                            <td id="t-td" class="text-nowrap">{{ $item->item_name }}</td>
                                            <td id="t-td">{{ $item->app_type }}</td>
                                            <td id="t-td">{{ $item->quantity }}</td>
                                            <td id="t-td">₱{{ number_format($item->unit_price,2,'.',',')  }}</td>
                                            <td id="t-td">₱{{ number_format($item->estimated_price,2,'.',',')  }}</td>
                                            <td id="t-td">{{ $item->item_description }}</td>
                                            <td id="t-td">{{ $item->unit_of_measurement }}</td>
                                            <td id="t-td">{{ $item->mode_of_procurement }}</td>
                                            <td id="t-td">{{  (new GlobalDeclare)->Month( $item->expected_month) }}</td>
                                            <td id="t-td">{{ Str::ucfirst((new GlobalDeclare)->status($item->status))  }}</td>
                                            <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0] }}</td>
                                            <td id="t-td">
                                                <div class="dropdown">
                                                    <span
                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <a class="dropdown-item" data-id = "{{ $item->ppmps_id }}" id="edit-ppmp-btn" href = "#">
                                                            <i class="bx bx-edit-alt mr-1"></i> edit
                                                        </a>
                                                        <a href="{{ route('department-delete-item', ['id'=> (new AESCipher)->encrypt($item->ppmps_id)]) }}" class="dropdown-item" id="delete-item-btn">
                                                            <i class="bx bx-trash mr-1"></i> delete
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
                    {{-- adding submit ppmp feature --}}
                    <div class="form-group p-1 row justify-content-center">
                        <div class="form-group col-6">
                            <form action="{{ route('department-re_submit-ppmp') }}" method="post">@csrf @method('POST')
                                <input type="text" value="{{ (new AESCipher)->encrypt($project_title_id) }}" class="form-control d-none" name="current_project_code">
                                <input type="text" class="form-control d-none" name="remaining_balance" value="{{ $allocated_budgets[0]->remaining_balance }}">
                                <input type="text" class="form-control d-none" name="total_estimated_price" value="{{ $total_estimated_price }}">
                                <input type="text" class="form-control d-none" name="allocated_budget" value="{{ $allocated_budgets[0]->id }}">
                                <input type="text" class="form-control d-none" name="deadline_of_submission" value="{{ $allocated_budgets[0]->end_date }}">
                                <button type="submit" class="btn btn-success form-control">Re-Submit PPMP</button>
                            </form>
                        </div>
                    </div>
                    {{-- end adding submit ppmp feature --}}
              
                {{-- creating data tables for the list of project titles --}}
            </div>
        </div>
    </div>

</section>
{{-- Torrexx | Code not mine --}}
    <script src="extensions/resizable/bootstrap-table-resizable.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @section('vendor-scripts')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
    @endsection
    @section('page-scripts')
    <script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
    @endsection
{{-- Torrexx | Code not mine --}}
<script>
    // this will enable view templates modal upon button click
        $('#view-templates-btn').click(function() {
            $('#templates-modal').modal('show');
                // // this will get all the item descriptions based on the given item name
                $('#templates-container').html('');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: "{{ route('department-item-description') }}",
                    method: 'POST',
                    data: {
                        'item_name' : $('#selected-item-name').text()
                    }, success: function(response) { 
                        console.log($('#selected-item-name').text());
                        $('#item-name-template').text($('#selected-item-name').text());
                        if(response.length > 0) {
                            // console.log(response);
                            response.forEach(element => {
                                var _unit_price_format = "₱" + element.unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                var _estimated_price_format = "₱" + element.estimated_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                $('#templates-container').append('\
                                \<div id="selected-template" class="p-1 btn btn-secondary bg-secondary col-sm-12 col-md-12 col-12 mb-1"\
                                    data-unit-price="'+ element.unit_price +'"\
                                    data-estimated-price="'+ element.estimated_price +'"\
                                    data-quantity="'+ element.quantity +'"\
                                    data-item-description="'+ element.item_description +'"\
                                >\
                                    <div class="form-group">\
                                        <p class="text-white text-left">\
                                           <strong>Author:</strong> ' + element.name +'\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Unit Price:</strong> '+ _unit_price_format +'\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Quantity:</strong> '+ element.quantity +' '+ element.unit_of_measurement +'s\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Estimated Price:</strong> '+ _estimated_price_format +'\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Item Description:</strong>\
                                        </p>\
                                        <p class="text-white text-left">\
                                           '+ element.item_description +'\
                                        </p>\
                                    </div>\
                                </div>\
                                ');
                            });
                        } 
                        if(response.length <= 0) {
                            $('#templates-container').append('<p class="col-12 text-center m-1" >Failed to fetch item descriptions</p>');
                        }
                    }
                });
        });
    // end
    // edit ppmps modal
       $(document).on('click', '#edit-ppmp-btn', function(e) {
            e.preventDefault();
            $('#edit-ppmps-modal').modal('show');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('get_ppmps') }}",
                method: 'GET',
                data: {
                    'id' : $(this).data('id')
                }, success: function(response) { 
                    console.log(response);
                    console.log(response[0].app_type);
                    if( (response.length > 0) || (response == null)) {
                        var month = [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December',
                        ];
                        response.forEach(element => {
                            $('#edit-id').val(element.ppmps_id);

                            $('#edit-item-name').val(element.item_name);
                            $('#edit-selected-item-name').text(element.item_name);

                            $('#edit-item-category').val(element.item_category);
                            $('#edit-app-type').val(element.app_type);
                            $('#edit-app-type-p').text(element.app_type);

                            $('#edit-default-unit-of-measurement').text(element.unit_of_measurement);
                            $('#edit-default-unit-of-measurement').val(element.unit_of_measurement);

                            $('#edit-quantity').val(element.quantity);

                            var _unit_price_format = "₱" + element.unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            var _estimated_price_format = "₱" + element.estimated_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            $('#edit-final-unit-price').val(element.unit_price);
                            $('#edit-unit-price').val(_unit_price_format);

                            $('#edit-estimated-price').val(element.estimated_price);
                            $('#edit-estimated-price-p').text(_estimated_price_format);

                            $('#edit-item-description-textarea').val(element.item_description);

                            $('#edit-default-mode-of-procurement').val(element.procurement_id);
                            $('#edit-default-mode-of-procurement').text(element.mode_of_procurement);

                            $('#edit-default-expected-month').val(element.expected_month);
                            $('#edit-default-expected-month').text(month[element.expected_month - 1]);

                        }); 
                    } else {
                        console.log('No item to display');
                    }
                } 
            });
       });
    // end
    // this will show the templates using the view templates from the edit item details modal
       $(document).on('click', '#edit-templates-btn', function() {
           $('#edit-templates-modal').modal('show');
            // appending value on template modal
            $('#item-name-template').html('something');
                console.log($('#default-item-name-select').children('option:selected').text());
                // this will get all the item descriptions based on the given item name
                $('#edit-templates-container').html('');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: "{{ route('department-item-description') }}",
                    method: 'POST',
                    data: {
                        'item_name' : $('#default-item-name-select').children('option:selected').text()
                    }, success: function(response) { 
                        if(response['status'] == 200) {
                            // this will loop through all the response data
                            for(var i = 0; i < response['data'].length; i++) {
                                console.log(response['data'][i]['item_description']);
                                // this will append all 
                                $('#edit-templates-container').append('<button class="btn btn-light col-12 m-1" type="button" id="edit-templates-btn">' + response['data'][i]['item_description']  + '</button>');
                            }
                        } 
                        if(response['status'] == 400) {
                            $('#edit-templates-container').append('<p class="col-12 text-center m-1" >' + response['message']  + '</p>');
                        }
                    } 
                });
       });
    // end
    // this will close the edit modal 
       $(document).on('click', '#edit-templates-btn', function() {
            $('#edit-templates-modal').modal('hide');
            // append the clicked template to edit item description textarea 
                $('#default-item-description').val($(this).text());
            
       });
    // end
    // this will close the edit item decription modal
       $(document).on('click', '#edit-close', function() {
            $('#edit-templates-modal').modal('hide');
       });
    // end
    // item modal
    // 
        $(document).on('click', '#item-name-btn', function () {
            $('#items-modal').modal('show');
            $('#item-result').html(' ');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('get_items') }}",
                method: 'GET', 
                success: function(response) { 
                    console.log(response);
                    if(response.length > 0) {
                        response.forEach(element => {
                            $('#item-result').append('<button class="btn btn-light col-12 m-1" type="button" id="item-btn"\
                            data-public-bidding="'+ element.public_bidding +'" \
                            data-mode-of-procurement="'+ element.mode_of_procurement +'"\
                            data-procurement-id="'+ element.mode_of_procurement_id +'"\
                            data-app-type="'+ element.app_type +'" \
                            data-item-category="'+ element.item_category +'" \
                            data-item="'+ element.item_name +'"\
                            data-dismiss="modal" aria-label="Close"\>' 
                            + element.item_name  + '</button>');
                        });
                    } else {
                        $('#item-result').append('<p>Nothing to show</p>');
                    }
                } 
            });
        });
        // --- edit --
        $(document).on('click', '#edit-item-name-btn', function () {
            $('#edit-items-modal').modal('show');
            $('#edit-item-result').html(' ');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('get_items') }}",
                method: 'GET', 
                success: function(response) { 
                    console.log(response);
                    if(response.length > 0) {
                        response.forEach(element => {
                            $('#edit-item-result').append('<button class="btn btn-light col-12 m-1" type="button" id="edit-item-btn"\
                            data-public-bidding="'+ element.public_bidding +'" \
                            data-mode-of-procurement="'+ element.mode_of_procurement +'"\
                            data-procurement-id="'+ element.mode_of_procurement_id +'"\
                            data-app-type="'+ element.app_type +'" \
                            data-item-category="'+ element.item_category +'" \
                            data-item="'+ element.item_name +'"\
                            data-dismiss="modal" aria-label="Close"\>' 
                            + element.item_name  + '</button>');
                        });
                    } else {
                        $('#item-result').append('<p>Nothing to show</p>');
                    }
                } 
            });
        });
    // end
    // item
        // this the element for the selected item display on display
        $(document).on('click', '#item-btn', function(e) {
            // console.log($(this).data('item'));
            $('#selected-item-name').text($(this).data('item'));
            $('#app-type').val($(this).data('app-type'));
            $('#app-type-p').text($(this).data('app-type'));
            $('#item-name').val($(this).data('item'));
            $('#item-category').val($(this).data('item-category'));
            console.log($(this).data('mode-of-procurement'));
            console.log($(this).data('procurement-id'));
            // changing mode of procurement
            if($(this).data('public-bidding') == 1) {
                $('#mode-procurement-select').css('display', 'none');
                $('#mode-procurement-select').prop( "disabled", true );
                $('#mode-procurement-input').prop( "disabled", false );
                $('#mode-procurement-input').val($(this).data('procurement-id'));
                $('#mode-procurement-p').css('display', 'block');
                $('#mode-procurement-p').text($(this).data('mode-of-procurement'));
            } else {
                // setting default mode of procurement
                $('#default-mode-of-procurement').val($(this).data('procurement-id'));
                $('#default-mode-of-procurement').text($(this).data('mode-of-procurement'));

                $('#mode-procurement-select').css('display', 'block');
                $('#mode-procurement-select').prop( "disabled", false );
                $('#mode-procurement-input').prop( "disabled", true );
                $('#mode-procurement-input').val('');
                $('#mode-procurement-p').css('display', 'none');
                $('#mode-procurement-p').text('');
            }
        }); 
        // ----
        $(document).on('click', '#edit-item-btn', function(e) {
            // console.log($(this).data('item'));
            $('#edit-selected-item-name').text($(this).data('item'));
            $('#edit-app-type').val($(this).data('app-type'));
            $('#edit-app-type-p').text($(this).data('app-type'));
            $('#edit-item-name').val($(this).data('item'));
            $('#edit-item-category').val($(this).data('item-category'));
            console.log($(this).data('mode-of-procurement'));
            console.log($(this).data('procurement-id'));
            // changing mode of procurement
            if($(this).data('public-bidding') == 1) {
                $('#edit-mode-procurement-select').css('display', 'none');
                $('#edit-mode-procurement-select').prop( "disabled", true );
                $('#edit-mode-procurement-input').prop( "disabled", false );
                $('#edit-mode-procurement-input').val($(this).data('procurement-id'));
                $('#edit-mode-procurement-p').css('display', 'block');
                $('#edit-mode-procurement-p').text($(this).data('mode-of-procurement'));
            } else {
                // setting default mode of procurement
                $('#edit-default-mode-of-procurement').val($(this).data('procurement-id'));
                $('#edit-default-mode-of-procurement').text($(this).data('mode-of-procurement'));

                $('#edit-mode-procurement-select').css('display', 'block');
                $('#edit-mode-procurement-select').prop( "disabled", false );
                $('#edit-mode-procurement-input').prop( "disabled", true );
                $('#edit-mode-procurement-input').val('');
                $('#edit-mode-procurement-p').css('display', 'none');
                $('#edit-mode-procurement-p').text('');
            }
        }); 
    //end
    // selected template
        $(document).on('click', '#selected-template', function(e) {
            e.preventDefault();
            // currency formating
            var _unit_price = $(this).data('unit-price');
            var _calculate = $(this).data('estimated-price');
            var _unit_price_format = "₱" + _unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var _estimated_price_format = "₱" + _calculate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            $('#templates-modal').modal('hide'); // this will close the template modal
            // this will append the unit price, quantity and estimated price of the selected template
            $('#quantity').val($(this).data('quantity'));
            $('#unit-price').val(_unit_price_format);
            $('#final-unit-price').val($(this).data('unit-price'));
            $('#estimated-price').val($(this).data('estimated-price'));
            $('#estimated-price-p').text(_estimated_price_format);
            // append the item description on the item description
            $('#item-description-textarea').val($(this).data('item-description'));
        });
    // end
    // calculating estimated price using calculate btn
        $(document).on('click', '#calculate-btn', function(e) {
            e.preventDefault();
            if( $('#quantity').val() == 0 ||  $('#quantity').val() == null) {
                var _quantity = 0;
                var _unit_price = 0;
                var _calculate = (_quantity * _unit_price);
                // formating _unit_price
                var _unit_price_format = "₱" + _unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                var _estimated_price_format = "₱" + _calculate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#quantity').val('0');
                $('#unit-price').val('');
                $('#unit-price').val(_unit_price_format);
                // appending the calculated estimated price
                $('#final-unit-price').val(_unit_price);
                $('#estimated-price').val(_calculate);
                $('#estimated-price-p').text(_estimated_price_format);
            } else {
                var _quantity = $('#quantity').val();
                var _unit_price = $('#unit-price').val();
                var _calculate = (_quantity * _unit_price);
                // formating _unit_price
                var _unit_price_format = "₱" + _unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                var _estimated_price_format = "₱" + _calculate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#unit-price').val('');
                $('#unit-price').val(_unit_price_format);
                // appending the calculated estimated price
                $('#final-unit-price').val(_unit_price);
                $('#estimated-price').val(_calculate);
                $('#estimated-price-p').text(_estimated_price_format);
            }
            
        });
    //end
    // change unit price money format on click
        $(document).on('click', '#unit-price', function(e) {
            $(this).val($('#final-unit-price').val());
        });

        $(document).on('click', '#edit-unit-price', function(e) {
            $(this).val($('#edit-final-unit-price').val());
        });
    //end

    // edit | view templates btn
        $('#edit-view-templates-btn').click(function() {
            $('#edit-templates-modal').modal('show');
                // // this will get all the item descriptions based on the given item name
                $('#edit-templates-container').html('');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: "{{ route('department-item-description') }}",
                    method: 'POST',
                    data: {
                        'item_name' : $('#edit-selected-item-name').text()
                    }, success: function(response) { 
                        console.log($('#edit-selected-item-name').text());
                        $('#edit-item-name-template').text($('#edit-selected-item-name').text());
                        if(response.length > 0) {
                            // console.log(response);
                            response.forEach(element => {
                                var _unit_price_format = "₱" + element.unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                var _estimated_price_format = "₱" + element.estimated_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                $('#edit-templates-container').append('\
                                \<div id="edit-selected-template" class="p-1 btn btn-secondary bg-secondary col-sm-12 col-md-12 col-12 mb-1"\
                                    data-unit-price="'+ element.unit_price +'"\
                                    data-estimated-price="'+ element.estimated_price +'"\
                                    data-quantity="'+ element.quantity +'"\
                                    data-item-description="'+ element.item_description +'"\
                                >\
                                    <div class="form-group">\
                                        <p class="text-white text-left">\
                                           <strong>Author:</strong> ' + element.name +'\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Unit Price:</strong> '+ _unit_price_format +'\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Quantity:</strong> '+ element.quantity +' '+ element.unit_of_measurement +'s\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Estimated Price:</strong> '+ _estimated_price_format +'\
                                        </p>\
                                        <p class="text-white text-left">\
                                            <strong>Item Description:</strong>\
                                        </p>\
                                        <p class="text-white text-left">\
                                           '+ element.item_description +'\
                                        </p>\
                                    </div>\
                                </div>\
                                ');
                            });
                        } 
                        if(response.length <= 0) {
                            $('#edit-templates-container').append('<p class="col-12 text-center m-1" >Failed to fetch item descriptions</p>');
                        }
                    }
                });
        });
    // end
    
    // edit | selected template
        $(document).on('click', '#edit-selected-template', function(e) {
            e.preventDefault();
            // currency formating
            var _unit_price = $(this).data('unit-price');
            var _calculate = $(this).data('estimated-price');
            var _unit_price_format = "₱" + _unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var _estimated_price_format = "₱" + _calculate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            $('#edit-templates-modal').modal('hide'); // this will close the template modal
            // this will append the unit price, quantity and estimated price of the selected template

            console.log($(this).data('quantity'));
            $('#edit-quantity').val($(this).data('quantity'));
            $('#edit-unit-price').val(_unit_price_format);
            $('#edit-final-unit-price').val($(this).data('unit-price'));
            $('#edit-estimated-price').val($(this).data('estimated-price'));
            $('#edit-estimated-price-p').text(_estimated_price_format);
            // append the item description on the item description
            $('#edit-item-description-textarea').val($(this).data('item-description'));
        });
    // end

    // edit | calculate btn 
        $(document).on('click', '#edit-calculate-btn', function(e) {
            e.preventDefault();
            if( $('#edit-quantity').val() == 0 ||  $('#edit-quantity').val() == null) {
                var _quantity = 0;
                var _unit_price = 0;
                var _calculate = (_quantity * _unit_price);
                // formating _unit_price
                var _unit_price_format = "₱" + _unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                var _estimated_price_format = "₱" + _calculate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#edit-quantity').val('0');
                $('#edit-unit-price').val('');
                $('#edit-unit-price').val(_unit_price_format);
                // appending the calculated estimated price
                $('#edit-final-unit-price').val(_unit_price);
                $('#edit-estimated-price').val(_calculate);
                $('#edit-estimated-price-p').text(_estimated_price_format);
            } else {
                var _quantity = $('#edit-quantity').val();
                var _unit_price = $('#edit-unit-price').val();
                var _calculate = (_quantity * _unit_price);
                // formating _unit_price
                var _unit_price_format = "₱" + _unit_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                var _estimated_price_format = "₱" + _calculate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('#edit-unit-price').val('');
                $('#edit-unit-price').val(_unit_price_format);
                // appending the calculated estimated price
                $('#edit-final-unit-price').val(_unit_price);
                $('#edit-estimated-price').val(_calculate);
                $('#edit-estimated-price-p').text(_estimated_price_format);
            }
        });
    // end
</script>
@endsection

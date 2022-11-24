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
{{-- including the modal --}}
@include('pages.department.modal');
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
                    <div class="card-header">
                    <div class="row border-bottom p-1">
                        <div class="form-group col-6 text-left">
                            <h4>Add Item to PPMP</h4>
                        </div>
                        <div class="form-group col-6 text-right">
                            <h4 class=""><strong class="text-danger">Deadline of Submission:</strong> {{ explode('-', date('F j, Y', strtotime($allocated_budgets[0]->deadline_of_submission)))[0] }}  </h4>
                            
                        </div>
                    </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="tab-content">
                                    {{-- Creat PPMP Tab --}}
                                    <div class="tab-pane active" id="create-ppmp-div" aria-labelledby="create-ppmp-div-tab" role="tabpanel">
                                        <div class="row justify-content-center p-1">
                                            <div class="col-sm-8 row">
                                                <table class="table border-zero">
                                                    <tr class="border-zero">
                                                        <th>Project Code</th>
                                                        <th>Project Title</th>
                                                        <th>Project Type</th>
                                                        <th>Immediate Supervisor</th>
                                                        <th>Fund Source</th>
                                                        <th>Year</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    <tbody>
                                                        <tr class="border-zero">
                                                            @for ($i = 0; $i < count($ProjectTitleResponse); $i++)
                                                                <tr class="border-zero">
                                                                    @php  $project_title_id = $ProjectTitleResponse[$i]['id']; @endphp
                                                                    <td class="border-zero">{{ $current_project_code = $ProjectTitleResponse[$i]['year_created'] }}-{{ ($i) + 1}}</td>
                                                                    <td>{{ $ProjectTitleResponse[$i]['project_title'] }}</td>
                                                                    <td>{{ $ProjectTitleResponse[$i]['project_type'] }}</td>
                                                                    <td>{{ $ProjectTitleResponse[$i]['immediate_supervisor'] }}</td>
                                                                    <td>{{ $ProjectTitleResponse[$i]['fund_source'] }}</td>
                                                                    <td>{{ $ProjectTitleResponse[$i]['project_year'] }}</td>
                                                                        @if( Str::ucfirst((new GlobalDeclare)->status($ProjectTitleResponse[$i]['status'])) == 'approved' )
                                                                            <td class="text-success">{{ Str::ucfirst((new GlobalDeclare)->status($ProjectTitleResponse[$i]['status'])) }}</td>
                                                                        @else
                                                                            <td class="text-danger">{{ Str::ucfirst((new GlobalDeclare)->status($ProjectTitleResponse[$i]['status'])) }}</td>
                                                                        @endif
                                                                </tr>
                                                            @endfor
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
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

                                        <div class="row justify-content-center form-group">
                                            <div class="col-sm-8 p-1 border-bottom">
                                                <h5>Add Item</h5>
                                            </div>
                                        </div>
                                        <form class="row justify-content-center" action="{{ route('department-create-ppmps') }}" method="POST"> @csrf @method('POST')
                                            <div class="row col-8 p-1">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for=""> Item Name</label>
                                                        <input type="text" class="form-control d-none" name="item_name_select" id="item-name-val">
                                                        <select name="item_name" id="item-name-select" class="form-control" required>
                                                            <option value="">-- Choose Item --</option>
                                                            @foreach ($items as $item)
                                                                <option data-name="{{ (new AESCipher)->encrypt($item->item_name) }}" value="{{ (new AESCipher)->encrypt($item->item_name) }}**{{ (new AESCipher)->encrypt($item->app_type) }}**{{ $ProjectTitleResponse[0]['id'] }}">{{ $item->item_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for=""> Quantity </label>
                                                        <input type="text" id="quantity-input" class="form-control @error('quantity') is-invalid @enderror"
                                                            name="quantity" autocomplete="quantity" autofocus required>
                                                            @error('quantity')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for=""> Unit Price </label>
                                                        <input type="text" class="form-control @error('unit_price') is-invalid @enderror"
                                                            name="unit_price" autocomplete="unit_price" autofocus id="unit-price" required>
                                                            @error('unit_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for=""> APP Type</label>
                                                        <input type="text" class="form-control d-none @error('category') is-invalid @enderror"
                                                            name="item_category" autocomplete="item_category" autofocus id="item-category" required>
                                                            @error('item_category')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        <p class="form-control" id="item-category-p">-- None --</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Unit of Measure</label>
                                                        <select name="unit_of_measurement" id="" class="form-control" required>
                                                            <option value="">-- Choose Option --</option>
                                                            @for ($i = 0; $i < count($unit_of_measurements); $i++)
                                                                <option value="{{ $unit_of_measurements[$i]['unit_of_measurement'] }}">{{ $unit_of_measurements[$i]['unit_of_measurement'] }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for=""> Estimated Price</label>
                                                        <input type="text" class="form-control d-none @error('estimated_price') is-invalid @enderror"
                                                            name="estimated_price" autocomplete="estimated_price" autofocus id="estimated-price" required> 
                                                            @error('estimated_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                            <p class="form-control" id="estimated-price-p">-- None --</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="">Item Description</label>
                                                        <textarea id="item-description-textarea" class="form-control @error('item_description') is-invalid @enderror" 
                                                            name="item_description" id="" cols="15" rows="5" autofocus required>
                                                        </textarea>
                                                            @error('item_description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                    </div>
                                                   {{-- view templates modal --}}
                                                        <div class="modal fade" id="templates-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modal-header">Item descriptions for: &nbsp;<strong id="item-name-template"></strong> </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                        <div class="modal-body container" id="templates-container">
                                                                            {{-- appended buttons will be placed here --}}
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- view templates modal --}}
                                                    <button class="btn btn-primary col-12" type="button" id="view-templates-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                            <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                            <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                                          </svg>
                                                        &nbsp; View Templates
                                                    </button>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label for="">Mode of Procurement</label>
                                                        <input type="text" class="form-control d-none" value="" id="mode-procurement-input" name="mode_of_procurement" disabled>
                                                        <p class="form-control" id="mode-procurement-p" style="display: none"></p>
                                                        <select name="mode_of_procurement" id="mode-procurement-select" class="form-control">
                                                            <option value="">-- Choose Option --</option>
                                                            @for ($i = 0; $i < count($mode_of_procurements); $i++)
                                                                <option value="{{ $mode_of_procurements[$i]['mode_of_procurement'] }}">{{ $mode_of_procurements[$i]['mode_of_procurement'] }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <label for="">Expected Month</label>
                                                        <select name="expected_month" id="" class="form-control">
                                                            <option value="">-- Choose Option --</option>
                                                            @for ($i = 0; $i < 12; $i++)
                                                                <option value="{{ (new AESCipher)->encrypt(($i) + 1) }}">{{ (new GlobalDeclare)->Month(($i) + 1) }}</option>
                                                            @endfor
                                                    </select>
                                                    
                                                    <button class="btn btn-success mt-2 col-sm-12" type="submit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" 
                                                            class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                        </svg>
                                                        &nbsp; Add item
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    @for ($i = 0; $i < count($ppmp_response); $i++)
      @php
        //number_format($total_estimated_price,2,'.',','
          $total_estimated_price += doubleVal($ppmp_response[$i]['estimated_price']);
          $remaining_balance = $allocated_budget - $total_estimated_price;
          if($remaining_balance <= 0) {
            $remaining_balance = 0.0;
          }
      @endphp
    @endfor
    <div class="row">
        <div class="col-12 ">
            <div class="card p-1">
                <div class="row p-1 bg-secondary">
                    <div class="col-4 text-center">
                        <h5 class="text-success"><strong>Allocated Budget:</strong>&nbsp; ₱ {{ number_format($allocated_budget,2,'.',',') }}</h5>
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
                   <table class="table  scrollable zero-configuration item-table " id="item-table">
                       <thead>
                           <tr>
                               <th>#</th>
                               <th>Item name</th>
                               <th>app type</th>
                               <th>estimated price</th>
                               <th>item description</th>
                               <th>quantity</th>
                               <th>unit of measurement</th>
                               <th>mode of procurement</th>
                               <th>expected month</th>
                               <th>status</th>
                               <th>date created</th>
                               <th>Action</th>
                           </tr>
                       </thead>
                       <tbody>
                            {{-- showing ppmp data based on department and user --}}
                                @for ($i = 0; $i < count($ppmp_response); $i++)
                                   <tr>
                                   
                                       <td>{{ ($i) + 1 }}</td>
                                       <td>{{ $ppmp_response[$i]['item_name'] }}</td>
                                       <td>{{ $ppmp_response[$i]['app_type'] }}</td>
                                       <td>₱{{ number_format($ppmp_response[$i]['estimated_price'],2,'.',',')  }}</td>
                                       <td class="">{{ $ppmp_response[$i]['item_description'] }}</td>
                                       <td>{{ $ppmp_response[$i]['quantity'] }}</td>
                                       <td>{{ $ppmp_response[$i]['unit_of_measurement'] }}</td>
                                       <td>{{ $ppmp_response[$i]['mode_of_procurement'] }}</td>
                                       <td>{{  (new GlobalDeclare)->Month($ppmp_response[$i]['expected_month']) }}</td>
                                        @if (Str::ucfirst((new GlobalDeclare)->status($ppmp_response[$i]['status'])) == 'approved')
                                            <td class="text-success">{{ Str::ucfirst((new GlobalDeclare)->status($ppmp_response[$i]['status']))  }}</td>
                                        @else
                                            <td class="text-danger">{{ Str::ucfirst((new GlobalDeclare)->status($ppmp_response[$i]['status'])) }}</td>
                                        @endif
                                       <td>{{ explode('-', date('j F, Y-', strtotime($ppmp_response[$i]['updated_at'])))[0] }}</td>
                                       <td>
                                           <div class="dropdown">
                                               <span
                                                   class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                               </span>
                                               <div class="dropdown-menu dropdown-menu-left">
                                                {{-- edit --}}
                                                    <input type="text" class="form-control d-none" id="edit-item-name-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['item_name'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-item-name-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['item_name'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-quantity-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['quantity'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-unit-price-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['unit_price'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-item-category-{{ $ppmp_response[$i]['id']}}" value="{{ $ppmp_response[$i]['item_category'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-estimated-price-{{ $ppmp_response[$i]['id']}}" value="{{ $ppmp_response[$i]['estimated_price'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-unit-of-measurement-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['unit_of_measurement'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-item-description-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['item_description'] }}">
                                                    <input type="text" class="form-control d-none" id="edit-mode-of-procurement-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['mode_of_procurement'] }}">
                                                    <input type="text" class="form-control d-none" data-value="{{ (new GlobalDeclare)->Month($ppmp_response[$i]['expected_month']) }}" id="edit-expected-month-{{ $ppmp_response[$i]['id'] }}" value="{{ $ppmp_response[$i]['expected_month'] }}">
                                                {{-- edit --}}
                                                    <a class="dropdown-item" data-id = "{{ $ppmp_response[$i]['id'] }}" data-date = "{{ $ppmp_response[$i]['expected_month'] }}" data-toggle = "modal" id="edit-item-btn" href = ""
                                                        id="edit-title-btn">
                                                        <i class="bx bx-edit-alt mr-1"></i> edit
                                                    </a>
                                                    <form action="{{ route('department-delete-item') }}" method="post"> @csrf @method('POST')
                                                        <input type="text" class="form-control d-none" value="{{ (new AESCipher)->encrypt($ppmp_response[$i]['id']) }}" name="id">
                                                        <button class="dropdown-item" id="delete-item-btn" type = "submit">
                                                            <i class="bx bx-trash mr-1"></i> delete
                                                        </button>
                                                    </form>
                                               </div>
                                           </div> 
                                       </td>
                                   </tr>
                                    
                                @endfor
                                {{-- edit item modal --}}
                                    <div class="modal fade" id="edit-item-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-xl modal-dialog" role="document">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal-header">Edit PPMP item detail(s)</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('department-update-item') }}" method="POST"> @csrf @method('POST')
                                                    <div class="modal-body row">
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <label for=""> Item Name</label>
                                                                <input type="text" class="form-control d-none" name="id" id="default-id" value="somehtihahwdha">
                                                                <select name="item_name" id="default-item-name-select" class="form-control">
                                                                    <option value="" id="default-item-name" selected>  {{-- default selected item name --}}</option>
                                                                        @foreach ($items as $item)
                                                                            <option data-name="{{ (new AESCipher)->encrypt($item->item_name) }}" value="{{ (new AESCipher)->encrypt($item->item_name) }}**{{ (new AESCipher)->encrypt($item->app_type) }}**{{ $ProjectTitleResponse[0]['id'] }}">{{ $item->item_name }}</option>
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for=""> Quantity </label>
                                                                <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                                                    id="default-quantity" autocomplete="quantity" autofocus>
                                                                    @error('item_name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for=""> Unit Price </label>
                                                                <input type="text" name="unit_price" class="form-control @error('quantity') is-invalid @enderror"
                                                                    id="default-unit-price" autocomplete="quantity" autofocus>
                                                                    @error('item_name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for=""> APP Type</label>
                                                                <input type="text" class="form-control d-none @error('item_category') is-invalid @enderror"
                                                                    name="item_category" autocomplete="item_category" autofocus
                                                                    id="default-item-category">
                                                                    @error('estimated_price')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                <p class="form-control" id="default-item-category-p">-- None --</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Unit of Measure</label>
                                                                <select name="unit_of_measurement" id="" class="form-control">
                                                                    <option value="" id="default-unit-of-measurement">-- Choose Option --</option>
                                                                    @for ($i = 0; $i < count($unit_of_measurements); $i++)
                                                                        <option value="{{ $unit_of_measurements[$i]['unit_of_measurement'] }}">{{ $unit_of_measurements[$i]['unit_of_measurement'] }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for=""> Estimated Price</label>
                                                                <input type="text" class="form-control d-none @error('estimated_price') is-invalid @enderror"
                                                                    name="estimated_price" autocomplete="estimated_price" autofocus
                                                                    id="default-estimated-price">
                                                                    @error('estimated_price')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                <p class="form-control" id="default-estimated-price-p">-- None --</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="">Item Description</label>
                                                                <textarea class="form-control @error('item_description') is-invalid @enderror" 
                                                                    name="item_description" id="default-item-description" cols="15" rows="5">
                                                                </textarea>
                                                                    @error('item_description')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                            {{-- edit view templates modal --}}
                                                                <div class="modal fade" id="edit-templates-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="modal-header">Item descriptions for: &nbsp;<strong id="item-name-template">Something</strong> </h5>
                                                                                <button type="button" class="close" id="edit-close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                                <div class="modal-body container" id="edit-templates-container">
                                                                                     {{-- appended buttons will be placed here --}}
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {{-- edit view templates modal --}}
                                                            <button class="btn btn-primary col-12" type="button" id="edit-templates-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                                                  </svg>
                                                                &nbsp; View Templates
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="">Mode of Procurement</label>
                                                                <select name="mode_of_procurement" class="form-control">
                                                                    <option value="" id="default-mode-of-procurement"></option>
                                                                    @for ($i = 0; $i < count($mode_of_procurements); $i++)
                                                                        <option value="{{ $mode_of_procurements[$i]['mode_of_procurement'] }}">{{ $mode_of_procurements[$i]['mode_of_procurement'] }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
        
                                                            <label for="">Expected Month</label>
                                                            <select name="expected_month" class="form-control" >
                                                                <option value="" id="default-expected-month"> </option>
                                                                    @for ($i = 0; $i < 12; $i++)
                                                                        <option value="{{ (new AESCipher)->encrypt(($i) + 1) }}">{{ (new GlobalDeclare)->Month(($i) + 1) }}</option>
                                                                    @endfor
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer" id = "footModal">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                                                        <button type="submit" class="btn btn-success">Save changes</button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                {{-- end --}}
                                {{-- view templates modal for editing of ppmps data --}}
                                    <div class="modal fade" id="edit-templates-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog" role="document">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal-header">Item descriptions for: &nbsp;<strong id="edit-item-name">ff</strong> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                    <div class="modal-body container" id="edit-templates-container">
                                                        {{-- appended buttons will be placed here --}}
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                {{-- end view templates modal for editing of ppmps data --}}
                           {{-- showing ppmp data based on department and user --}}
                       </tbody>
                       </table>
               </div>
                    {{-- adding submit ppmp feature --}}
                    <div class="form-group p-1 row justify-content-center">
                        <div class="form-group col-6">
                            <form action="{{ route('department-submit-ppmp') }}" method="post">@csrf @method('POST')
                                <input type="text" value="{{ (new AESCipher)->encrypt($project_title_id) }}" class="form-control d-none" name="current_project_code">
                                <input type="text" class="form-control d-none" name="remaining_balance" value="{{ $remaining_balance }}">
                                <input type="text" class="form-control d-none" name="allocated_budget" value="{{ $allocated_budgets[0]->id }}">
                                <input type="text" class="form-control d-none" name="deadline_of_submission" value="{{ $allocated_budgets[0]->deadline_of_submission }}">
                                <button type="submit" class="btn btn-success form-control">Submit PPMP</button>
                            </form>
                        </div>
                    </div>
                    {{-- end adding submit ppmp feature --}}
              
                {{-- creating data tables for the list of project titles --}}
            </div>
        </div>
    </div>

</section>
{{-- Torrexx | Code not minde --}}
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
{{-- Torrexx | Code not minde --}}
<script>
    // this will submit when add item button is clicked
        $('#edit-title-btn').click(function (e) {
            e.preventDefault();
            $('#viewmodal').show();
        });
    // end
    // this will get the item description based item name on change event
        $('#item-name-select').change(function() {
            var item_name = '';
            $('#item-name-val').val($(this).children('option:selected').val());
            // alert($(this).children('option:selected').text());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('department-item-category') }}",
                method: 'POST',
                data: {
                    'item_name' : $(this).children('option:selected').text()
                }, success: function(response) { 
                    if(response['status'] == 200) {
                        // changing value of APP type text 
                        $('#item-category').val(response['data'][0]['app_type']);
                        $('#item-category-p').text(response['data'][0]['app_type']);
                        // changing mode of procurement
                        if(response['data'][0]['public_bidding'] == 1) {
                            $('#mode-procurement-select').css('display', 'none');
                            $('#mode-procurement-select').prop( "disabled", true );
                           
                            $('#mode-procurement-input').prop( "disabled", false );
                            $('#mode-procurement-input').val('Public Bidding');
                            $('#mode-procurement-p').css('display', 'block');
                            $('#mode-procurement-p').text('Public Bidding')
                        } else {
                            $('#mode-procurement-select').css('display', 'block');
                            $('#mode-procurement-select').prop( "disabled", false );

                            $('#mode-procurement-input').prop( "disabled", true );
                            $('#mode-procurement-input').val('');
                            $('#mode-procurement-p').css('display', 'none');
                            $('#mode-procurement-p').text('')
                        }
                    } 
                    if(response['status'] == 400) {
                       
                    }
                } 
            });
        });
        $('#default-item-name-select').change(function() {
            var item_name = '';
            $('#item-name-val').val($(this).children('option:selected').val());
            // alert($(this).children('option:selected').text());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('department-item-category') }}",
                method: 'POST',
                data: {
                    'item_name' : $(this).children('option:selected').text()
                }, success: function(response) { 
                    if(response['status'] == 200) {
                        $('#default-item-category').val(response['data'][0]['app_type']);
                        $('#default-item-category-p').text(response['data'][0]['app_type']);
                    } 
                    if(response['status'] == 400) {
                       // code here
                    }
                } 
            });
        });
        
    // end
    // this will enable view templates modal upon button click
        $('#view-templates-btn').click(function() {
            $('#templates-modal').modal('show');
            // appending value on template modal
            $('#item-name-template').text($('#item-name-select').children('option:selected').text());
                // this will get all the item descriptions based on the given item name
                $('#templates-container').html('');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    },
                    url: "{{ route('department-item-description') }}",
                    method: 'POST',
                    data: {
                        'item_name' : $('#item-name-select').children('option:selected').text()
                    }, success: function(response) { 
                        if(response['status'] == 200) {
                            // this will loop through all the response data
                            for(var i = 0; i < response['data'].length; i++) {
                                console.log(response['data'][i]['item_description']);
                                // this will append all 
                                $('#templates-container').append('<button class="btn btn-light col-12 m-1" type="button" id="templates-btn" data-dismiss="modal" aria-label="Close">' + response['data'][i]['item_description']  + '</button>');
                            }
                        } 
                        if(response['status'] == 400) {
                            $('#templates-container').append('<p class="col-12 text-center m-1" >' + response['message']  + '</p>');
                        }
                    } 
                });
        });

        // this will append the clicked item description template to item description textarea
            $(document).on('click', '#templates-container #templates-btn', function() {
            console.log($(this).text());
            // append clicked button text to item description textarea
                $('#item-description-textarea').val($(this).text());
            });
    // end
    // this will show the modal for item edit feature
       $(document).on('click', '#edit-item-btn', function() {
        var pars = $('#edit-expected-month-'+$(this).attr('data-id')).val();
        var text = $('#edit-expected-month-'+$(this).attr('data-id')).val();
        var expected_date = $(this).attr('data-date');
            $('#edit-item-modal').modal('show');
            // this will append the item details to modal elements
             /* default */
                $('#default-id').val($(this).attr('data-id'));
                $('#default-item-name').val($('#edit-item-name-'+$(this).attr('data-id')).val());
                $('#default-item-name').text($('#edit-item-name-'+$(this).attr('data-id')).val());
                $('#default-quantity').val($('#edit-quantity-'+$(this).attr('data-id')).val());
                $('#default-unit-price').val($('#edit-unit-price-'+$(this).attr('data-id')).val());
                $('#default-item-category').val($('#edit-item-category-'+$(this).attr('data-id')).val());
                $('#default-item-category-p').text($('#edit-item-category-'+$(this).attr('data-id')).val());
                $('#default-estimated-price').val($('#edit-estimated-price-'+$(this).attr('data-id')).val());
                $('#default-estimated-price-p').text($('#edit-estimated-price-'+$(this).attr('data-id')).val());
                $('#default-unit-of-measurement').val($('#edit-unit-of-measurement-'+$(this).attr('data-id')).val());
                $('#default-unit-of-measurement').text($('#edit-unit-of-measurement-'+$(this).attr('data-id')).val());
                $('#default-item-description').val($('#edit-item-description-'+ $(this).attr('data-id')).val());
                $('#default-mode-of-procurement').val($('#edit-mode-of-procurement-'+$(this).attr('data-id')).val());
                $('#default-mode-of-procurement').text($('#edit-mode-of-procurement-'+$(this).attr('data-id')).val());
                $('#default-expected-month').val(expected_date);
                $('#default-expected-month').text($('#edit-expected-month-'+$(this).attr('data-id')).attr('data-value'));
             /* default */
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
    
    // calculating estimated price
        $(document).on('change', '#unit-price', function() {
            var quantity = $('#quantity-input').val();
            var unit_price = $('#unit-price').val();
            $('#estimated-price').val(quantity * unit_price);
            $('#estimated-price-p').text(quantity * unit_price);
        });

        $(document).on('change', '#default-unit-price', function() {
            var quantity = $('#default-quantity').val();
            var unit_price = $('#default-unit-price').val();
            $('#default-estimated-price').val(quantity * unit_price);

            $('#default-estimated-price-p').text(quantity * unit_price);
            // $('#default-estimated-price-p').text(quantity * unit_price);
        });
    // end
    
</script>
@endsection

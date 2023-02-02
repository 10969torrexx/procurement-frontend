@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Items')
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
  {{-- @foreach($data as $data) --}}
  <div class="card">
    <!-- Greetings Content Starts -->
      <div class="card">
        <section id="basic-datatable">
          <div class="card ">
              <div class="card col-12 mt-2">
                {{-- <div class="card-header"><div class="col-sm-3 ">
                </div> --}}
                <div class="row" >
                  <div class="col-md-2 ml-1"  >
                    <label>Add Item</label>
                    <input type="text" name="item_name" id="item_name" placeholder="Item Name" class="form-control item_name" required>
                  </div> 
                  <div class="col-md-2 " >
                    <label>Category:</label>
                    <select class="form-select form-control item_category" aria-label="Default select example">
                      <option selected>Choose..</option>
                      {{-- @foreach($data1 as $data1) --}}+
                      {{-- @for ($i = 0; $i < count($data[1]); $i++) --}}
                      @foreach($category as $categories)
                        <option value="{{ $categories->category }}">{{ $categories->category }}</option>
                      @endforeach
                      
                      {{-- @endforeach --}}
                    </select>
                  </div>
                  <div class="col-md-2" >
                    <label>APP Type:</label>
                    <select class="form-select form-control item_type" aria-label="Default select example">
                      <option selected>Choose..</option>
                      <option value="CSE">CSE</option>
                      <option value="Non-CSE">Non - CSE</option>
                    </select>
                  </div>

                  {{-- <div class="col-md-2" >
                    <label for="public_bidding">Public Bidding:</label>
                    <select name="public_bidding" class="form-select form-control public_bidding" aria-label="Default select example">
                      <option selected>Choose..</option>
                      <option value="0">Not Required</option>
                      <option value="1">Required</option>
                    </select>
                  </div> --}}
                  
                  <div class="col-md-2 mp">
                    <label for="mode_of_procurement">Mode of Procurement:</label>
                    <select name="mode_of_procurement" class="form-select form-control mode_of_procurement" aria-label="Default select example">
                      <option value="0" selected>Choose..</option>
                      @foreach($mode as $mode)
                        <option value="{{ $mode->id }}">{{ $mode->mode_of_procurement }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-1" >
                    <br/>
                    <button type="submit" id ="add-item" class="btn btn-success add-item form-control">Add</button>
                  </div>
                </div>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration item-table" id="item-table">
                      <thead>
                          <tr>
                              <th>Item Name</th>
                              <th>Category</th>
                              <th>APP TYPE</th>
                              <th>Mode Of Procurement</th>
                              <th>Added By</th>
                              <th>Campus</th>
                              <th>Date Added</th>
                              <th>Date Updated</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      {{-- @for ($i = 0; $i < count($data[0]); $i++) --}}
                      <tbody>
                            @foreach($item as $items)
                          <tr>
                              <td>{{ $items->item_name }}</td>
                              <td>{{ $items->item_category }}</td>
                              <td>{{ $items->app_type }}</td>
                              <td>{{ $items->mode_of_procurement }}
                                  {{-- <input type="hidden" class="campusCheck" value="{{ $campuscount }}"> --}}

                                {{-- <?php
                                  if($items->public_bidding == 0){
                                  echo $items->mode_of_procurement;
                                  }elseif($items->public_bidding == 1){
                                  echo 'Public Bidding';
                                  }
                                ?>  --}}
                                {{-- @if($items->public_bidding == 0)
                                  <?php $pro = ""; ?>
                                  @foreach($procurement as $procurements)
                                    <?php if( $procurements->id == $items->mode_of_procurement_id )
                                      $pro = $procurements->mode_of_procurement;
                                    ?>
                                  @endforeach
                                  {{ $pro }}
                                @endif
                                @if($items->public_bidding == 1)
                                  Public Bidding
                                @endif --}}
                              </td>
                              <td>{{ $items->name }}</td>
                              <td>{{(new GlobalDeclare)->Campus($items->campus)}}</td>
                              <td>{{ explode('-', date('j F, Y-', strtotime($items->created_at)))[0] }}</td>
                              <td>{{ explode('-', date('j F, Y-', strtotime($items->updated_at)))[0] }}</td>
                              <td>
                                @if($items->campus == session('campus'))
                                  <div class="dropdown">
                                    <span
                                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                    <div class="dropdown-menu dropdown-menu-left">
                                      <a class="dropdown-item edit" data-id = "<?=$aes->encrypt($items->id)?>" value="{{ $items->mode_of_procurement }}" data-toggle = "modal" id="edit_item_Modal" href = "{{ $aes->encrypt($items->id) }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                      <a class="dropdown-item delete-item" href = "{{ $aes->encrypt($items->id) }}">
                                        <i class="bx bx-trash mr-1"></i> delete
                                      </a>
                                    </div>
                                  </div> 
                                @endif
                              </td>
                          </tr>
                            @endforeach 
                      </tbody>
                      {{-- @endfor --}}
                      
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
  </div>
  {{-- @endforeach --}}
  @include('pages.bac.edit-item-modal')
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
{{-- <script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script> --}}
{{-- <script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script> --}}


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
{{-- <script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script> --}}

<script src="{{asset('js/bac/item.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
{{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
 {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}




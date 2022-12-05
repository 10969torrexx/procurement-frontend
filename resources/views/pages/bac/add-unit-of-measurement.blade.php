@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Unit of Measurement')
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
        <section id="basic-datatable" >
          <div class="card">
            <div class="col-12">
              <div class="card">
                <div class="card-header"><div class="col-sm-3 ">
                        <label>Add Unit of Measurement</label>
                </div>
                <div class="row" >
                  <div class="col-md-6 ml-1"  >
                    <input type="text" name="unit-of-measurement" id="unit-of-measurement" placeholder="Unit Of Measure" class="form-control unit-of-measurement">
                  </div> 
                  <div class="col-md-2">
                    <button type="submit" id ="add-unit" class="btn btn-success form-control add-unit">Add</button>
                  </div>
                </div>
              </div>
              <div class="card-content">
              <div class="card-body card-dashboard">
                <div class="table-responsive">
                  <table class="table zero-configuration item-table responsive" id="item-table">
                    <thead>
                      <tr>
                        <th>Unit of Measurements</th>
                        {{-- <th>Campus</th> --}}
                        <th>Added By</th>
                        <th>Date Added</th>
                        <th>Date Updated</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data1 as $data1)
                        <tr>
                          <td maxlength="6">{{ $data1->unit_of_measurement }}</td>
                          {{-- <td>{{ (new GlobalDeclare)->Campus(IntVal($data1->campus)) }}</td> --}}
                          <td>{{ $data1->name }}</td>
                          <td>{{ explode('-', date('j F, Y- g:i a', strtotime($data1->created_at)))[0] }}</td>
                          <td>{{ explode('-', date('j F, Y- g:i a', strtotime($data1->updated_at)))[0] }}</td>
                          <td>
                            <div class="dropdown">
                              <span
                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-left">
                                  <a class="dropdown-item edit" data-id = "<?=$aes->encrypt($data1->id)?>" data-toggle = "modal" id="editModal" href = "{{ $aes->encrypt($data1->id) }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                  <a class="dropdown-item delete-unit" href = "{{ $aes->encrypt($data1->id) }}">
                                      <i class="bx bx-trash mr-1"></i> delete
                                  </a>
                              </div>
                          </div> 
                          </td> 
                        </tr>
                      @endforeach
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
  </div>
  @include('pages.bac.edit-modal')
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

<script src="{{asset('js/bac/unitofmeasurement.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
{{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
 {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}




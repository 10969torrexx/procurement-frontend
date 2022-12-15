@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Mode of Procurement')
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
  <div class="row">
      <div class="card">
        <section id="basic-datatable">
          <div class="card">
            <div class="col-12">
              <div class="card">
                <div class="card-header"><div class="col-sm-3 ">
                  <label>Add Mode of Procurement</label>
                </div>
                <div class="row" >
                  <div class="col-md-4 ml-1"  >
                    <input type="text" name="mode-of-procurement" id="mode-of-procurement" placeholder="Mode of Procurement" class="form-control mode-of-procurement">
                  </div> 
                  <div class="col-md-2 ml-1">
                    <input type="text" name="mode-of-procurement -abv" id="mode-of-procurement-abv" placeholder="Abbreviation" class="form-control mode-of-procurement-abv">
                  </div>
                  <div class="col-md-2">
                    <button type="submit" id ="add-procurement" class="btn btn-success form-control add-procurement">Add</button>
                  </div>
                </div>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration item-table table-responsive" id="item-table">
                      <thead>
                        <tr>
                          <th>Action</th>
                          <th>Mode of Procurement</th>
                          <th>Abbreviation</th>
                          <th>Added By</th>
                          <th>Date Added</th>
                          <th>Date Updated</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($data as $data)
                          <tr>

                            <td>
                              <div class="dropdown">
                                <span
                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                <div class="dropdown-menu dropdown-menu-left">
                                  <a class="dropdown-item edit" data-id = "<?=$aes->encrypt($data->id)?>" data-toggle = "modal" id="editModal" href = "{{ $aes->encrypt($data->id) }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                  <a class="dropdown-item delete-procurement" href = "{{ $aes->encrypt($data->id) }}">
                                    <i class="bx bx-trash mr-1"></i> delete
                                  </a>
                                </div>
                              </div> 
                            </td> 
                            <td >{{ $data->mode_of_procurement }}</td>
                            <td >{{ $data->abbreviation }}</td>
                            <td>{{ (new GlobalDeclare)->Campus($data->campus) }} Campus</td>
                            {{-- <td>{{ $data->name }}</td> --}}
                            <td>{{ explode('-', date('j F, Y- g:i a', strtotime($data->created_at)))[0] }}</td>
                            <td>{{ explode('-', date('j F, Y- g:i a', strtotime($data->updated_at)))[0] }}</td>
                            
                          </tr>
                        @endforeach
                      </tbody>
                     
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
  </div>
  @include('pages.admin.update_mode_of_procurement_modal')
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

<script src="{{asset('js/admin/modeofprocurement.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
{{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
 {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}




@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Category')
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
        <section id="basic-datatable" >
            <div class="card-header row " >
                <div class="col-md-6 ml-1"  >
                    <label>Add Category</label>
                    <input type="text" name="category" id="category" placeholder="Category" class="form-control category">
                </div> 
                <div class="col-md-2">
                    <label class="mt-1"></label>
                    <button type="submit" id ="add-category" class="btn btn-success add-category form-control ">Add</button>
                </div>
            </div>
            <div class="card-body card-dashboard">
                <div class="table-responsive">
                    <table class="table zero-configuration item-table " id="item-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Campus</th>
                                <th>Added By</th>
                                <th>Date Added</th>
                                <th>Date Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $data)
                            <tr>
                            <td>{{ $data->category }}</td>
                            <td>{{ (new GlobalDeclare)->Campus(IntVal($data->campus)) }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ explode('-', date('j F, Y- g:i a', strtotime($data->created_at)))[0] }}</td>
                            <td>{{ explode('-', date('j F, Y- g:i a', strtotime($data->updated_at)))[0] }}</td>
                            <td>
                                @if($data->campus == session('campus'))
                                    <div class="dropdown">
                                        <span
                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            <a class="dropdown-item edit" data-id = "<?=$aes->encrypt($data->id)?>" data-toggle = "modal" id="editModal" href = "{{ $aes->encrypt($data->id) }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                            <a class="dropdown-item delete-category " href = "{{ $aes->encrypt($data->id) }}">
                                                <i class="bx bx-trash mr-1"></i> delete
                                            </a>
                                        </div>
                                    </div> 
                                @endif
                            </td> 
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        
                    </table>
                </div>
            </div>
        </section>
  </div>
  @include('pages.bac.edit-modal')
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script src="{{asset('js/bac/category.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>




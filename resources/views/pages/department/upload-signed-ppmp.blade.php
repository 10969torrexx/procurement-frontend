@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Dashboard')
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
      <!-- Greetings Content Starts -->
      <div class="col-xl-12 col-md-6 col-12 dashboard-greetings">
        <div class="card">
            <div class="card-body">
                    <div class="col-sm-3 ">
                        <label>Attach file here:</label>
                    </div>
                    <form action="/fileupload" method="post" enctype="multipart/form-data" >
                      @csrf
                      <div class="row">
                          <div class="col-md-10">
                              <input type="file" name="file" class="form-control">
                          </div>
               
                          <div class="col-md-2">
                              <button type="submit" class="btn btn-success">Upload</button>
                          </div>
                      </div>
                      @if(session('message'))
                        <div class="alert alert-success col-sm-10 mt-1">
                          {{ session('message') }}
                        </div>
                      @endif
                  </form>
            </div>
        </div>
      </div>
        <div class="card">
          <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Zero configuration</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <p class="card-text">DataTables has most features enabled by default, so all you need to do to
                                    use it with your own tables is to call the construction function: $().DataTable();.</p>
                                <div class="table-responsive">
                                    <table class="table zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Position</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($data as $data)
                                            <tr>
                                                <td>{{ $data['file'] }}</td>
                                                <td><a href="#"><i class="fa-solid fa-eye fa-sm pl-1" style="color:blue" title="view"></i></a> <a href="#"><i class="fa-sharp fa-solid fa-trash-can fa-sm pl-1" style="color:red" title="delete" ></i></a></td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Position</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script>


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script>
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection


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
  <div class="card">
    <!-- Greetings Content Starts -->
        <section id="basic-datatable">
                <div class="card-content">
                    <div class="card-body card-dashboard" >
                        <div class="table-responsive">
                                    
                            <table class="table zero-configuration item-table" id="item-table">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>File Name</th>
                                    <th>Year Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
    
                            
                            @foreach ($data as $line)
                            <tbody>
                                <tr>
                                    <td>{{ $line->department_name }}</td>
                                    <td>{{ $line->file_name }}</td>
                                    <td>{{ $line->year_created }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <span
                                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-left">
                                                <a href= "{{ route('download-signed-ppmp', ['id' => (new AESCipher)->encrypt($line->id) ]) }}" class="dropdown-item">
                                                  <i class="fa-solid fa-file-arrow-down mr-1"></i>Download
                                                </a>
                                                <button type="button" class="dropdown-item viewPPMP" value="{{ (new GlobalDeclare)->pres_status($line->project_category) }}" data-id="<?=$aes->encrypt($line->id)?>" data-toggle = "modal" data-target = "#preview-ppmp"> <i class="fa-solid fa-eye"></i>&nbsp; View</button>
                                            </div>
                                        </div> 
                                    </td>
                                    
                                </tr>
                            </tbody>
                            @endforeach
                            </table>
                        </div>
                    </div>
                </div>
        </section>
                            @include('pages.bac.ppmp.view-approved-modal')
                            @include('pages.bac.ppmp.loading')
                            @include('pages.bac.ppmp.signed-ppmp-modal')
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>

<script>
    $(document).on('click', '.viewPPMP', function(e) {
       e.preventDefault();
       $('#preview-ppmp').modal('show');
       $('#content').html('');
       // appending content
       $.ajax({
          headers: {
             'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
          },
          url: "view-signed-ppmp",
          method: 'post',
          data: {
             'id' : $(this).attr('data-id')
          }, success: function(response) {
             console.log(response);
             response['data'].forEach(element => {
                   $('#content').append(`<iframe src="{{asset("storage/department_upload/signed_ppmp/`+ element.signed_ppmp +`")}}" style="width:100% !important;" height="750" frameborder="0"></iframe>`);
             });
          }
       });
    });
</script>
<script src="{{asset('js/bac/approvedppmp.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>





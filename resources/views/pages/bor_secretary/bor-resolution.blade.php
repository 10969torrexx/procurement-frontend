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
@section('title','Upload BOR Resolution')
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
{{-- view bor resolution --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="view-resolution-modal" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">View BOR Resolution: <strong id="bor-title"></strong> </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="p-1" id="content">
              </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary">Close</button>
            </div>
        </div>
      </div>
    </div>
{{-- end --}}
{{-- edit uploaded ppmp --}}
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="edit-resolution-modal" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Uploaded PPMP</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="" id="uploaded-ppmp-content">
              <div class="row justify-content-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                      <form action="{{ route('edit_bor_resolution') }}" method="POST" enctype="multipart/form-data"> 
                        @csrf
                          <input type="text" name="id" class="form-control d-none" hidden id="default-id">
                          <div class="form-group row">
                              <div class="col-6 col-sm-6 col-lg-6">
                                <label for="">BOR Resolution Title</label>
                                <textarea name="bor_title" class="form-control" cols="30" rows="1" required id="default-bor-title"></textarea>
                              </div>
                              <div class="col-6 col-sm-6 col-lg-6">
                                <label for="">BOR Resolution Scope</label>
                                <select name="department" id="" class="form-control" required>
                                    <option value="" id="default-department"></option>
                                    <option value="{{ (new AESCipher)->encrypt(0) }}">All Departments</option>
                                    @foreach ($departments as $item)
                                      <option value="{{ (new AESCipher)->encrypt($item->id) }}">{{ $item->department_name }} | {{ $item->description }} </option>
                                    @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="">BOR Resolution File</label>
                              <input type="file" name="bor_file" id="signed-ppmp" class="form-control" required>
                              <p class="card-text alert bg-rgba-info">Attach the desired file here (.docx, .pub .pdf, .jpeg, .jpg, .png) files</p>
                          </div>
                          <button type="submit" class="btn btn-success text-white" id="upload-signed-ppmp">Upload BOR Resolution</button>
                      </form>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>
{{-- end --}}

<section id="dropzone-examples">
    <!-- Upload BOR Resolution -->
        @if (session('role') == 13)
          <div class="row">
              <div class="col-12">
                  <div class="card">
                  <div class="card-header p-1">
                      <h4 class="card-title text-primary border-bottom pb-1">
                      <strong>Upload BOR Resolution</strong>
                      </h4>
                  </div>
                  <div class="card-content">
                      <div class="card-body">
                          <div class="row justify-content-center">
                              <div class="col-5 col-sm-5 col-md-5 col-lg-5">
                              <form action="{{ route('upload_bor_resolution') }}" method="POST" enctype="multipart/form-data"> @csrf
                                  <div class="form-group row">
                                      <div class="col-6 col-sm-6 col-lg-6">
                                        <label for="">BOR Resolution Title</label>
                                        <textarea name="bor_title" class="form-control" cols="30" rows="1" required></textarea>
                                      </div>
                                      <div class="col-6 col-sm-6 col-lg-6">
                                        <label for="">BOR Resolution Scope</label>
                                        <select name="department" id="" class="form-control" required>
                                            <option value="{{ (new AESCipher)->encrypt(0) }}">All Departments</option>
                                            @foreach ($departments as $item)
                                              <option value="{{ (new AESCipher)->encrypt($item->id) }}">{{ $item->department_name }} | {{ $item->description }} </option>
                                            @endforeach
                                        </select>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="">BOR Resolution File</label>
                                      <input type="file" name="bor_file" id="signed-ppmp" class="form-control" required>
                                      <p class="card-text alert bg-rgba-info">This can only process (.docx, .pub .pdf, .jpeg, .jpg, .png) files</p>
                                  </div>
                                  <button type="submit" class="btn btn-success text-white" id="upload-signed-ppmp">Upload BOR Resolution</button>
                              </form>
                              </div>
                          </div>
                      </div>
                  </div>
                  </div>
              </div>
          </div>
        @endif
    <!-- Upload BOR Resolution -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                <h4 class="card-title text-primary border-bottom pb-1">
                    <strong>BOR Resolution</strong>
                </h4>
                </div>
                <div class="card-content">
                <div class="card-body">
                    {{-- search BOR Resolution --}}
                        <form action="{{ route('search_bor_resolution') }}" method="post">
                          @csrf
                            <div class="row">
                              <div class="col-8 col-md-8 col-sm-8 col-lg-8 p-1 row">
                                  <div class="col-6 col-md-6 col-sm-6 col-lg-6">
                                      <textarea name="bor_title" class="form-control" required id="bor-title" cols="30" rows="1" placeholder="BOR Title"></textarea>
                                  </div>
                                  <div class="col-3 col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-success text-white" id="upload-signed-ppmp">Search</button>
                                  </div>
                              </div>
                            </div>
                        </form>
                    {{-- search BOR Resolution --}}
                    {{-- list of uploaded BOR Resolution --}}
                        <div class="table-responsive col-12 container">
                            <table class="table zero-configuration item-table" id="item-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>BOR Title</th>
                                        <th>Year Created</th>
                                        <th>Department</th>
                                        <th>Description</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- showing uploaded bor resolution here --}}
                                      @foreach ($response as $item)
                                          <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-nowrap">{{ $item->bor_title }}</td>
                                            <td>{{ $item->year_created }}</td>
                                            <td class="text-nowrap">{{ $item->department_name }}</td>
                                            <td class="text-nowrap">{{ $item->description }}</td>
                                            <td class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                            <td>
                                              <div class="dropdown">
                                                  <span
                                                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                  </span>
                                                  <div class="btn dropdown-menu dropdown-menu-left">
                                                      @if (session('role') == 13)
                                                        <a href = "" id="edit-resolution-btn" data-id="{{ (new AESCipher)->encrypt($item->id) }}" class="dropdown-item">
                                                          <i class="bx bx-edit-alt mr-1"></i>Edit
                                                        </a>
                                                        <a href = "{{ route('delete_bor_resolution', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" data-id="" class="dropdown-item">
                                                          <i class="fa-solid fa-trash mr-1"></i>delete
                                                        </a>
                                                      @endif
                                                      <a href = "" data-id="{{ (new AESCipher)->encrypt($item->id) }}" id="view-resolution-btn"  class="dropdown-item">
                                                        <i class="fa-solid fa-eye mr-1"></i>View
                                                      </a>
                                                      <a href= "{{ route('download_bor_resolution', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" class="dropdown-item">
                                                        <i class="fa-solid fa-file-arrow-down mr-1"></i>Download
                                                      </a>
                                                  </div>
                                              </div> 
                                            </td>
                                          </tr>
                                      @endforeach
                                    {{-- showing uploaded bor resolution here --}}
                                </tbody>
                                
                            </table>
                        </div>
                    {{-- list of BOR Resolution --}}
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
{{-- message --}}
  @if(Session::has('success'))
    <script>
      Swal.fire({
        title: 'Success',
        icon: 'success',
        html: "{{ Session::get('success') }}",
      });
    </script>
  @endif

  @if(Session::has('error'))
    <script>
      Swal.fire({
        title: 'Error',
        icon: 'error',
        html: "{{ Session::get('error') }}",
      });
    </script>
  @endif

  @if($errors->all())
    @foreach ($errors->all() as $item)
        <script>
            Swal.fire({
                title: 'Error',
                icon: 'error',
                html: "{{ $item }}",
            });
        </script>
    @endforeach
  @endif
{{-- end message --}}
{{-- script --}}
    <script>
      // view files
        $(document).on('click', '#view-resolution-btn', function(e) {
          e.preventDefault();
          $('#view-resolution-modal').modal('show');
          $('#content').html('');
          $('#bor-title').text('');
          // appending content
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            },
            url: "{{ route('view_bor_resolution') }}",
            method: 'GET',
            data: {
              'id' : $(this).data('id')
            }, success: function(response) {
                console.log(response['data']);
                response['data'].forEach(element => {
                    $('#bor-title').text(element.bor_title);
                    $('#content').append(`<iframe src="{{asset("storage/`+ element.file_directory +`")}}" style="width:100% !important;" height="600" frameborder="0"></iframe>`);
                });
            }
          });
        });
      // preview files
      // edit 
        $(document).on('click', '#edit-resolution-btn', function(e) {
            e.preventDefault();
            $('#edit-resolution-modal').modal('show');
            // appending content
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),

              },
              url: "{{ route('get_bor_resolution') }}",
              method: 'GET',
              data: {
                'id' : $(this).data('id')
              }, success: function(response) {
                  console.log(response['data']);
                  response['data'].forEach(element => {
                    console.log(element.id);
                    $('#default-id').val(element.id);
                    $('#default-bor-title').text(element.bor_title);
                    $('#default-department').val(`{{ (new AESCipher)->encrypt(`+ element.department_id +`) }}`);
                    $('#default-department').text(element.department_name + ' | ' + element.description);
                  });
              }
            });
        });
      // end
      // search 
    </script>

{{-- end script --}}
@endsection

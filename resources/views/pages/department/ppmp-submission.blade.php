{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    use Carbon\Carbon;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $current_year = Carbon::now();
    // dd((new AESCipher)->decrypt($project_type));
@endphp
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','PPMP Submission')
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
{{-- preview uploaded ppmp --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="preview-ppmp" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Preview File:</h5>
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
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="edit-uploaded-ppmp" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                      <form action="{{ route('edit_ppmp') }}" method="POST" enctype="multipart/form-data"> 
                        @csrf
                        <div class="form-group row">
                            <input type="text" name="id" id="default-id" class="form-control d-none">
                            <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                                <label for="">PPMP / Project Category</label>
                                <select name="project_category" id="project-category" class="form-control" required>
                                  <option value="" id="default-project-category">-- Select Project Category --</option>
                                  @for ($i = 0; $i < 3; $i++)
                                      <option value="{{ (new AESCipher)->encrypt($i) }}">{{ (new GlobalDeclare)->project_category($i) }}</option>
                                  @endfor
                              </select>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                                <label for="">Year Created</label>
                                <select name="year_created" id="year-created" class="form-control" required>
                                  <option value="" id="default-year-created">-- Select Year --</option>
                                  @for ($i = 5; $i > 0; $i--)
                                      <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->subYear($i)->format('Y') }}</option>
                                  @endfor
                                  @for ($i = 0; $i < 5; $i++)
                                      <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->addYear($i)->format('Y') }}</option>
                                  @endfor
                              </select>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="">File name</label>
                          <textarea name="file_name" id="default-file-name" class="form-control" id="" cols="30" rows="1" required></textarea>
                        </div>
                        <div class="form-group">
                          <label for="">Signed PPMP File</label>
                          <input type="file" name="signed_ppmp" id="default-signed-ppmp" class="form-control">
                          <p class="card-text alert bg-rgba-info">Attach desired file here (.pdf, .jpeg, .jpg, .png) files</p>
                        </div>
                        <button type="submit" class="btn btn-success text-white" id="update-ppmp">Update PPMP</button>
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
    <!-- Upload signed ppmp starts -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-header p-1">
                    <h4 class="card-title text-primary border-bottom pb-1">
                    <strong> Request for PPMP Submission</strong>
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-5 col-sm-5 col-md-5 col-lg-5">
                            <form action="{{ route('upload_ppmp') }}" method="POST" enctype="multipart/form-data"> @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                                        <label for="">PPMP / Project Category</label>
                                        <select name="project_category" id="project-category" class="form-control" required>
                                        <option value="">-- Select Project Category --</option>
                                        @for ($i = 0; $i < 3; $i++)
                                            <option value="{{ (new AESCipher)->encrypt($i) }}">{{ (new GlobalDeclare)->project_category($i) }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                                        <label for="">Year Created</label>
                                        <select name="year_created" id="year-created" class="form-control" required>
                                        <option value="">-- Select Year --</option>
                                        @for ($i = 5; $i > 0; $i--)
                                            <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->subYear($i)->format('Y') }}</option>
                                        @endfor
                                        @for ($i = 0; $i < 5; $i++)
                                            <option value="{{ (new AESCipher)->encrypt(Carbon::now()->subYears($i)->format('Y')) }}">{{ Carbon::now()->addYear($i)->format('Y') }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="">File name</label>
                                <textarea name="file_name" class="form-control" id="" cols="30" rows="1" required></textarea>
                                </div>
                                <div class="form-group">
                                <label for="">Signed PPMP File</label>
                                <input type="file" name="signed_ppmp" id="signed-ppmp" class="form-control" required>
                                <p class="card-text alert bg-rgba-info">This can only process (.pdf, .jpeg, .jpg, .png) files</p>
                                </div>
                                <button type="submit" class="btn btn-success text-white" id="upload-signed-ppmp">Send Request</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <!-- single file upload starts -->
 
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
    <script>
      Swal.fire({
        title: 'Error',
        icon: 'error',
        html: "Please make sure fields are accounted for!",
      });
    </script>
  @endif
{{-- end message --}}
{{-- script --}}
    <script>
      // preview files
        $(document).on('click', '#preview-ppmp-btn', function(e) {
          e.preventDefault();
          $('#preview-ppmp').modal('show');
          $('#content').html('');
          // appending content
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            },
            url: "{{ route('view_ppmp') }}",
            method: 'GET',
            data: {
              'id' : $(this).data('id')
            }, success: function(response) {
                response['data'].forEach(element => {
                    $('#content').append(`<iframe src="{{asset("storage/department_upload/signed_ppmp/`+ element.signed_ppmp +`")}}" style="width:100% !important;" height="600" frameborder="0"></iframe>`);
                });
            }
          });
        });
      // preview files
      // edit uploaded ppmp
        $(document).on('click', '#edit-uploaded-ppmp-btn', function(e) {
            e.preventDefault();
            $('#edit-uploaded-ppmp').modal('show');
            // appending content
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),

              },
              url: "{{ route('get_edit_ppmp') }}",
              method: 'GET',
              data: {
                'id' : $(this).data('id')
              }, success: function(response) {
                  console.log(response);
                  response['data'].forEach(element => {
                    // console.log(element.id);
                    // response id
                      $('#default-id').val(element.id);
                      // $('#default-id').val(`{{ (new AESCipher)->encrypt(`+ element.id +`) }}`);
                    // project category
                      $('#default-project-category').text(`{{ (new GlobalDeclare)->project_category(`+ element.project_category +`) }}`);
                      $('#default-project-category').val(`{{ (new AESCipher)->encrypt(`+ element.project_category +`) }}`);
                    // year created
                      $('#default-year-created').text(element.year_created);
                      $('#default-year-created').val(`{{ (new AESCipher)->encrypt(`+ element.year_created +`) }}`);
                    // file name
                      $('#default-file-name').val(element.file_name);
                  });
              }
            });
        });
      // edit uploaded ppmp
    </script>

{{-- end script --}}
@endsection

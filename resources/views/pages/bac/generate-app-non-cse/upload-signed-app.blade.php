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
@section('title','Upload APP')
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
{{-- preview uploaded app --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="preview-app" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
{{-- edit uploaded app --}}
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="edit-uploaded-app" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Uploaded app</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="" id="uploaded-app-content">
              <div class="row justify-content-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                      <form action="{{ route('edit_app') }}" method="POST" enctype="multipart/form-data"> 
                        @csrf
                        <div class="form-group row">
                            <input type="text" name="id" id="default-id" class="form-control d-none">
                            <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                                <label for="">app / Project Category</label>
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
                                      <option value="{{ Carbon::now()->subYears($i)->format('Y') }}">{{ Carbon::now()->subYear($i)->format('Y') }}</option>
                                  @endfor
                                  @for ($i = 0; $i < 5; $i++)
                                      <option value="{{ Carbon::now()->subYears($i)->format('Y') }}">{{ Carbon::now()->addYear($i)->format('Y') }}</option>
                                  @endfor
                              </select>
                            </div>
                        </div>
                        @if (session('campus') == 1)
                          <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 col-12">
                              <label for="">File name</label>
                              <textarea name="file_name" id="default-file-name" class="form-control" id="" cols="30" rows="1" required></textarea>
                            </div>
                            
                            <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                              <label for="">APP / UAPP: </label>
                              <select name="type" id="type" class="form-control" required>
                                <option value="">-- Select Year --</option>
                                <option value="1">University Wide APP</option>
                                <option value="2">Main Campus APP</option>
                              </select>
                            </div>
                          </div>
                        @else
                          <input type="hidden" name="type" id="type" class="form-control" value="2" required>
                          <div class="form-group ">
                            <label for="">File name</label>
                            <textarea name="file_name" id="default-file-name" class="form-control" id="" cols="30" rows="1" required></textarea>
                          </div>
                        @endif
                        <div class="form-group">
                          <label for="">Signed app File</label>
                          <input type="file" name="signed_app" id="default-signed-app" class="form-control">
                          <p class="card-text alert bg-rgba-info">Attach desired file here (.pdf, .jpeg, .jpg, .png) files</p>
                        </div>
                        <button type="submit" class="btn btn-success text-white" id="update-app">Update app</button>
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
  <!-- Upload signed app starts -->
    @if (session('role') == 10 ||  session('role') == 2)
      @if ($campus == 0 || $campus == 1 )
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header p-1">
                <h4 class="card-title text-primary border-bottom pb-1">
                  <strong> Upload Signed app</strong>
                </h4>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <div class="row justify-content-center">
                    <div class="col-5 col-sm-5 col-md-5 col-lg-5">
                      <form action="{{ route('upload_app') }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="form-group row">
                          <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                              <label for="">app / Project Category</label>
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
                        @if (session('campus') == 1)
                          <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6 col-12">
                              <label for="">File name</label>
                              <textarea name="file_name" class="form-control" id="" cols="30" rows="1" required></textarea>
                            </div>
                            
                            <div class="col-sm-6 col-md-6 col-lg-6 col-12">
                              <label for="">APP / UAPP: </label>
                              <select name="type" id="type" class="form-control" required>
                                <option value="">-- Select Year --</option>
                                <option value="1">University Wide APP</option>
                                <option value="2">Main Campus APP</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                              <label for="">Signed app File</label>
                              <input type="file" name="signed_app" id="signed-app" class="form-control" required>
                              <p class="card-text alert bg-rgba-info">This can only process (.pdf, .jpeg, .jpg, .png) files</p>
                            </div>
                            <button type="submit" class="btn btn-success text-white" id="upload-signed-app">Upload Signed app</button>
                          </div>

                        @else
                          <input type="hidden" name="type" id="type" class="form-control" value="1" required>
                          <div class="form-group">
                            <label for="">File name</label>
                            <textarea name="file_name" class="form-control" id="" cols="30" rows="2" required></textarea>
                          </div>
                          
                          <div class="form-group">
                            <label for="">Signed app File</label>
                            <input type="file" name="signed_app" id="signed-app" class="form-control" required>
                            <p class="card-text alert bg-rgba-info">This can only process (.pdf, .jpeg, .jpg, .png) files</p>
                          </div>
                          <button type="submit" class="btn btn-success text-white" id="upload-signed-app">Upload Signed app</button>
                        @endif
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    @endif
  <!-- single file upload starts -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header p-1 ">
          @if ($campus == 3)
            <form action="/bac/app-non-cse-year" >
              @csrf
              <input type="hidden" name="project_category" value="{{ $project_category}}">
              <input type="hidden" name="scope" value="{{ $scope}}">
              <button class="btn btn-primary mb-1" type="submit" title="View Document of Endorsed APP NON CSE" value="{{ $year }}" name="year">Back</button>
            </form>
          @endif
          <h4 class="card-title text-primary border-bottom pb-1">
             <strong> Signed app</strong>
          </h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div class="card-header p-1">
              <h5 class="card-title">
                <strong>Project Category</strong>
              </h5>
              
              <ul class="nav nav-tabs m-1" role="tablist">

                {{-- 1st Tab | PPMP --}}
                    <li class="nav-item">
                        <a class="nav-link active" id="my-ppmp-tab" data-toggle="tab" href="#my-ppmp" aria-controls="regular" role="tab" aria-selected="true">
                          <i class="fa-solid fa-box-open"></i>
                            {{ (new GlobalDeclare)->project_category(1) }}
                        </a>
                    </li>
                {{-- end --}}
                {{-- 2nd Tab | Indicative --}}
                    <li class="nav-item">
                        <a class="nav-link" id="dissapproved-ppmp-tab" data-toggle="tab" href="#dissapproved" aria-controls="regular9" role="tab" aria-selected="false">
                          <i class="fa-regular fa-clock"></i>
                            {{ (new GlobalDeclare)->project_category(0) }} &nbsp; <strong></strong>
                        </a>
                    </li>
                {{-- end --}}
                {{-- 3rd Tab | Supplemental --}}
                    <li class="nav-item">
                        <a class="nav-link" id="approved-ppmp-tab" data-toggle="tab" href="#approved" aria-controls="regular9" role="tab" aria-selected="false">
                          <i class="fa-sharp fa-solid fa-cart-shopping"></i>
                            {{ (new GlobalDeclare)->project_category(2) }} &nbsp; <strong></strong>
                        </a>
                    </li>
                {{-- end --}}
              </ul>
              
              @if ($campus != 3 )
                @if (session('campus') == 1 )
                  <div class="row mr-1" >
                    <label for="campus" class="m-1 ">Campus:</label>
                    <div class="dropdown">
                      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false">
                        @if($response->isEmpty())
                          -- Select -- 
                        @else
                          @if ($campus == 1)
                            Main Campus
                          @else
                            University Wide
                          @endif
                        @endif
                      </a>
                    
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="/bac/signed-app-non-cse">Main Campus</a></li>
                        <li><a class="dropdown-item" href="/bac/signed-app-non-cse-uwide">University Wide</a></li>
                      </ul>
                    </div>
                  </div> 
                @endif
              @endif
          </div>
          <div class="card-body p-1">
              <div class="tab-content p-1">
                {{-- 1st Tab--}}
                  <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel">
                    {{-- list of uploaded app --}}
                      <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                              <tr id="t-tr">
                                  <th id="t-td">#</th>
                                  <th id="t-td">Project Category</th>
                                  <th id="t-td">Year Created</th>
                                  <th id="t-td">File Name</th>
                                  <th id="t-td">Date Created</th>
                                  <th id="t-td">Action</th>
                              </tr>
                            </thead>
                            <tbody class="app_cse_body">
                                {{-- showing app data based on department and user --}}
                                    @php
                                      $count = 0;
                                    @endphp
                                    @foreach ($response as $item)
                                      @if ($item->project_category == 1)
                                        @php
                                          $count += 1;
                                        @endphp
                                      <tr id="t-tr">
                                        <td id="t-td">{{ $count }}</td>
                                        <td id="t-td" class="text-nowrap">{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                        <td id="t-td">{{ $item->year_created }}</td>
                                        <td id="t-td">{{ $item->file_name }}</td>
                                        <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                        <td id="t-td">
                                          <div class="dropdown">
                                              <span
                                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                              </span>
                                              <div class="btn dropdown-menu dropdown-menu-left ">   
                                                @if (session('role') == 10 || session('role') == 2 )
                                                  @if ($campus != 3 )
                                                    <a href = "" id="edit-uploaded-app-btn" data-id="{{ (new AESCipher)->encrypt($item->id) }}" class="dropdown-item">
                                                      <i class="bx bx-edit-alt mr-1"></i>Edit
                                                    </a>
                                                    <a href = "{{ route('delete_app', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" data-id="" class="dropdown-item">
                                                      <i class="fa-solid fa-trash mr-1"></i>delete
                                                    </a>
                                                  @endif
                                                @endif
                                                  <a href = "" data-id="{{ (new AESCipher)->encrypt($item->id) }}" id="preview-app-btn"  class="dropdown-item">
                                                    <i class="fa-solid fa-eye mr-1"></i>View
                                                  </a>
                                                 
                                                  <a href= "{{ route('download_app', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" class="dropdown-item">
                                                    <i class="fa-solid fa-file-arrow-down mr-1"></i>Download
                                                  </a>
                                              </div>
                                          </div> 
                                        </td>
                                      </tr>
                                      @endif
                                    @endforeach
                                {{-- showing app data based on department and user --}}
                            </tbody>
                        </table>
                        <div class="" style="padding: 40px"></div>
                      </div>
                  </div>
                {{-- 2nd Tab--}}
                  <div class="tab-pane" id="dissapproved" aria-labelledby="dissapproved-ppmp-tab" role="tabpanel">
                    {{-- list of uploaded app --}}
                      <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                              <tr id="t-tr">
                                  <th id="t-td">#</th>
                                  <th id="t-td">Project Category</th>
                                  <th id="t-td">Year Created</th>
                                  <th id="t-td">File Name</th>
                                  <th id="t-td">Date Created</th>
                                  <th id="t-td">Action</th>
                              </tr>
                            </thead>
                            <tbody class="app_cse_body">
                                {{-- showing app data based on department and user --}}
                                
                                @php
                                  $count = 0;
                                @endphp
                                    @foreach ($response as $item)
                                      @if ($item->project_category == 0)
                                        @php
                                          $count += 1;
                                        @endphp
                                      <tr id="t-tr">
                                        <td id="t-td">{{ $count }}</td>
                                        <td id="t-td" class="text-nowrap">{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                        <td id="t-td">{{ $item->year_created }}</td>
                                        <td id="t-td">{{ $item->file_name }}</td>
                                        <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                        <td id="t-td">
                                          <div class="dropdown">
                                              <span
                                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                              </span>
                                              <div class="btn dropdown-menu dropdown-menu-left ">
                                                @if (session('role') == 10 || session('role') == 2 )
                                                  @if ($campus != 3 )
                                                    <a href = "" id="edit-uploaded-app-btn" data-id="{{ (new AESCipher)->encrypt($item->id) }}" class="dropdown-item">
                                                      <i class="bx bx-edit-alt mr-1"></i>Edit
                                                    </a>
                                                    <a href = "{{ route('delete_app', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" data-id="" class="dropdown-item">
                                                      <i class="fa-solid fa-trash mr-1"></i>delete
                                                    </a>
                                                  @endif
                                                @endif
                                                  <a href = "" data-id="{{ (new AESCipher)->encrypt($item->id) }}" id="preview-app-btn"  class="dropdown-item">
                                                    <i class="fa-solid fa-eye mr-1"></i>View
                                                  </a>
                                                 
                                                  <a href= "{{ route('download_app', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" class="dropdown-item">
                                                    <i class="fa-solid fa-file-arrow-down mr-1"></i>Download
                                                  </a>
                                              </div>
                                          </div> 
                                        </td>
                                      </tr>
                                      @endif
                                    @endforeach
                                {{-- showing app data based on department and user --}}
                            </tbody>
                        </table>
                        <div class="" style="padding: 40px"></div>
                      </div>
                  </div>
                {{-- 3rd Tab--}}
                  <div class="tab-pane" id="approved" aria-labelledby="approved-ppmp-tab" role="tabpanel">
                    {{-- list of uploaded app --}}
                      <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                              <tr id="t-tr">
                                  <th id="t-td">#</th>
                                  <th id="t-td">Project Category</th>
                                  <th id="t-td">Year Created</th>
                                  <th id="t-td">File Name</th>
                                  <th id="t-td">Date Created</th>
                                  <th id="t-td">Action</th>
                              </tr>
                            </thead>
                            <tbody class="app_cse_body">
                                {{-- showing app data based on department and user --}}
                                    @php
                                      $count = 0;
                                    @endphp
                                    @foreach ($response as $item)
                                      @if ($item->project_category == 2)
                                        @php
                                          $count += 1;
                                        @endphp
                                      <tr id="t-tr">
                                        <td id="t-td">{{ $count }}</td>
                                        <td id="t-td" class="text-nowrap">{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                        <td id="t-td">{{ $item->year_created }}</td>
                                        <td id="t-td">{{ $item->file_name }}</td>
                                        <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                        <td id="t-td">
                                          <div class="dropdown">
                                              <span
                                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                              </span>
                                              <div class="btn dropdown-menu dropdown-menu-left ">
                                                @if (session('role') == 10 || session('role') == 2 )
                                                  @if ($campus != 3 )
                                                    <a href = "" id="edit-uploaded-app-btn" data-id="{{ (new AESCipher)->encrypt($item->id) }}" class="dropdown-item">
                                                      <i class="bx bx-edit-alt mr-1"></i>Edit
                                                    </a>
                                                    <a href = "{{ route('delete_app', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" data-id="" class="dropdown-item">
                                                      <i class="fa-solid fa-trash mr-1"></i>delete
                                                    </a>
                                                  @endif
                                                @endif
                                                  <a href = "" data-id="{{ (new AESCipher)->encrypt($item->id) }}" id="preview-app-btn"  class="dropdown-item">
                                                    <i class="fa-solid fa-eye mr-1"></i>View
                                                  </a>
                                                 
                                                  <a href= "{{ route('download_app', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" class="dropdown-item">
                                                    <i class="fa-solid fa-file-arrow-down mr-1"></i>Download
                                                  </a>
                                              </div>
                                          </div> 
                                        </td>
                                      </tr>
                                      @endif
                                    @endforeach
                                {{-- showing app data based on department and user --}}
                            </tbody>
                        </table>
                        <div class="" style="padding: 40px"></div>
                      </div>
                  </div>
              </div>
          </div>
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
        $(document).on('click', '#preview-app-btn', function(e) {
          e.preventDefault();
          $('#preview-app').modal('show');
          $('#content').html('');
          // appending content
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            },
            url: "bac/view-uploaded-app",
            method: 'GET',
            data: {
              'id' : $(this).data('id')
            }, success: function(response) {
                console.log(response);
                response['data'].forEach(element => {
                    $('#content').append(`<iframe src="{{asset("storage/PMIS/bac_sec_upload/signed_app/`+ element.signed_app +`")}}" style="width:100% !important;" height="600" frameborder="0"></iframe>`);
                });
            }
          });
        });
      // preview files
      // edit uploaded app
        $(document).on('click', '#edit-uploaded-app-btn', function(e) {
            e.preventDefault();
            $('#edit-uploaded-app').modal('show');
            // appending content
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content'),

              },
              url: "{{ route('get_edit_app') }}",
              method: 'GET',
              data: {
                'id' : $(this).data('id')
              }, success: function(response) {
                  console.log(response['data'][0]['year_created']);

                  // var type = [0,1,2]
                  // $('#project-category').empty();
                  // $('#project-category').append('<option value="" selected disabled>-- Select Project Category --</option>');

                  // response['data'].forEach(element => {
                  //   console.log(element);
                    
                    // response id
                      $('#default-id').val(response['data'][0]['id']);
                      // $('#default-id').val(`{{ (new AESCipher)->encrypt(`+ response['data'][0]['id'] +`) }}`);
                    // project category
                    $cat ="";
                    if(response['data'][0]['project_category'] == 0){
                      $cat = "Indicative";
                    }else if(response['data'][0]['project_category'] == 1){
                      $cat = "PPMP";
                    }else{
                      $cat = "Supplemental";
                    }

                      $('#default-project-category').text($cat);
                      $('#default-project-category').val(`{{ (new AESCipher)->encrypt(`+ response['data'][0]['project_category'] +`) }}`);
 
                      $('#default-year-created').text(response['data'][0]['year_created']);
                      $('#default-year-created').val(response['data'][0]['year_created']);
                      // $('#default-year-created').val(`{{ (new AESCipher)->encrypt(`+ response['data'][0]['year_created'] +`) }}`);

                    // file name
                      $('#default-file-name').val(response['data'][0]['file_name']);
                  // });
              }
            });
        });
      // edit uploaded app
    </script>

{{-- end script --}}
@endsection

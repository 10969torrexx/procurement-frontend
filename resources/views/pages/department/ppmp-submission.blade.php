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
  {{-- MODALS --}}
  {{-- MODALS --}}
  {{-- message and errors --}}
    @if(Session::has('success'))
      <script>
          Swal.fire({
              title: 'Success',
              icon: 'success',
              html: "{{ Session::get('success') }}",
          });
      </script>
    @endif
    @if($errors->all())
      <script>
          Swal.fire({
              title: 'Error',
              icon: 'error',
              html: "Please make sure fields are well accounted for!",
          });
      </script>
    @endif
    @if(Session::has('failed'))
      <script>
          Swal.fire({
              title: 'Error',
              icon: 'error',
              html: "{{ Session::get('failed') }}",
          });
      </script>
    @endif
  {{-- message and errors --}}
  <div class="row col-12 col-sm-12 col-md-12 col-lg-12">
      <div class="card col-12 col-sm-12 col-md-12 col-lg-12">
          <div class="card-header p-1">
              <h5 class="card-title">
                <strong>Request for Submission</strong>
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
                {{-- 2nd Tab | Supplemental --}}
                    <li class="nav-item">
                        <a class="nav-link" id="approved-ppmp-tab" data-toggle="tab" href="#approved" aria-controls="regular9" role="tab" aria-selected="false">
                          <i class="fa-sharp fa-solid fa-cart-shopping"></i>
                            {{ (new GlobalDeclare)->project_category(2) }} &nbsp; <strong></strong>
                        </a>
                    </li>
                {{-- end --}}
              </ul>

          </div>
          <div class="card-body p-1">
              <div class="tab-content p-1">
                {{-- 1st Tab--}}
                  <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel">
                      <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                                <tr id="t-tr">
                                    <th id="t-td">#</th>
                                    <th id="t-td">Project Title</th>
                                    <th id="t-td">Year</th>
                                    <th id="t-td">status</th>
                                    <th id="t-td">Immediate SUPERVISOR</th>
                                    <th id="t-td">Project Type</th>
                                    <th id="t-td">Project Category</th>
                                    <th id="t-td">Fund Source</th>
                                    <th id="t-td" class="text-nowrap">Total Estimated Price</th>
                                    <th id="t-td" class="text-nowrap">Deadline of Submission</th>
                                    <th id="t-td">Date Added</th>
                                    <th id="t-td">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                    @foreach ($ppmp_project_titles as $item)
                                        <tr id="t-tr">
                                            <td id="t-td">{{ $loop->iteration }}</td>
                                            <td id="t-td"  class="text-nowrap">{{ $item->project_title }}</td>
                                            <td id="t-td">{{ $item->project_year }}</td>
                                            <td id="t-td" class="text-nowrap">{{ (new GlobalDeclare)->status($item->status) }}</td>
                                            <td id="t-td">{{ $item->immediate_supervisor }}</td>
                                            <td id="t-td">{{ $item->project_type }}</td>
                                            <td id="t-td">{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                            <td id="t-td" class="text-nowrap">{{ $item->fund_source }}</td>
                                            <td id="t-td">₱ {{ number_format($ppmp_total_estimated_price[ ($loop->iteration - 1) ],2,'.',',') }}</td>
                                            <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->deadline_of_submission)))[0]  }}</td>
                                            <td id="t-td" class="text-nowrap"> {{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0] }}</td>
                                            <td id="t-td">
                                              <a href="{{ route('show_ppmp_submission_items', ['project_title_id' => (new AESCipher)->encrypt($item->id), 'project_category' => (new AESCipher)->encrypt($item->project_category) ]) }}" class="btn text-secondary">
                                                <i class="fa-regular fa-paper-plane"></i>
                                              </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                        {{ $ppmp_project_titles->onEachSide(1)->links() }}
                      </div>
                  </div>
                {{-- 2nd Tab--}}
                  <div class="tab-pane" id="dissapproved" aria-labelledby="dissapproved-ppmp-tab" role="tabpanel">
                    <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel">
                      <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                                <tr id="t-tr">
                                    <th id="t-td">#</th>
                                    <th id="t-td">Project Title</th>
                                    <th id="t-td">Year</th>
                                    <th id="t-td">status</th>
                                    <th id="t-td">Immediate SUPERVISOR</th>
                                    <th id="t-td">Project Type</th>
                                    <th id="t-td">Project Category</th>
                                    <th id="t-td">Fund Source</th>
                                    <th id="t-td" class="text-nowrap">Total Estimated Price</th>
                                    <th id="t-td" class="text-nowrap">Deadline of Submission</th>
                                    <th id="t-td">Date Added</th>
                                    <th id="t-td">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                    @foreach ($indicative_project_titles as $item)
                                        <tr id="t-tr">
                                            <td id="t-td">{{ $loop->iteration }}</td>
                                            <td id="t-td"  class="text-nowrap">{{ $item->project_title }}</td>
                                            <td id="t-td">{{ $item->project_year }}</td>
                                            <td id="t-td" class="text-nowrap">{{ (new GlobalDeclare)->status($item->status) }}</td>
                                            <td id="t-td">{{ $item->immediate_supervisor }}</td>
                                            <td id="t-td">{{ $item->project_type }}</td>
                                            <td id="t-td">{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                            <td id="t-td" class="text-nowrap">{{ $item->fund_source }}</td>
                                            <td id="t-td">₱ {{ number_format($indicative_total_estimated_price[ ($loop->iteration - 1) ],2,'.',',') }}</td>
                                            <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->deadline_of_submission)))[0]  }}</td>
                                            <td id="t-td" class="text-nowrap"> {{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0] }}</td>
                                            <td id="t-td">
                                                <a href="{{ route('show_ppmp_submission_items', ['project_title_id' => (new AESCipher)->encrypt($item->id)]) }}" class="btn text-secondary">
                                                  <i class="fa-regular fa-paper-plane"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                        {{ $ppmp_project_titles->onEachSide(1)->links() }}
                      </div>
                    </div>
                  </div>
                {{-- 3rd Tab--}}
                  <div class="tab-pane" id="approved" aria-labelledby="approved-ppmp-tab" role="tabpanel">
                    <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel">
                      <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                                <tr id="t-tr">
                                    <th id="t-td">#</th>
                                    <th id="t-td">Project Title</th>
                                    <th id="t-td">Year</th>
                                    <th id="t-td">status</th>
                                    <th id="t-td">Immediate SUPERVISOR</th>
                                    <th id="t-td">Project Type</th>
                                    <th id="t-td">Project Category</th>
                                    <th id="t-td">Fund Source</th>
                                    <th id="t-td" class="text-nowrap">Total Estimated Price</th>
                                    <th id="t-td" class="text-nowrap">Deadline of Submission</th>
                                    <th id="t-td">Date Added</th>
                                    <th id="t-td">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- showing ppmp data based on department and user --}}
                                    @foreach ($supplemental_project_titles as $item)
                                        <tr id="t-tr">
                                            <td id="t-td">{{ $loop->iteration }}</td>
                                            <td id="t-td"  class="text-nowrap">{{ $item->project_title }}</td>
                                            <td id="t-td">{{ $item->project_year }}</td>
                                            <td id="t-td" class="text-nowrap">{{ (new GlobalDeclare)->status($item->status) }}</td>
                                            <td id="t-td">{{ $item->immediate_supervisor }}</td>
                                            <td id="t-td">{{ $item->project_type }}</td>
                                            <td id="t-td">{{ (new GlobalDeclare)->project_category($item->project_category) }}</td>
                                            <td id="t-td" class="text-nowrap">{{ $item->fund_source }}</td>
                                            <td id="t-td">₱ {{ number_format($supplemental_total_estimated_price[ ($loop->iteration - 1) ],2,'.',',') }}</td>
                                            <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->deadline_of_submission)))[0]  }}</td>
                                            <td id="t-td" class="text-nowrap"> {{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0] }}</td>
                                            <td id="t-td">
                                              <a href="" class="btn text-secondary btn-success">
                                                <i class="fa-regular fa-paper-plane"></i>
                                              </a>
                                          </td>
                                        </tr>
                                    @endforeach
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                        {{ $ppmp_project_titles->onEachSide(1)->links() }}
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

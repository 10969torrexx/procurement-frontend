{{-- Torrexx Additionals --}}
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
    {{-- reason for request | modal --}}
    <div class="modal fade" id="reason-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <form action="{{ route('request_submission') }}" method="post">
              @csrf
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Reason for Request</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group col-sm-12 col-md-12 col-lg-12 col-12">
                          <p class="text-secondary"> 
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                               <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                           </svg>
                               Please describe the reason for this request! Ellaborate the direness of the request.
                           </p>
                       </div>
                       <div class="form-group col-sm-12 col-12 col-lg-12 col-md-12">
                          <textarea name="reason" id="" cols="30" rows="10" class="form-control col-12 col-md-12 col-lg-12" placeholder="Enter the reason here" required></textarea>
                          <input type="text" class="form-control d-none" type="hidden" name="allocated_budget" id="allocated-budgets-input" value="">
                       </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="Submit" class="btn btn-success">Complete Request</button>
                  </div>
          </form>
      </div>
      </div>
    </div>
{{-- reason for request | modal --}}
  {{-- MODALS --}}
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
                            Make Request(s)
                        </a>
                    </li>
                {{-- end --}}
                {{-- 2nd Tab | Indicative --}}
                    <li class="nav-item">
                        <a class="nav-link" id="dissapproved-ppmp-tab" data-toggle="tab" href="#dissapproved" aria-controls="regular9" role="tab" aria-selected="false">
                          <i class="fa-regular fa-clock"></i>
                            Pending Request
                        </a>
                    </li>
                {{-- end --}}
              </ul>
          </div>
          <div class="card-body p-1">
            <div class="tab-content p-1">
              {{-- 1st Tab | Make Request --}}
                <div class="tab-pane active" id="my-ppmp" aria-labelledby="my-ppmp-tab" role="tabpanel">
                    {{-- allocated budgets | PPMP procurement type --}}
                      <div class="row justify-content-center">
                        <form action="{{ route('get_allocated_budget') }}" method="POST"  class="col-8 col-sm-8 col-md-8 col-lg-8 row">
                          @csrf
                          <div class="col-6 col-sm-6 col-md-6 col-md-6 col-lg-6">
                            <select name="procurement_type" id="procurement-type" class="form-control" required>
                                <option value="">Choose Procurement Type</option>
                                @for ($i = 0; $i < 3; $i++)
                                  <option value="{{ (new AESCipher)->encrypt($i) }}">{{ (new GlobalDeclare)->project_category($i) }}</option>
                                @endfor
                            </select>
                          </div>
                          <div class="col-6 col-sm-6 col-md-6 col-md-6 col-lg-6">
                              <button type="submit" class="btn btn-success">Search</button>
                          </div>
                        </form>
                      </div>
                    {{-- allocated budgets | PPMP procurement type --}}
                    <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                      {{-- allocated budgets | PPMP procurement type --}}
                        @if (empty($allocated_budgets))
                          <div class="table-responsive col-12 container">
                            <table class="table zero-configuration item-table" id="item-table t-table">
                                <thead>
                                    <tr id="t-tr">
                                        <th id="t-td">#</th>
                                        <th id="t-td">Fund Source</th>
                                        <th id="t-td" class="text-nowrap">Deadline of Submission</th>
                                        <th id="t-td" class="text-nowrap">Date Created</th>
                                        <th id="t-td">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- showing ppmp data based on department and user --}}
                                    {{-- showing ppmp data based on department and user --}}
                                </tbody>
                            </table>
                          </div>
                        @endif
                        @isset($allocated_budgets)
                          <div class="table-responsive col-12 container">
                            <table class="table zero-configuration item-table" id="item-table t-table">
                                <thead>
                                    <tr id="t-tr">
                                      <th id="t-td">#</th>
                                        <th id="t-td">Fund Source</th>
                                        <th id="t-td" class="text-nowrap">Deadline of Submission</th>
                                        <th id="t-td" class="text-nowrap">Date Created</th>
                                        <th id="t-td">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- showing ppmp data based on department and user --}}
                                        @foreach ($allocated_budgets as $item)
                                            <tr id="t-tr">
                                                <td id="t-td">{{ $loop->iteration }}</td>
                                                <td id="t-td" class="text-nowrap">{{ $item->fund_source }}</td>
                                                <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->end_date)))[0] }}</td>
                                                <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0] }}</td>
                                                <td id="t-td">
                                                  <a href="#" class="btn text-secondary" id="reason-btn" data-id="{{ $item->id }}">
                                                    <i class="fa-regular fa-paper-plane"></i>
                                                  </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    {{-- showing ppmp data based on department and user --}}
                                </tbody>
                            </table>
                        </div>
                        @endisset
                    </div>
                </div>
              {{-- 2nd Tab | Pending Requests --}}
                <div class="tab-pane" id="dissapproved" aria-labelledby="dissapproved-ppmp-tab" role="tabpanel">
                    {{-- allocated budgets | PPMP procurement type --}}
                      <div class="row justify-content-center">
                        <form action="{{ route('get_pending_request') }}" method="POST"  class="col-8 col-sm-8 col-md-8 col-lg-8 row">
                          @csrf
                          <div class="col-6 col-sm-6 col-md-6 col-md-6 col-lg-6">
                            <select name="procurement_type" id="procurement-type" class="form-control" required>
                                <option value="">Choose Procurement Type</option>
                                @for ($i = 0; $i < 3; $i++)
                                  <option value="{{ (new AESCipher)->encrypt($i) }}">{{ (new GlobalDeclare)->project_category($i) }}</option>
                                @endfor
                            </select>
                          </div>
                          <div class="col-6 col-sm-6 col-md-6 col-md-6 col-lg-6">
                              <button type="submit" class="btn btn-success">Search</button>
                          </div>
                        </form>
                      </div>
                    {{-- allocated budgets | PPMP procurement type --}}
                    <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                      {{-- allocated budgets | PPMP procurement type --}}
                        @if (empty($ppmp_request_submission))
                          <div class="table-responsive col-12 container">
                            <table class="table zero-configuration item-table" id="item-table t-table">
                                <thead>
                                    <tr id="t-tr">
                                        <th id="t-td">#</th>
                                        <th id="t-td">Fund Source</th>
                                        <th id="t-td">Remaining Balance</th>
                                        <th id="t-td" class="text-nowrap">Previous Deadline of Submission</th>
                                        <th id="t-td" class="text-nowrap">New Deadline of Submission</th>
                                        <th id="t-td" class="">Reason</th>
                                        <th id="t-td" class="text-nowrap">Status</th>
                                        <th id="t-td" class="text-nowrap">Date Created</th>
                                        <th id="t-td">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- showing ppmp data based on department and user --}}
                                    {{-- showing ppmp data based on department and user --}}
                                </tbody>
                            </table>
                          </div>
                        @endif
                        @isset($ppmp_request_submission)
                          <div class="table-responsive col-12 container">
                            <table class="table zero-configuration item-table" id="item-table t-table">
                                <thead>
                                    <tr id="t-tr">
                                      <th id="t-td">#</th>
                                      <th id="t-td">Fund Source</th>
                                      <th id="t-td">Remaining Balance</th>
                                      <th id="t-td" class="">Old Deadline of Submission</th>
                                      <th id="t-td" class="">New Deadline of Submission</th>
                                      <th id="t-td" class="">Reason</th>
                                      <th id="t-td" class="text-nowrap">Status</th>
                                      <th id="t-td" class="text-nowrap">Remark</th>
                                      <th id="t-td" class="text-nowrap">Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- showing ppmp data based on department and user --}}
                                        @foreach ($ppmp_request_submission as $item)
                                            <tr id="t-tr">
                                                <td id="t-td">{{ $loop->iteration }}</td>
                                                <td id="t-td" class="text-nowrap">{{ $item->fund_source }}</td>
                                                <td id="t-td">₱ {{ number_format($item->remaining_balance,2,'.',',') }}</td>
                                                <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->end_date)))[0] }}</td>
                                                {{-- deadline of submission --}}
                                                  @if ($item->deadline_of_submission != null)
                                                    <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->deadline_of_submission)))[0] }}</td>
                                                  @else
                                                    <td id="t-td">N/A</td>
                                                  @endif
                                                <td id="t-td">{{ $item->reason }}</td>
                                                  {{-- status --}}
                                                    @if($item->status == 0)
                                                      <td id="t-td"><div class="badge badge-pill badge-light-primary mr-1">Pending</div></td>
                                                    @elseif($item->status == 1)
                                                      <td id="t-td"><div class="badge badge-pill badge-light-success mr-1">Approved</div></td>
                                                    @elseif($item->status == 2)
                                                      <td id="t-td"><div class="badge badge-pill badge-light-danger mr-1">Disapproved</div></td>
                                                    @endif
                                                  {{-- remark --}}
                                                    @if (empty($item->remark))
                                                      <td id="t-td">No remark</td>
                                                    @else
                                                      <td id="t-td">{{ $item->remark }}</td>
                                                    @endif
                                                <td id="t-td" class="text-nowrap">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0] }}</td>
                                            </tr>
                                        @endforeach
                                    {{-- showing ppmp data based on department and user --}}
                                </tbody>
                            </table>
                        </div>
                        @endisset
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
      // search allocated budgets
        $(document).on('change', '#procurement-type', function(e) {
          e.preventDefault();
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
            },
            url: "{{ route('get_allocated_budget') }}",
            method: 'GET',
            data: {
              'procurement_type' : $(this).val()
            }, success: function(response) {
                if(response['status'] == 400) {
                  Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    html: response['message'],
                  });
                }

                if(response['status'] == 200) {
                  console.log(response['data']);
                  response['data'].forEach(element => {
                    $('#allocated-budgets').append(`<option value="`+ element.id +`">`+ element.fund_source +`</option>`);
                  });
                }
            }
          });
        });
      // search allocated budgets
      // reason modal
        $(document).on('click', '#reason-btn', function(e) {
            e.preventDefault();
            $('#reason-modal').modal('show');
            $('#allocated-budgets-input').val($(this).data('id'));
            console.log($(this).data('id'));
        }); 
      // end
    </script>

{{-- end script --}}
@endsection

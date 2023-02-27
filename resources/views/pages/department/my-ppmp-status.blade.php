{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    use Carbon\Carbon;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $status = 0;
    
@endphp

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','My PPMP')
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
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1">
                    <div class="col-8 col-sm-8 col-md-8">
                        {{-- search ppmp by year | Disapproved --}}
                            <form action="{{ route('department-year-created') }}" method="POST"  class="row col-12 col-sm-12 col-md-12">
                                @csrf @method('POST')
                                <div class="col-3 p-1">
                                    <select name="year_created" id="" class="form-control" required>
                                        <option value="">-- Year --</option>
                                        @for ($i = 6; $i > 0; $i--)
                                            <option value="{{ Carbon::now()->subYear($i)->format('Y') }}">{{ Carbon::now()->subYear($i)->format('Y') }}</option>
                                        @endfor
                                        @for ($i = 0; $i < 10; $i++)
                                            <option value="{{ Carbon::now()->addYear($i)->format('Y') }}">{{ Carbon::now()->addYear($i)->format('Y') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-3 p-1">
                                    <select name="procurement_type" id="" class="form-control" required>
                                        <option value="">-- Procurement Type --</option>
                                        @for ($i = 0; $i < 3; $i++)
                                            <option value="{{ $i }}">{{ (new GlobalDeclare)->project_category($i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-3 p-1">
                                    <select name="status" id="" class="form-control" required>
                                        <option value="">-- Project Status --</option>
                                        @for ($i = 0; $i < 7; $i++)
                                            <option value="{{ $i }}">{{ (new GlobalDeclare)->Status($i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-3 p-1">
                                    <button class="btn btn-primary">
                                        <i class="fa-solid fa-magnifying-glass mr-1"></i>
                                        Search
                                    </button>
                                </div>
                            </form>
                        {{-- end --}}
                    </div>
                </div>
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
                <div class="card-body">
                    @isset($project_titles)
                        <table class="table zero-configuration item-table" id="item-table t-table">
                            <thead>
                                <tr id="t-tr">
                                    <th id="t-td"><input type="checkbox" name="" id="select-all"></th>
                                    <th id="t-td">#</th>
                                    {{-- <th>Project Code</th> --}}
                                    <th id="t-td">Project Title</th>
                                    <th id="t-td">Project Year</th>
                                    <th id="t-td">Status</th>
                                    <th id="t-td">Immediate SUPERVISOR</th>
                                    <th id="t-td">Project Type</th>
                                    <th id="t-td">Fund Source</th>
                                    <th id="t-td" class="text-nowrap">Total Estimated Price</th>
                                    <th id="t-td">Deadline</th>
                                    <th id="t-td">Date Added</th>
                                    <th id="t-td">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="text" id="item-count" value="{{ count($project_titles) }}" class="form-control d-none">
                                {{-- showing ppmp data based on department and user --}}
                                    @foreach ($project_titles as $item)
                                        <tr>
                                            <td id="t-td"><input type="checkbox" name="" class="project-checkbox" value="{{ $item->id }}" data-status="{{ $item->status }}" data-allocated_budget="{{ $item->allocated_budget }}" data-procurement_type="{{ $item->project_category }}"></td>
                                            <td id="t-td">{{ $loop->iteration }}</td>
                                            <td id="t-td">{{ $item->project_title }}</td>
                                            <td id="t-td">{{ $item->project_year }}</td>
                                            <td id="t-td">{{ Str::ucfirst((new GlobalDeclare)->status($item->status)) }}</td>
                                            <td id="t-td">{{ $item->immediate_supervisor }}</td>
                                            <td id="t-td">{{ $item->project_type }}</td>
                                            <td id="t-td">{{ $item->fund_source }}</td>
                                            <td id="t-td">₱ {{ number_format($total_estimated_price[ ($loop->iteration - 1) ],2,'.',',') }}</td>
                                            {{-- checking deadline of submmission --}}
                                                @if ($item->deadline_of_submission == null)
                                                    <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->end_date)))[0]  }}</td>
                                                @else
                                                    <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->deadline_of_submission)))[0]  }}</td>
                                                @endif
                                            {{-- end --}}
                                            <td id="t-td">{{ explode('-', date('j F, Y-', strtotime($item->updated_at)))[0]  }}</td>
                                            <td id="t-td">
                                                <div class="dropdown">
                                                    <span
                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                                                    </span>
                                                    <div class="btn dropdown-menu dropdown-menu-left">
                                                        {{-- view status --}}
                                                            <a href = "{{ route('showProjectStatus', ['id' => (new AESCipher)->encrypt($item->id) ]) }}"  id="" class="dropdown-item">
                                                                <i class="fa-solid fa-business-time mr-1"></i>View Status
                                                            </a>
                                                        {{-- view status --}}
                                                        @if ($item->status == 3 || $item->status == 5) 
                                                             {{-- edit button --}}
                                                                <a href = "" data-id="{{ $aes->encrypt($item->id) }}" id="edit-title-btn" class="dropdown-item">
                                                                    <i class="bx bx-edit-alt mr-1"></i>Edit
                                                                </a>
                                                            {{-- END --}}
                                                            {{-- delete title --}}
                                                                <a href = "{{ route('department-destroy-project', ['id' => $aes->encrypt($item->id) ]) }}" class="dropdown-item">
                                                                    <i class="bx bx-trash mr-1"></i> delete
                                                                </a>
                                                            {{-- END --}}
                                                        @endif
                                                        @if ($item->status == 4) 
                                                            <a href = "{{ route('export_ppmp', ['id' => (new AESCipher)->encrypt($item->id) ]) }}" class="dropdown-item">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                </svg> &nbsp; &nbsp;
                                                            Export PDF
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div> 
                                            </td>
                                        </tr>
                                    @endforeach
                                {{-- showing ppmp data based on department and user --}}
                            </tbody>
                        </table>
                    @endisset
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

<script>
    // edit project title details
        $(document).on('click', '#edit-title-btn', function(e) {
        e.preventDefault();
        $('#viewmodal').modal('show');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('department-get-project') }}",
                method: 'GET',
                data: {
                    'id' : $(this).attr('data-id')
                }, success: function(response) { 
                    // console.log(response[0]['id']);
                    console.log(response);
                    $('#project-id').val(response[0]['id']);
                    $('#modal-header').text('Edit - ' + response[0][['project_title']]);
                    $('#project-title').val(response[0]['project_title']);
                    $('#default-project-type').val(response[0]['project_type']);
                    $('#default-project-type').text(response[0]['project_type']);
                    $('#default-year').val(response[0]['project_year']);
                    $('#default-year').text(response[0]['project_year']);
                    $('#default-fund-source').val(response[0]['allocated_budget']+'**'+response[0]['fund_source_id']);
                    $('#default-fund-source').text(response[0]['fund_source']);
                } 
            });
        });
    // end
    
</script>
@endsection

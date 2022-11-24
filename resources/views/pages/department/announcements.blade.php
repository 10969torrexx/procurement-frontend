{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $status = 0;
@endphp

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','ANNOUNCEMENTS')
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
<section id="horizontal-vertical">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Welcome <strong>{{ session('name')}}!</strong></h4>
          </div>
    </div>
      </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card p-1">
                <div class="card-header p-1">
                    <h4 class="text-primary border-bottom pb-1">
                       <strong> Announcements</strong>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive col-12 container">
                        <table class="table zero-configuration item-table" id="item-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>year</th>
                                    <th>Deadline of Submission</th>
                                    <th>Allocated Budget</th>
                                    <th>Fund Source</th>
                                </tr>
                            </thead>
                            <tbody>
                    
                                  @foreach ($allocated_budgets as $item)
                                    <tr>
                                      <td>{{ $loop->iteration }}</td>
                                      <td>{{ $item->year }}</td>
                                      <td>{{   date('M, j, Y', strtotime($item->deadline_of_submission))  }}</td>
                                      <td>₱{{ number_format($item->allocated_budget,2,'.',',') }}</td>
                                      <td>{{ $item->fund_source }}</td>
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

@endsection

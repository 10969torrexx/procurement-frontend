{{-- Torrexx Additionals --}}
@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    $immediate_supervisor = '';
    $status = 0;
@endphp
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
</style>
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
                    <h6 class="card-title border-bottom pb-1">
                       <strong> Budget Allocation</strong>
                    </h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive col-12 container">
                    <table class="table zero-configuration item-table" id="item-table t-table">
                        <thead>
                            <tr id="t-tr">
                                <th id="t-td">#</th>
                                <th id="t-td">Fund Source</th>
                                <th id="t-td">year</th>
                                <th id="t-td">Allocated Budget</th>
                                <th id="t-td">Expenditure</th>
                                <th id="t-td">Mandatory Expenditure</th>
                                <th id="t-td">Budget Used</th>
                                <th id="t-td">Remaining Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocated_budgets as $item)
                              <tr id="t-tr">
                                <td id="t-td">{{ $loop->iteration }}</td>
                                <td id="t-td">{{ $item->fund_source }}</td>
                                <td id="t-td">{{ $item->year }}</td>
                                <td id="t-td">???{{ number_format($item->SumBudget,2,'.',',') }}</td>
                                  @php
                                    $sumMandatory = 0;
                                    $expenditure = 0;
                                  @endphp
                                  @foreach ($mandatory_expeditures as $item2)
                                    @if ($item->year == $item2->year && $item->fund_source_id == $item2->fund_source_id) 
                                        <?php
                                            $sumMandatory += (!isset($item2->SumMandatory)?0:$item2->SumMandatory);
                                        ?>
                                    @endif
                                  @endforeach
                                <td id="t-td">???{{ number_format($expenditure,2,'.',',') }}</td>
                                <td id="t-td">???{{ number_format($sumMandatory,2,'.',',') }}</td>
                                <td id="t-td">???{{ number_format($total_estimated_price[ $loop->iteration - 1],2,'.',',') }}</td>
                                <td id="t-td">???{{ number_format(($item->SumBudget - ($expenditure + $sumMandatory + $total_estimated_price[$loop->iteration - 1]) ),2,'.',',') }}</td>
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

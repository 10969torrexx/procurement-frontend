@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Dashboard')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection
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
@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
    <div class="row">
      <!-- Greetings Content Starts -->
      <div class="col-12 dashboard-greetings">
        <div class="card">
          <div class="card-header">
            <h3 class="greeting-text">Welcome {{session('name')}}!</h3>
            <p class="mb-0"></p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="card-header p-1">
               <h4 class="card-title border-bottom pb-1">
                  <strong> Announcements </strong>
               </h4>
               
            </div>
            <div class="card-body">
              <div class="row p-1">
                <div class="text-center">
                  Nothing to show as of the Moment
                </div>
              </div>
            </div>
          </div>
        </div>


        @if (session('role') == 4 || session('role') == 11)
          <div class="card">
            <div class="card-header">
                <h4 class="card-title border-bottom pb-1">
                  <strong> Budget Allocation </strong>
                </h4>
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
                          <td id="t-td">₱{{ number_format($item->SumBudget,2,'.',',') }}</td>
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
                          <td id="t-td">₱{{ number_format($expenditure,2,'.',',') }}</td>
                          <td id="t-td">₱{{ number_format($sumMandatory,2,'.',',') }}</td>
                          <td id="t-td">₱{{ number_format($total_estimated_price[ $loop->iteration - 1],2,'.',',') }}</td>
                          <td id="t-td">₱{{ number_format(($item->SumBudget - ($expenditure + $sumMandatory + $total_estimated_price[$loop->iteration - 1]) ),2,'.',',') }}</td>
                        </tr>
                      @endforeach
                  </tbody>
              </table>
              </div>
            </div>
          </div>
        @endif

      </div>
    </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script>
@endsection


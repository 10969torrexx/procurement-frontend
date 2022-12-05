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
               <h4 class="text-primary border-bottom pb-1">
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

          @if (session('role') == 4)
            <div class="card">
              <div class="card-header">
                  <h4 class="text-primary border-bottom pb-1">
                    <strong> Budget Allocation </strong>
                  </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive col-12 container">
                  <table class="table zero-configuration item-table" id="item-table">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Fund Source</th>
                              <th>year</th>
                              <th>Allocated Budget</th>
                              <th>Expenditure</th>
                              <th>Mandatory Expenditure</th>
                              <th>Balance</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($response as $item)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $item->fund_source }}</td>
                              <td>{{ $item->year }}</td>
                              <td>₱{{ number_format($item->SumBudget,2,'.',',') }}</td>
                                <?php
                                  $sumMandatory = 0;
                                  $expenditure = 0;
                                ?>
                                @foreach ($mandatory_expeditures as $item2)
                                  @if ($item->year == $item2->year && $item->fund_source_id == $item2->fund_source_id) 
                                      <?php
                                          $sumMandatory += (!isset($item2->SumMandatory)?0:$item2->SumMandatory);
                                      ?>
                                  @endif
                                @endforeach
                              <td>₱{{ number_format($expenditure,2,'.',',') }}</td>
                              <td>₱{{ number_format($sumMandatory,2,'.',',') }}</td>
                              <td>₱{{ number_format(($item->SumBudget - ($expenditure + $sumMandatory)),2,'.',',') }}</td>
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


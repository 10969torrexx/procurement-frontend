@php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
@endphp
   
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Submit PPMP')

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
                    <h4 class="card-title border-bottom p-1">Submit PPMP
                     
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body ">
                        <p class="card-text"></p>
                        {{-- Buttons menu --}}
                            <div class="row form-group p-1">
                                <button class="btn btn-primary" type="button" id="create-ppmp-btn">
                                    <i class="bx bx-plus"></i> 
                                    Create PPMP
                                </button>
                            </div>
                            {{-- PPMP Details --}}
                              <form action="{{ route('departmentSubmitPPMP') }}" method="post">
                                @csrf @method('POST')
                                  {{-- Project Description Details | This will hold the project title --}}
                                  <div class="form-group p-1 border-top border-bottom" id="ppmp-details-div">
                                    {{-- Project item details | This will hold the project item name, decription and estimated price --}}
                                      <div class="row form-group p-1" id="ppmp-item-div"></div>
                                    {{-- Project item details --}}
                                  </div>
                                  {{-- Project Description Details --}}
                              </form>
                            {{-- PPMP Details --}}
                        {{-- Buttons menu --}}

                    </div>
                </div>

                
            </div>
        </div>
    </div>
    
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
      // this will add items on ppmp div element
        $('#ppmp-details-div').on('click', '#add-item-btn', function (e) {
            e.preventDefault();
            document.querySelector('#ppmp-item-div').insertAdjacentHTML(
                `afterbegin`,
                `<div class="row col-sm-12" id="ppmp-item-container">
                <div class="col-xl-3 col-md-3 col-3">
                  <label for="">Item Name</label>
                    <select name="item_name" id="item-name-select" class="form-control">
                      @if (count($response) > 0)
                      <option value="">-- Choose Item --</option>
                          @for ($i = 0; $i < count($response); $i++)
                            <option value="{{ $aes->encrypt($response[$i]["id"]) }}">{{ $response[$i]['item_name']  }}</option>
                          @endfor
                      @endif
                    </select>
                </div>
                <div class="col-xl-2 col-md-2 col-2">
                  <label for="">Quantity</label>
                  <input type="text" class="form-control" name="quantity">
                </div>
                <div class="col-xl-2 col-md-2 col-2">
                  <label for="">Unit Of Measure</label>
                  <select name="item_name" id="unit-of-measure-select" class="form-control">
                    <option value="">-- Choose Item --</option>
                  </select>
                </div>
                <div class="col-xl-2 col-md-2 col-2">
                  <label for="">Item Desciption</label>
                  <textarea name="" id="" cols="10" rows="10" class="form-control"></textarea>
                </div>
                <div class="col-xl-2 col-md-2 col-2">
                  <label for="">Estimated Price</label>
                  <input type="text" class="form-control" name="estimated_price">
                </div>
                <div class="col-xl-1 col-md-1 col-1 pt-2">
                  <button class="btn btn-danger" id="remove-item-btn" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                      <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                    </svg>
                  </button>
                </div>
              </div>`
            );
        });

      // getting the unit of measure per item using ajax get request
        $('#ppmp-item-div').on('change', '#item-name-select', function() {
         
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                },
                url: "{{ route('UnitOfMeasure') }}",
                method: 'POST',
                data : {
                  'id' : $('#item-name-select').val()
                },
                success: function(response) { 
                  console.log(response);
                } 
            });
        });
</script>
@endsection

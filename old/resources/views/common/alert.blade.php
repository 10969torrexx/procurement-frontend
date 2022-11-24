{{-- Message --}}
@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible com-md-5" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <strong><i class="fa-solid fa-circle-check"></i></strong> {{ session('success') }}
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible com-md-5" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <strong><i class="fa-solid fa-circle-exclamation"></i></strong> {{ session('error') }}
    </div>
@endif
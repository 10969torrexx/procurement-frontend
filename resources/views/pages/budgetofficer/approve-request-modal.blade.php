<div class="modal fade" id="ApproveRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered" role="document">
      <div class="modal-content">
            <div class="modal-header">
                @if($data->status == 0)
                <h5 class="modal-title" id="exampleModalLongTitle">NEW DEADLINE</h5>
                <input type="text" class="form-control edit" placeholder="" value="{{ $data->status }}" hidden>
                @else
                <h5 class="modal-title" id="exampleModalLongTitle">EDIT DEADLINE</h5>
                <input type="text" class="form-control edit" placeholder="" value="{{ $data->status }}" hidden>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
 
                <input type="text" class="form-control id" placeholder="" value="" hidden>
                <input type="text" class="form-control status" placeholder="" value="{{ $data->status }}" hidden>

                <div class="col-sm-12">

                    <label>Select Date: </label>
                    <input type="date" class="form-control new_deadline" placeholder="" value="{{ $data->deadline_of_submission }}">
                </div>
            </div>
            <div class="modal-footer" id = "footModal">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="btn btn-primary approveRequest_button">SET</button>
            </div>
        </div>
    </div>
  </div>
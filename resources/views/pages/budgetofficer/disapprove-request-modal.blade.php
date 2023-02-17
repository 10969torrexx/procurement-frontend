<div class="modal fade" id="DisapproveRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">DISAPPROVE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
 
                <input type="text" class="form-control id" placeholder="" value="" hidden>
                <input type="text" class="form-control status" placeholder="" value="{{ $data->status }}" hidden>

                <label>REMARKS:  </label>
                <input type="text" class="form-control reject_remarks" placeholder="-- Enter --" value="">
            </div>
            <div class="modal-footer" id = "footModal">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="btn btn-primary disapproveRequest_button">SUBMIT</button>
            </div>
        </div>
    </div>
  </div>
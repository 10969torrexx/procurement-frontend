<div class="modal fade" id="editmodeofprocurementmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">UPDATE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="ItemName" class="title" id="title"></label>
          <input type="text" class="form-control update" id="update"  placeholder="Input" name = "update" value = "" required autofocus>
          
          <label for="" class="title mt-1" id="title">ABBREVIATION:</label>
          <input type="text" class="form-control abv" id="update"  placeholder="Input" name = "update" value = "" required autofocus>
        </div>
        <div class="modal-footer" id = "footModal">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal" >
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Cancel</span>
          </button>
          <input type="text" class="form-control update-id" id="update-id"  placeholder="Item Name" name = "update-id" value = "" hidden>
          <button type="submit" id="update-btn" class="btn btn-primary ml-1 update-btn">
          <i class="bx bx-check d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Update</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="EditPRModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">EDIT ITEM</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row" hidden>
                <fieldset class="form-group">
                    <input type="text" class="fund_source_id form-control"  placeholder="" value="<?=$aes->encrypt($details[0]->fund_source)?>">
                </fieldset>
            </div>
            <div class="col-sm" hidden>
                <fieldset class="form-group">
                    <label for="">project code</label>
                    <input type="text" id="project_code"  class="project_code form-control" placeholder="<?=($project_code)?>" name = "project_code" value="<?=($project_code)?>">
                </fieldset>
            </div>
            @if (!empty(session("globalerror")))
                <div class="alert alert-danger" role="alert">
                    {{session("globalerror")}}
                </div>
            @else
            
            <form action="{{ route('addItem') }}" method="POST" enctype="multipart/form-data"> @csrf
                
                <div class="col-sm" hidden>
                    <fieldset class="form-group">
                        <label for="">project code</label>
                        <input type="text" id="project_code"  class="project_code form-control" placeholder="<?=($project_code)?>" name = "project_code" value="<?=($project_code)?>">
                    </fieldset>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            <label for="SelectItem" >Item</label>
                            <select  id="item" name="item" class="item form-control" required autofocus>
                                <option value="" selected disabled>-- Select Item --</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            <label for="Quantity">Quantity</label>
                            <input type="number" id="quantity"  class="quantity form-control" placeholder="Enter Quantity" name = "quantity" value = "" required>
                        </fieldset>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset class="form-group" >
                                <label for="">Upload File</label>
                                <input type="file" name="file" class="file form-control" required>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset class="form-group">
                            <label for="Specification">Specification</label>
                            <textarea id="specification"  class="specification form-control" placeholder="Enter Specification" name = "specification" value = "" required></textarea>
                        </fieldset>
                    </div>
                </div>
            </form>
            @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
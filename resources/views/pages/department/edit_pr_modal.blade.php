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
            
            <form id="updateItem" method="POST" enctype="multipart/form-data"> @csrf
                {{-- <form action="{{ route('updateItem') }}" method="POST" enctype="multipart/form-data"> @csrf --}}

                
                <div class="col-sm" hidden>
                    <fieldset class="form-group">
                        <label for="">project code</label>
                        <input type="text" id="project_code"  class="project_code form-control" placeholder="<?=($project_code)?>" name = "project_code" value="<?=($project_code)?>">
                    </fieldset>
                </div>
                {{-- <div class="col-sm" hidden>
                    <fieldset class="form-group">
                        <label for="">data</label>
                        <input type="text" id="data"  class="data form-control" placeholder="data" name = "data" value="">
                    </fieldset>
                </div> --}}
                <div class="col-sm" hidden>
                    <fieldset class="form-group">
                        <label for="">id</label>
                        <input type="text" id="updateid"  class="updateid form-control" placeholder="" name = "itemid" value="">
                    </fieldset>
                </div>
                <div class="col-sm" hidden>
                    <fieldset class="form-group">
                        <label for="">id</label>
                        <input type="text" id="id"  class="id form-control" placeholder="" name = "id" value="">
                    </fieldset>
                </div>
                <div class="col-sm-6" hidden>
                    <fieldset class="form-group">
                        <label for="Item">Item</label>
                        <input style="border:1px solid rgb(233, 230, 230);" type="text" id="updatename"  class="updatename form-control" placeholder="Item" name = "updatename" value = "" required>
                    </fieldset>
                </div>
                <div class="row">
                    
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            <label for="Item">Item</label>
                            <input style="border:1px solid rgb(233, 230, 230);" type="text" id="updatename"  class="updatename form-control" placeholder="Item" name = "updatename" value = "" required disabled>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            <label for="Quantity">Quantity</label>
                            <input type="number" id="updatequantity"  class="updatequantity form-control" placeholder="Enter Quantity" name = "updatequantity" value = "" required>
                        </fieldset>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset class="form-group" >
                                <label for="">Upload File</label>
                                <input type="file" name="updatefile" id="updatefile" class="updatefile form-control" value="" required>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset class="form-group">
                            <label for="Specification">Specification</label>
                            <textarea id="updatespecification"  class="updatespecification form-control" placeholder="Enter Specification" name = "updatespecification" value = "" required></textarea>
                        </fieldset>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary updateButton">Update</button>

        </div>
        </form>
      </div>
    </div>
  </div>

<div class="modal fade" id="edit_item_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">UPDATE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body " >
        <label  class="title" id="title"></label>
        <input type="text" class="form-control update" id="update"   placeholder="Item Name" name = "update" value = "" required autofocus>
  
        <label class="mt-1"  >Item Category</label>
        <select class="form-select form-control item-category" name = "item_category"  aria-label="Default select example" >
          <option id="item-category" selected></option>
          <option disabled>--------------------------------------------------------------------------</option>
          @foreach($category as $cat)
          {{-- @for ($i = 0; $i < count($data[1]); $i++) --}}
            <option value="{{ $cat->category }}">{{ $cat->category }}</option>
          @endforeach
           
          {{-- @endforeach --}}
        </select>

        
        <label class="mt-1">APP Type</label>
        <select class="form-select form-control app-type" name = "app_type"  aria-label="Default select example">
          <option id="app-type" selected></option>
          <option disabled>--------------------------------------------------------------------------</option>
          <option  value="CSE">CSE</option>
          <option  value="Non-CSE">Non-CSE</option>
        </select>

        {{-- <label class="mt-1">Public Bidding</label>
        <select class="form-select form-control p_bidding" name ="p_bidding"  aria-label="Default select example">
          <option id="p_bidding" value="" selected></option>
          <option disabled>--------------------------------------------------------------------------</option>
          <option value="0">Not Required</option>
          <option value="1">Required</option>
        </select> --}}
        <div id="procurement" >
          <label class="mt-1">Mode Of Procurement:</label>
          <select class="form-select form-control m_procurement" name ="m_procurement"  aria-label="Default select example" >
            <option id="m_procurement" value="" selected> Choose...</option>
            <option disabled>--------------------------------------------------------------------------</option>
            @foreach($mode2 as $m_procurement)
              <option value="{{ $m_procurement->id }}">{{ $m_procurement->mode_of_procurement }}</option>
            @endforeach
          </select>
        </div>

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
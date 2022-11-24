<!-- Button trigger modal -->
<button type="button" class = "btn btn-success round mr-1 mb-1" data-toggle="modal" data-target="#exampleModal">
    <i class="bx bx-plus"></i> New Account</a>
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <form>
            <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="SelectRole" >Select Role</label>
                        <select class="role form-control" name="SelectRole" id="SelectRole" value = "">
                            <option value="" selected disabled>Select Role</option>
                            <option value="{{$aes->encrypt(1)}}">Administrator</option>
                            <option value="{{$aes->encrypt(2)}}">Budget Officer</option>
                            <option value="{{$aes->encrypt(3)}}">Canvasser</option>
                            <option value="{{$aes->encrypt(4)}}">Department</option>
                            <option value="{{$aes->encrypt(5)}}">Supply Officer</option>
                            <option value="{{$aes->encrypt(6)}}">Supply Custodian</option>
                            <option value="{{$aes->encrypt(7)}}">Procurement Officer</option>
                            <option value="{{$aes->encrypt(8)}}">Employee</option>
                            <option value="{{$aes->encrypt(9)}}">Supplier</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label>Role Name</label>
                        <input type="text" class="form-control" placeholder="Enter Role Name">
                    </div>
                    <div class="form-group">
                        <label>Campus</label>
                        <input type="text" class="form-control" placeholder="Enter Campus">
                    </div>

            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

      </div>
    </div>
  </div>
<div class="modal fade" id="edit_campuslogo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Campus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <div class="modal-body">
                <form {{-- action="{{ route('update-campuslogo') }}" --}} id ="CampusLogo" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="" style="margin-top: 1%;">
                        <label for="inputName">Upload logo:</label>
                        <input type="file" name="logo" id="campuslogo" class="form-control" >
                        <input type="hidden" name="campuslogoid" id="campusLogo" class="form-control campusLogo" value="" >
                    </div>
                    {{-- campusLogo --}}
                    <button type="submit" class="btn btn-success form-control col-sm-3 ml-9 mt-1 float-right  " value="">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
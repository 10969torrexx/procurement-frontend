<div class="modal fade" id="edit_newapproval" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label for="Name">Name:</label>
                <select class="selectpicker form-control approvedName" id="approvedName" data-style="btn-light" data-live-search="true">
                    <option selected>choose</option>
                    @foreach($users as $users)
                        <option value="{{ $users->id }}">{{ $users->name }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" style="margin-bottom: 1%;" class="form-control approvedName" id="Name" value=""> --}}

                <label for="inputProffession" class="mt-1">Suffix:</label>
                <input type="text" class="form-control approvededutitle" id="edutitle" value="" placeholder="eg. Phd">

                <label for="Proffession" class="mt-1" >Designation:</label>
                <input type="text" class="form-control approvedprofession" id="approvedprofession" value="" placeholder="--Enter--">
                <input type="hidden" class="form-control year" value="">
    
                <button type="button" class="btn btn-success form-control col-sm-3 ml-9 mt-1 float-right submitapproval" value="">Submit</button>
            </div>
        </div>
    </div>
</div>
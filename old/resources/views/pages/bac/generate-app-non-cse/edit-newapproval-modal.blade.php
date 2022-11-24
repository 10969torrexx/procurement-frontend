<div class="modal fade" id="edit_newapproval" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label for="Name">Name:</label>
                <select class="selectpicker form-control approvedName" id="approvedName" data-style="btn-light" data-live-search="true">
                    <option selected>choose</option>
                    @foreach($users as $users)
                        <option value="{{ $users->name }}">{{ $users->name }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" style="margin-bottom: 1%;" class="form-control approvedName" id="Name" value=""> --}}

                <label for="inputProffession" >Title:</label>
                <input type="text" class="form-control approvededutitle" id="edutitle" value="" placeholder="--Enter--">

                <label for="Proffession" >Proffession:</label>
                <input type="text" class="form-control approvedprofession" id="approvedprofession" value="">
                <input type="hidden" class="form-control year" value="">
    
                <button type="button" class="btn btn-success form-control col-sm-3 ml-9 mt-1 float-right submitapproval" value="">Submit</button>
            </div>
        </div>
    </div>
</div>
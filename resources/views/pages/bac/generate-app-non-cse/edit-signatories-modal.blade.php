<div class="modal fade" id="edit_signatories" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label for="inputName">Name:</label>
                <select class="selectpicker form-control inputName" id="inputName" name="name" data-style="btn-light" data-live-search="true">
                    <option id="choose" selected></option>
                    @foreach($users as $users)
                        <option value="{{ $users->name }}">{{ $users->name }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" style="margin-bottom: 1%;" class="form-control inputName" id="inputName" value=""> --}}

                <label for="inputProffession" >Suffix (ex: Phd,MIT):</label>
                <input type="text" class="form-control edutitle" id="edutitle" value="" placeholder="--Enter--">

                <label for="inputProffession" >Designation:</label>
                <input type="text" class="form-control inputProfession" id="inputProfession" value="">
    
                <button type="button" class="btn btn-success form-control col-sm-3 ml-9 mt-1 float-right submitedit" value="">Submit</button>
            </div>
        </div>
    </div>
</div>
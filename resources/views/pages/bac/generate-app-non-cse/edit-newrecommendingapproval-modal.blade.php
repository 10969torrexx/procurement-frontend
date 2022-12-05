<div class="modal fade" id="edit_newrecommendingapproval" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label for="recommendingapprovalName">Name:</label>
                <select class="selectpicker form-control recommendingapprovalName" id="recommendingapprovalName" data-style="btn-light" data-live-search="true">
                    <option selected>choose</option>
                    @foreach($users as $users)
                        <option value="{{ $users->name }}">{{ $users->name }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" style="margin-bottom: 1%;" class="form-control recommendingapprovalName" id="recommendingapprovalName" value=""> --}}

                <label for="inputProffession" class="mt-1" >Suffix:</label>
                <input type="text" style="margin-bottom: 1%;" class="form-control recommendingapprovaltitle" id="recommendingapprovaltitle" value="" placeholder="eg. Phd">

                <label for="recommendingapprovalProffession"  class="mt-1">Designation:</label>
                <input type="text" class="form-control recommendingapprovalprofession" id="recommendingapprovalprofession" value="" placeholder="--Enter--">
                <input type="hidden" class="form-control year" value="">
    
                <button type="button" class="btn btn-success form-control col-sm-3 ml-9 mt-1 float-right submitrecommendingapproval" value="">Submit</button>
            </div>
        </div>
    </div>
</div>
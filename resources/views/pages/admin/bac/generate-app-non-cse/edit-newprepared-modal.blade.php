<div class="modal fade" id="edit_newprepared" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label for="preparedName">Name:</label>
                <select class="selectpicker form-control preparedName" id="preparedName" data-style="btn-light" data-live-search="true">
                    <option selected>choose</option>
                    @foreach($users as $users)
                        <option value="{{ $users->name }}">{{ $users->name }}</option>
                    @endforeach
                </select>
                  
                {{-- <select class="selectpicker" data-live-search="true">
                    <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
                    <option data-tokens="mustard">Burger, Shake and a Smile</option>
                    <option data-tokens="frosting">Sugar, Spice and all things nice</option>
                </select> --}}
                  
                {{-- <input type="text" style="margin-bottom: 1%;" class="form-control preparedName" id="preparedName" value=""> --}}

                <label for="preparedProffession" class="mt-1" >Suffix:</label>
                <input type="text" class="form-control preparedtitle" id="preparedtitle" value="" placeholder="eg. Phd">

                <label for="preparedProffession" class="mt-1" >Designation:</label>
                <input type="text" class="form-control preparedProfession" id="preparedProfession" value="" placeholder="--Enter--">
                <input type="hidden" class="form-control year"value="">
                
                <button type="button" class="btn btn-success form-control col-sm-3 ml-9 mt-1 float-right submitprepared" value="">Submit</button>
            </div>
        </div>
    </div>
</div>
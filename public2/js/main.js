/** Torrexx Additionals */
    /** This code block is for getting all the data from the database using ajax */
    /** The following code is used on submit-PPMP.blade.php */
        // this will add ppmp details div
        var countNumberOfItem = 0;
        $('#create-ppmp-btn').click(function(e) {
            e.preventDefault();
           // this will append project details
            document.querySelector('#ppmp-details-div').insertAdjacentHTML(
                `afterbegin`,
                `<div class="row form-group">
                <div class="col-xl-6 col-md-3 col-6">
                    <label for="">Project Title</label>
                    <input type="text" class="form-control" name="project_title">
                </div>
                <div class="col-xl-3 col-md-3 col-3">
                    <label for="">Schedule / Milestone Date</label>
                    <input type="date" class="form-control" name="scheduled_date">
                </div>
                <div class="col-xl-3 col-md-3 col-3">
                  <label for="">Mode of Procurement</label>
                  <select name="" id="" class="form-control">
                    <option value="">SVP</option>
                    <option value="">SVP</option>
                    <option value="">SVP</option>
                  </select>
                </div>
              </div>
              <div class="row form-group p-1">
                  <button class="btn btn-secondary" type="button" id="add-item-btn">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                    Add Item
                  </button>
                  &nbsp;
                  &nbsp;
                  <button class="btn btn-success" type="button" id="add-item-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                    Confirm and Submit PPMP
                  </button>
              </div>`
            );
        });
        
        // this will add items on ppmp
        /** Unused code for adding items on ppmp 
          // $('#ppmp-details-div').on('click', '#add-item-btn', function (e) {
          //     e.preventDefault();
          //     document.querySelector('#ppmp-item-div').insertAdjacentHTML(
          //         `afterbegin`,
          //         `<div class="row col-sm-12" id="ppmp-item-container-`+ countNumberOfItem +`">
          //         <div class="col-xl-3 col-md-3 col-3">
          //           <label for="">Item Name</label>
          //             <select name="item_name" id="item-name-select" class="form-control">
          //               <option value="">-- Choose Item --</option>
          //             </select>
          //         </div>
          //         <div class="col-xl-2 col-md-2 col-2">
          //           <label for="">Quantity</label>
          //           <input type="text" class="form-control" name="quantity">
          //         </div>
          //         <div class="col-xl-2 col-md-2 col-2">
          //           <label for="">Unit Of Measure</label>
          //           <select name="item_name" id="unit-of-measure-select" class="form-control">
          //             <option value="">-- Choose Item --</option>
          //           </select>
          //         </div>
          //         <div class="col-xl-2 col-md-2 col-2">
          //           <label for="">Item Desciption</label>
          //           <textarea name="" id="" cols="10" rows="10" class="form-control"></textarea>
          //         </div>
          //         <div class="col-xl-2 col-md-2 col-2">
          //           <label for="">Estimated Price</label>
          //           <input type="text" class="form-control" name="estimated_price">
          //         </div>
          //         <div class="col-xl-1 col-md-1 col-1 pt-2">
          //           <button class="btn btn-danger" id="remove-item-btn-`+ countNumberOfItem +`" type="button">
          //             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
          //               <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
          //             </svg>
          //           </button>
          //         </div>
          //       </div>`
          //     );
          // });
        */

        $('#pmmp-details-div').on('click', '#remove-item-btn-1', function (e) {
           
        });


    /** The following code is used on submit-PPMP.blade.php */
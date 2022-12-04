$(document).on('click', '.addsupplier', function (e) {
    // console.log("ghsdcg");
    $('.editSupplierName').val("");
    $('.editAddress').val("");
    $('.editContactNumber').val("");
    $('.editDescription').val("");
  });
  
  $(document).on('click', '.supplierSave', function (e) {
    // console.log("ghsdcg");
    // $("#allmodal").modal('show');
    var data = {
      'SupplierName': $(".SupplierName").val(),
      'Address': $('.Address').val(),
      'ContactNumber': $('.ContactNumber').val(),
      'Description': $('.Description').val(),
      // 'id': $(this).val(),
    }
    console.log(data);
    if(data.SupplierName == "" || data.Address == "" || data.ContactNumber == "" || data.Description == "" ){
      Swal.fire('Complete the needed data', '', 'info')
    }else{
      console.log(data);
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
  
      $.ajax({
        type: "post",
        url: "add-supplier",
        data:data,
        success: function (response) {
          console.log(response);
          if(response['status'] == 200) {
            Swal.fire({
              title: 'Added!!!',
              html: 'Added Successfully!',
              icon: 'success',
              timer: 1000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                  b.textContent = Swal.getTimerLeft()
                }, 100)
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
              }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                  location.reload();
                  console.log('I was closed by the timer')
                }
              })
          }else if(response['status'] == 400){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Check Inputs',
            })
          }
        }
      });
    }
    
  });
  
  $(document).on('click', '.supplieredit', function (e) {
    // $("#allmodal").modal('show');
    var id = $(this).attr("data-id")
    // console.log(id);
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
  
      $.ajax({
        type: "post",
        url: "edit-supplier",
        data:{'id' :id},
        beforeSend : function(html){
          $('.editSupplierName').val('Pls wait..');
          $('.editAddress').val('Pls wait..');
          $('.editContactNumber').val('Pls wait..');
          $('.editDescription').val('Pls wait..');
        },
        success : function(response){
          // console.log(response);
          $('.editSupplierName').val(response['data'][0]['SupplierName']);
          $('.editAddress').val(response['data'][0]['Address']);
          $('.editContactNumber').val(response['data'][0]['ContactNo']);
          $('.editDescription').val(response['data'][0]['Description']);
          $('.supplierEditsave').val(response['data'][0]['id']);
        },
      });
  });
  
  $(document).on('click', '.supplierEditsave', function (e) {
    // $("#allmodal").modal('show');
    var data = {
      'SupplierName': $('.editSupplierName').val(),
      'Address': $('.editAddress').val(),
      'ContactNumber': $('.editContactNumber').val(),
      'Description': $('.editDescription').val(),
      'id': $(this).val(),
      // 'id': $(this).val(),
    }
    // console.log(data);
  
    if(data.SupplierName == "" || data.Address == "" || data.ContactNumber == "" || data.Description == "" ){
      Swal.fire('Complete the needed data', '', 'info')
    }else{
      // console.log(data);
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
  
      $.ajax({
        type: "post",
        url: "submitedit-supplier",
        data:data,
        success: function (response) {
          console.log(response);
          if(response['status'] == 200) {
            Swal.fire({
              title: 'Updated!!!',
              html: 'Updated Successfully!',
              icon: 'success',
              timer: 1000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                  b.textContent = Swal.getTimerLeft()
                }, 100)
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
              }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                  location.reload();
                  console.log('I was closed by the timer')
                }
              })
          }else if(response['status'] == 400){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Check Inputs',
            })
          }
        }
      });
    }
    
  });
  
  $(document).on('click', '.supplierdelete', function (e) {
    // $("#allmodal").modal('show');
    var id = $(this).attr("data-id")
    // console.log(id);
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
  
      $.ajax({
        type: "post",
        url: "delete-supplier",
        data:{'id' :id},
        success: function (response) {
          console.log(response);
          if(response['status'] == 200) {
            Swal.fire({
              title: 'Added!!!',
              html: 'Added Successfully!',
              icon: 'success',
              timer: 1000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                  b.textContent = Swal.getTimerLeft()
                }, 100)
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
              }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                  location.reload();
                  console.log('I was closed by the timer')
                }
              })
          }else if(response['status'] == 400){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Check Inputs',
            })
          }
        }
      });
  });
$(document).on('click', '.add_btn', function (e) {
  // e.preventDefault();

  $('.fundsource').val('');
});

$(document).on('click', '.btnAddFundSource', function (e) {
  e.preventDefault();
  
  var fundsource = document.getElementById('fundsource').value;
  
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        } 
      });

  if(fundsource==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
  }
  else{
        var data = {
              'fundsource': fundsource,
        }
        // alert(data);
        $.ajax({
              type: "POST",
              url: "add_FundSource",
              data: data,
              dataType: "json",
              success: function (response) {
                // console.log(response);
                  if (response.status == 200) {
                          Swal.fire({
                            title: 'Saved',
                            icon: 'success',
                            html: 'Fund Source Added Successfully!',
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
                            if (result.dismiss === Swal.DismissReason.timer) {
                              // $('#FundSourceModal').modal('hide');
                                // location.reload();
                              // console.log('I was closed by the timer')
                              $('.fundsource').val('');
                              $("#FundSourceModal").on("hidden.bs.modal", function(e){
                                location.reload();
                              })
                            }
                          })
                      
                  } if(response.status == 400){
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: response.message,
                    })
                      $('.btnAddFundSource').text('Save');
                  }
              }
          });
  }
});

$(document).on('click', '.editbutton', function (e) {
  e.preventDefault();

  var id = $(this).attr("href");
  // alert(id);
   $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $.ajax({
        type: "POST",
        url: "edit_FundSource",
        data:{'id':id},
        success: function (response) {
            if (response.status == 200) {
              $('.update_fundsource').val(response['data'][0]['fund_source']);
              $('.update_id').val(id);
              $('#UpdateFundSourceModal').modal('show');
            }
        }
    });
});

$(document).on('click', '.btnUpdateFundSource', function (e) {
  e.preventDefault();
  $(this).text('Updating..');

  var fundsource = document.getElementById('update_fundsource').value;
  var id = document.getElementById('update_id').value;
  // alert(expenditure); 
  // console.log(expenditure);     

  if(fundsource==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
            $('.btnUpdateFundSource').text('Update');
  }
  else{
        var data = {
              'fundsource': fundsource,
              'id': id,
        }
        // alert(expenditure); 
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              } 
            });
        $.ajax({
              type: "POST",
              url: "update_FundSource",
              data: data,
              dataType: "json",
              success: function (response) {
                if(response['status'] == 200) {
                }
                    if(response['status'] == 200) {
                      Swal.fire({
                        title: 'Updated!',
                        html: 'Fund Source Updated Successfully!',
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
                          $('#UpdateFundSourceModal').modal('hide');
                          location.reload();
                          console.log('I was closed by the timer')
                    }
                  })
                  }if(response.status == 400){
                    Swal.fire({ 
                      icon: 'error',
                      title: 'Error',
                      text: response['message'],
                    })
                      // $('#AddUserModal').find('input').val('');
                      $('.btnUpdateFundSource').text('Update');
                      // $('#AddUserModal').modal('hide');
                  }
              }
            })
  }
});

// START DELETE BUTTON
$(document).on('click', '.hrefdelete', function (e) {
  e.preventDefault();
  var id = $(this).attr("href");
  Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',  
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          confirmButtonClass: 'btn btn-primary',
          cancelButtonClass: 'btn btn-danger ml-1',
          buttonsStyling: false,
  }).then((result) => {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      if (result.isConfirmed) {
        $.ajax({
              type: "post",
              url: "delete_FundSource",
              data:{'id':id},
              success: function (response) {
                // alert(response.status);
                if (response.status == 200) {
                  Swal.fire({
                    title: 'Deleted!',
                    html: response.message,
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
                // Swal.fire('Deleted SuccessFully!', '', 'success')
                // location.reload()
                }
              }
            });
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
    })
});
// END DELETE BUTTON
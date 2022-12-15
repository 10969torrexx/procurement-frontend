$(document).on('click', '.add_btn', function (e) {
  $('.mandatory_expenditure').val('');
});

$(document).on('click', '.btnAddMandatoryExpenditure', function (e) {
  e.preventDefault();
  
  var mandatory_expenditure = document.getElementById('mandatory_expenditure').value;
  
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        } 
      });

  if(mandatory_expenditure==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
  }
  else{
        var data = {
              'mandatory_expenditure': mandatory_expenditure,
        }
        // alert(data);
        $.ajax({
              type: "POST",
              url: "save_mandatory_expenditure",
              data: data,
              dataType: "json",
              success: function (response) {
                  if (response.status == 200) {
                          Swal.fire({
                            title: 'Saved',
                            icon: 'success',
                            html: 'Mandatory Expenditure Added Successfully!',
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
                              $('.mandatory_expenditure').val('');
                              $("#MandatoryExpenditureModal").on("hidden.bs.modal", function(e){
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
                      $('.btnAddMandatoryExpenditure').text('Save');
                  }
              }
          });
  }
});

$(document).on('click', '.editbutton', function (e) {
  e.preventDefault();

  var id = $(this).attr("href");
   $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $.ajax({
        type: "POST",
        url: "edit_mandatory_expenditure",
        data:{'id':id},
        success: function (response) {
            if (response.status == 200) {
              $('.update_mandatory_expenditure').val(response['mandatory_expenditure'][0]['expenditure']);
              $('.update_id').val(id);
              $('#UpdateMandatoryExpenditureModal').modal('show');
            }
        }
    });
});

$(document).on('click', '.btnUpdateMandatoryExpenditure', function (e) {
  e.preventDefault();
  $(this).text('Updating..');

  var mandatory_expenditure = document.getElementById('update_mandatory_expenditure').value;
  var id = document.getElementById('update_id').value;
  if(mandatory_expenditure==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
            $('.btnUpdateMandatoryExpenditure').text('Update');
  }
  else{
        var data = {
              'mandatory_expenditure': mandatory_expenditure,
              'id': id,
        }
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              } 
            });
        $.ajax({
              type: "POST",
              url: "update_mandatory_expenditure",
              data: data,
              dataType: "json",
              success: function (response) {
                if(response['status'] == 200) {
                }
                    if(response['status'] == 200) {
                      Swal.fire({
                        title: 'Updated!',
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
                        if (result.dismiss === Swal.DismissReason.timer) {
                          $('#UpdateMandatoryExpenditureModal').modal('hide');
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
                      $('.btnUpdateMandatoryExpenditure').text('Update');
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
              url: "delete_mandatory_expenditure",
              data:{'id':id},
              success: function (response) {
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
                    if (result.dismiss === Swal.DismissReason.timer) {
                      location.reload();
                      console.log('I was closed by the timer')
                    }
                  })
                }
              }
            });
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
    })
});
// END DELETE BUTTON
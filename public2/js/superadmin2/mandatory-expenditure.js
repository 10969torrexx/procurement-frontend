$("#MandatoryExpenditureModal").on("hidden.bs.modal", function(e){
        // location.reload();
  })
  $(document).on('click', '.add_btn', function (e) {
      // e.preventDefault();

      $('.expenditure').val('');
      $('.price').val('');
  });
 
  $(document).on('click', '.btnAddExpenditure', function (e) {
      e.preventDefault();
      
      var expenditure = document.getElementById('expenditure').value;
      var price = document.getElementById('price').value;
      
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } 
          });

      if(expenditure==''||price==''){
            Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Please provide the needed information!',
                })
      }
      else{
            var data = {
                  'expenditure': expenditure,
                  'price': price,
            }
            // alert(data);
            $.ajax({
                  type: "POST",
                  url: "add_MandatoryExpenditure",
                  data: data,
                  dataType: "json",
                  success: function (response) {
                    console.log(response);
                      if (response.status == 200) {
                              Swal.fire({
                                title: 'Saved',
                                icon: 'success',
                                html: 'Expenditure Added Successfully!',
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
                                  $('#MandatoryExpenditureModal').modal('hide');
                                    location.reload();
                                  console.log('I was closed by the timer')
                                }
                              })
                          
                      } if(response.status == 400){
                        Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'Expenditure Already Exist!',
                        })
                          $('.btnAddExpenditure').text('Save');
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
            url: "edit_MandatoryExpenditure",
            data:{'id':id},
            success: function (response) {
            //   console.log(response);
                if (response.status == 200) {
                  $('.update_expenditure').val(response['data'][0]['expenditure']);
                  $('.update_price').val(response['data'][0]['price']);
                  $('.update_id').val(id);
                  $('#UpdateMandatoryExpenditureModal').modal('show');
                }
            }
        });
});

$(document).on('click', '.btnUpdateExpenditure', function (e) {
      e.preventDefault();
      $(this).text('Updating..');
    
      var expenditure = document.getElementById('update_expenditure').value;
      var price = document.getElementById('update_price').value;
      var id = document.getElementById('update_id').value;
      // alert(expenditure); 
      // console.log(expenditure);     

      if(expenditure==''||price==''){
            Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Please provide the needed information!',
                })
                $('.btnUpdateExpenditure').text('Update');
      }
      else{
            var data = {
                  'expenditure': expenditure,
                  'price': price,
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
                  url: "update_MandatoryExpenditure",
                  data: data,
                  dataType: "json",
                  success: function (response) {
                    if(response['status'] == 200) {
                    }
                        if(response['status'] == 200) {
                          Swal.fire({
                            title: 'Updated!',
                            html: 'Expenditure Updated Successfully!',
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
                          // $('#AddUserModal').find('input').val('');
                          $('.btnUpdateExpenditure').text('Update');
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
              url: "delete_MandatoryExpenditure",
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
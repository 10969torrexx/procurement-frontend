//Mpdal
// $("#editItemModal").on('click', function(e){
//       e.preventDefault();
//       $("#edititemmodal").modal('show');
// })

// $("#edititemmodal").on("show.bs.modal", function(e){
//   $("#bodyModal").html('<i class="bx bx-loader-circle"></i> Please wait...');
//   // $("#bodyModalImage").html('<i class="bx bx-loader-circle"></i> Please wait...');
// })



//Save
var input = document.getElementById("mode-of-procurement");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var data = {
        // 'item id': $('.item-id').val(),
        'modeofprocurement': $('.mode-of-procurement').val(),
        'abbreviation': $('.mode-of-procurement-abv').val(),
    }
    console,log(data);
    if( data.modeofprocurement == "" || data.abbreviation == "")
    {
      Swal.fire('Complete the needed data', '', 'info')
    }
    else{
      $(this).text('Saving..');
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "add_procurement",
        data: data,
        dataType: "json",
        success: function (response) {
          // console.log(response);
            if (response.status == 200) {
              
                    // console.log(response);
                    Swal.fire({
                      title: 'Saved',
                      html: 'Saved Successfully!',
                      icon: 'success',
                      timer: 2000,
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
                        $('.add-procurement').text('Add');
                        $('.mode-of-procurement').val('');
                        // $('#item-table').DataTable().ajax.reload();
                        location.reload();
                        console.log('I was closed by the timer')
                      }
                    })
                
            } else {
              
              $('.add-procurement').text('Add');
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Already Exist',
              })
              $('.add-procurement').text('Add');
              $('.mode-of-procurement').val('');
            }
        }
      });
    }
           console.log(data);
   
  }
});

$(document).on('click', '.add-procurement', function (e) {
    e.preventDefault();


    var data = {
      // 'item id': $('.item-id').val(),
      'modeofprocurement': $('.mode-of-procurement').val(),
      'abbreviation': $('.mode-of-procurement-abv').val(),
  }
  // console.log(data);
  if( data.modeofprocurement == "" || data.abbreviation == "")
    {
      Swal.fire('Input Mode of Procurement!!', '', 'info')
    }
    else
    {
      $(this).text('Saving..');
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
     });

      $.ajax({
        type: "POST",
        url: "add_procurement",
        data: data,
        dataType: "json",
        success: function (response) {
          // console.log(response);
            if (response.status == 200) {
              
                    // console.log(response);
                    Swal.fire({
                      title: 'Saved',
                      html: 'Saved Successfully!',
                      icon: 'success',
                      timer: 2000,
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
                        $('.add-procurement').text('Add');
                        $('.mode-of-procurement').val('');
                        // $('#item-table').DataTable().ajax.reload();
                        location.reload();
                        console.log('I was closed by the timer')
                      }
                    })
                
            } else {
              
              $('.add-procurement').text('Add');
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Already Exist',
              })
              $('.add-procurement').text('Add');
              $('.mode-of-procurement').val('');
            }
        }
      });
    }
          //  console.log(data);


});



//delete
$(document).on('click', '.delete-procurement', function (e) {
  e.preventDefault();
  var id = $(this).attr("href");
  // alert(id );
  Swal.fire({
    title: 'Are you sure you want to delete?',
    showDenyButton: true,
    confirmButtonText: 'Yes',
    icon: 'question',
    denyButtonText: 'No',
    customClass: {
      actions: 'my-actions',
      confirmButton: 'order-2',
      denyButton: 'order-3',
    }
  }).then((result) => {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      if (result.isConfirmed) {
        $.ajax({
              type: "post",
              url: "delete_procurement",
              data:{'id' :id},
              success: function (response) {
                // alert(response.status);
                if (response.status == 200) {
                  Swal.fire({
                    title: 'Deleted!!!',
                    html: 'Deleted Successfully!',
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

//Show
$(document).on('click', '.edit', function (e) {
  e.preventDefault();
  var id = $(this).attr("href");

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "edit_procurement",
      data:{'id' :id},
      success: function (response) {
        if(response['status'] == 200) {
        // console.log(response['data'][0]['item_name']);
        // console.log(response);
         $("#editmodeofprocurementmodal").modal('show');
         $.ajax({
          cache      : false,
          beforeSend : function(html){
            $('.update').val('Pls wait...');
            $('.update-id').val('Pls wait...');
          },
          success: function(html){
            $('.title').text('Mode of Procurement');
            $('.update').val(response['data'][0]['mode_of_procurement']);
            $('.abv').val(response['data'][0]['abbreviation']);
            $('.update-id').val(response['id']);
            },
        });
         
        }
      }
    });
});

//update

var input = document.getElementById("update");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();

    var data = {
      'mode_of_procurement': $('.update').val(),
      'abbreviation': $('.abv').val(),
      'id': $('.update-id').val(),
  }

  if( data.mode_of_procurement == "" || data.abbreviation=="")
  {
    Swal.fire('Complete the needed data', '', 'info')
  }
  else{
    // $(this).text('Saving..');
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "update_procurement",
      data: data,
      dataType: "json",
      success: function (response) {
        // console.log(response);
        if(response['status'] == 200) {
          
          $("#editmodal").modal('hide');
          Swal.fire({
            title: 'Updated!!!',
            html: 'Update Successfully!',
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
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Already Exist',
            })
            $(this).text('Update');
          }
      }
    }) 
    }   
  }
});

$(document).on('click', '.update-btn', function (e) {
  e.preventDefault();
  var data = {
    'mode_of_procurement': $('.update').val(),
    'abbreviation': $('.abv').val(),
    'id': $('.update-id').val(),
}

if( data.mode_of_procurement == "" || data.abbreviation=="")
  {
    Swal.fire('Complete the needed data', '', 'info')
  }
  else{
    // $(this).text('Saving..');
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
      $.ajax({
        type: "POST",
        url: "update_procurement",
        data: data,
        dataType: "json",
        success: function (response) {
          // console.log(response);
          if(response['status'] == 200) {
            
            $("#editmodal").modal('hide');
            Swal.fire({
              title: 'Updated!!!',
              html: 'Update Successfully!',
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
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Already Exist',
              })
              $(this).text('Update');
            }
        }
      })
    }
});
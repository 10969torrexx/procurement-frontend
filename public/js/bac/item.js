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

var input = document.getElementById("item_name");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    
    // var item_name = $('.item_name').val();
    // var category = $('.item_category').val();
    // var type = $('.item_type').val();
    // console.log(category);
    var data = {
      'item_name': $('.item_name').val(),
      'item_category': $('.item_category').val(),
      'app_type': $('.item_type').val(),
      'campus': $('.campus').val(),
      'name': $('.name').val(),
      'public_bidding': $('.public_bidding').val(),
  }
  if(data.item_name=="" || data.item_category=="Choose.." || data.app_type=="Choose.." || data.public_bidding=="Choose..")
  {
    Swal.fire('Complete the needed data', '', 'info')
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
      url: "add-item",
      data: data,
      dataType: "json",
      success: function (response) {
        // console.log(response);
          if (response.status == 200) {
            
                  console.log(response);
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
                      $('.add-item').text('Add');
                      $('.item_name').val('');
                      // $('#item-table').DataTable().ajax.reload();
                      location.reload();
                      console.log('I was closed by the timer')
                    }
                  })
              
          } else {
            
            $('.add-item').text('Add');
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Item Already Exist!!',
            })
            $('.add-item').text('Add');
            $('.item_name').val('');
          }
      }
    });
    }
          //  console.log(data);

    
  }
});


$(document).on('click', '.add-item', function (e) {
    e.preventDefault();

    var data = {
        'item_name': $('.item_name').val(),
        'item_category': $('.item_category').val(),
        'app_type': $('.item_type').val(),
        // 'campus': $('.campus').val(),
        // 'name': $('.name').val(),
        'public_bidding': $('.public_bidding').val(),
    }
    if(data.item_name=="" || data.item_category=="Choose.." || data.app_type=="Choose.." || data.public_bidding=="Choose..")
    {
      Swal.fire('Complete the needed data', '', 'info')
    }
    else{
      console.log(data);
      $(this).text('Saving..');
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  
      $.ajax({
          type: "POST",
          url: "add-item",
          data: data,
          dataType: "json",
          success: function (response) {
            console.log(response);
              if (response.status == 200) {
                
                      console.log(response);
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
                          $('.add-item').text('Add');
                          $('.item_name').val('');
                          // $('#item-table').DataTable().ajax.reload();
                          location.reload();
                          console.log('I was closed by the timer')
                        }
                      })
                  
              } else {
                
                $('.add-item').text('Add');
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Item Already Exist!!',
                })
                $('.add-item').text('Add');
                $('.item_name').val('');
              }
          }
      });
    }

});



//delete
$(document).on('click', '.delete-item', function (e) {
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
                url: "delete-item",
                data:{'id' :id},
                success: function (response) {
                  // alert(response.status);
                  if (response.status == 200) {
                    Swal.fire({
                      title: 'Deleted!!!',
                      html: 'Account Deleted Successfully!',
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
      url: "show-item",
      data:{'id' :id},
      success: function (response) {
        if(response['status'] == 200) {
        console.log(response);
        // console.log(response);
         $("#edit_item_modal").modal('show');
         $.ajax({
          cache      : false,
          beforeSend : function(html){
            $('.title').text('Item Name');
            $('.update').val('Pls wait..');
            $('#item-category').text('Pls wait..');
            $('#app-type').text('Pls wait..');
            $('#public-bidding').text('Pls wait..');
            $('.update-id').val('Pls wait..');
          },
          success : function(html){
          $('.title').text('Item Name');
          $('.update').val(response['data'][0]['item_name']);
          $('#item-category').text(response['data'][0]['item_category']);
        //  for(var i in response['data1'][0]['category'] ){
        //     $('#item-category').append('<option value='+response[i]+ 'selected disabled>'+ response[i]+'</option>')
        //   };
          $('#app-type').text(response['data'][0]['app_type']);
          if(response['data'][0]['public_bidding'] == 0)
          {
            $('#public-bidding').text('Not Required');
            $('#public-bidding').val(response['data'][0]['public_bidding']);
          }
          else if(response['data'][0]['public_bidding'] == 1)
          {
            $('#public-bidding').text('Required');
            $('#public-bidding').val(response['data'][0]['public_bidding']);
          }

          $('.update-id').val(response['id']);
            },
        });

        //  $(document).on('click', '.category', function (b) {
        //   b.preventDefault();
        //   $('.item-category').val( $('.category'));
        //  });
         
        }
      }
    });
});

//update

var input = document.getElementById("update");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    
  if($('.public-bidding').val() == "Required")
  {
    var data = {
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'public_bidding': "1",
      'id': $('.update-id').val(),
    }
  }else if($('.public-bidding').val() == "Not Required")
  {
    var data = {
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'public_bidding': "0",
      'id': $('.update-id').val(),
    }
  }else{
    var data = {
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'public_bidding': $('.public-bidding').val(),
      'id': $('.update-id').val(),
    }
  }
    if(data.item_name=="" || data.item_category=="Choose.." || data.app_type=="Choose..")
    {
      Swal.fire('Complete the needed data', '', 'info')
    }
    else
    {
        // $(this).text('Saving..');
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "update-item",
        data: data,
        dataType: "json",
        success: function (response) {
          // console.log(response);
          if(response['status'] == 200) {
            
            $("#edit_item_modal").modal('hide');
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
  if($('.public-bidding').val() == "Required")
  {
    var data = {
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'public_bidding': "1",
      'id': $('.update-id').val(),
    }
  }else if($('.public-bidding').val() == "Not Required")
  {
    var data = {
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'public_bidding': "0",
      'id': $('.update-id').val(),
    }
  }else{
    var data = {
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'public_bidding': $('.public-bidding').val(),
      'id': $('.update-id').val(),
    }
  }

// console.log(data);
  if(data.item_name=="" || data.item_category=="Choose.." || data.app_type=="Choose..")
  {
    Swal.fire('Complete the needed data', '', 'info')
  }
  else
  {
    // $(this).text('Saving..');
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "POST",
      url: "update-item",
      data: data,
      dataType: "json",
      success: function (response) {
        // console.log(response);
        if(response['status'] == 200) {
          $("#edit_item_modal").modal('hide');
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


// //modal Dropdown
// $(document).on('click', '.category-btn', function (e) {
//   e.preventDefault();
//   var data = $('.category-btn').val();
  
//   $('.item-category').val(data);
// console.log(data);
//   $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
//   });

//   $.ajax({
    
//   })
// });
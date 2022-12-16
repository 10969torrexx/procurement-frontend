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
$(document).ready(function() {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
  });
  $.ajax({
    type: "GET",
    url: "get_mode_of_procurement",
    beforeSend : function(){
      $('.mode_of_procurement').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
    },
    success: function (response) {
      $('.mode_of_procurement').empty() 
      $('.mode_of_procurement').append('<option value="" selected disabled>Choose..</option>')
      for(var i = 0; i < response['data'].length; i++) {
      $('.mode_of_procurement').append(
      '<option value="' + response['data'][i]['id'] + '">' 
                        + response['data'][i]['mode_of_procurement'] + '</option>')
        }
      }
    });
});

var input = document.getElementById("item_name");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    
    var mode_of_procurement = $('.mode_of_procurement').val();
    // var category = $('.item_category').val();
    // var type = $('.item_type').val();
    // console.log(category);
    var data = {
      'item_name': $('.item_name').val(),
      'item_category': $('.item_category').val(),
      'app_type': $('.item_type').val(),
      'campus': $('.campus').val(),
      'name': $('.name').val(),
      'mode_of_procurement': $('.mode_of_procurement').val(),
  }
  if(data.item_name=="" || data.item_category=="Choose.." || data.app_type=="Choose.." || mode_of_procurement==null)
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
      url: "add_item",
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
                      $('.add_item').text('Add');
                      $('.item_name').val('');
                      // $('#item-table').DataTable().ajax.reload();
                      location.reload();
                      console.log('I was closed by the timer')
                    }
                  })
              
          } else {
            
            $('.add_item').text('Add');
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Item Already Exist!!',
            })
            $('.add_item').text('Add');
            $('.item_name').val('');
          }
      }
    });
    }
          //  console.log(data);

    
  }
});


$(document).on('click', '.add_item', function (e) {
    e.preventDefault();
    var mode_of_procurement = $('.mode_of_procurement').val();
// alert(mode_of_procurement);
    var data = {
        'item_name': $('.item_name').val(),
        'item_category': $('.item_category').val(),
        'app_type': $('.item_type').val(),
        // 'campus': $('.campus').val(),
        // 'name': $('.name').val(),
        'mode_of_procurement': $('.mode_of_procurement').val(),
    }
    if(data.item_name=="" || data.item_category=="Choose.." || data.app_type=="Choose.." || mode_of_procurement==null)
    {
      Swal.fire('Complete the needed data', '', 'info')
    }
    else{
      // console.log(data);
      $(this).text('Saving..');
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  
      $.ajax({
          type: "POST",
          url: "add_item",
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
                          $('.add_item').text('Add');
                          $('.item_name').val('');
                          // $('#item-table').DataTable().ajax.reload();
                          location.reload();
                          console.log('I was closed by the timer')
                        }
                      })
                  
              } else {
                
                $('.add_item').text('Add');
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Item Already Exist!!',
                })
                $('.add_item').text('Add');
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
                url: "delete_item",
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
  
  var array_mode_of_procurement = [];

$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  } 
});
$.ajax({
  type: "GET",
  url: "get_mode_of_procurement",
  beforeSend : function(){
    $('.update_mode_of_procurement').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
  },
  success: function (response) {
    $('.update_mode_of_procurement').empty() 
    $('.update_mode_of_procurement').append('<option value="" selected disabled>Choose..</option>')
    for(var i = 0; i < response['data'].length; i++) {
      array_mode_of_procurement.push(response['data'][i]['id']);
    $('.update_mode_of_procurement').append(
    '<option value="' + response['data'][i]['id'] + '">' 
                      + response['data'][i]['mode_of_procurement'] + '</option>')
      }
    }
  });

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "edit_item",
      data:{'id' :id},
      success: function (response) {
        if(response['status'] == 200) {
         $("#UpdateItemModal").modal('show');
         $.ajax({
          cache      : false,
          beforeSend : function(html){
            $('.title').text('Item Name');
            $('.update').val('Pls wait..');
            $('#item-category').text('Pls wait..');
            $('#app-type').text('Pls wait..');
            $('#mode_of_procurement').text('Pls wait..');
            $('.update-id').val('Pls wait..');
          },
          success : function(html){

          var mode_of_procurement_id = response['data'][0]['mode_of_procurement_id'];
          $('.title').text('Item Name');
          $('.update').val(response['data'][0]['item_name']);
          $('#item-category').text(response['data'][0]['item_category']);
          $('#app-type').text(response['data'][0]['app_type']);

          var mode_of_procurement_index = array_mode_of_procurement.indexOf(parseInt(mode_of_procurement_id));
          // alert(mode_of_procurement_index);

          document.getElementById('update_mode_of_procurement').getElementsByTagName('option')[mode_of_procurement_index+1].selected = 'selected';
          // console.log(array_mode_of_procurement);

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
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'mode_of_procurement':  $('.update_mode_of_procurement').val(),
      'id': $('.update-id').val(),
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
        url: "update_item",
        data: data,
        dataType: "json",
        success: function (response) {
          // console.log(response);
          if(response['status'] == 200) {
            
            $("#UpdateItemModal").modal('hide');
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
            }else if(response['status'] == 400){
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.message,
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
      'item_name': $('.update').val(),
      'item_category': $('.item-category').val(),
      'app_type': $('.app-type').val(),
      'mode_of_procurement':  $('.update_mode_of_procurement').val(),
      'id': $('.update-id').val(),
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
      url: "update_item",
      data: data,
      dataType: "json",
      success: function (response) {
        // console.log(response);
        if(response['status'] == 200) {
          $("#UpdateItemModal").modal('hide');
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
        }else if(response['status'] == 400){
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: response.message,
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
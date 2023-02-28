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

// var input = document.getElementById("category");
// input.addEventListener("keypress", function(event) {
//     if (event.key === "Enter") {
//       event.preventDefault();
//       var data = {
//           // 'item id': $('.item-id').val(),
//           'category': $('.category').val(),
//       }
//       console.log(data);
//       if( data.category == "")
//       {
//         Swal.fire('Complete the needed data', '', 'info')
//       }
//       else{
//         // $(this).text('Saving..');
//         $.ajaxSetup({
//           headers: {
//               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//           }
//         });
  
//         $.ajax({
//           type: "POST",
//           url: "add-category",
//           data: data,
//           dataType: "json",
//           success: function (response) {
//               if (response.status == 200) {
                
//                       // console.log(response);
//                       Swal.fire({
//                         title: 'Saved',
//                         html: 'Saved Successfully!',
//                         icon: 'success',
//                         timer: 2000,
//                         timerProgressBar: true,
//                         didOpen: () => {
//                           Swal.showLoading()
//                           const b = Swal.getHtmlContainer().querySelector('b')
//                           timerInterval = setInterval(() => {
//                             b.textContent = Swal.getTimerLeft()
//                           }, 100)
//                         },
//                         willClose: () => {
//                           clearInterval(timerInterval)
//                         }
//                       }).then((result) => {
//                         /* Read more about handling dismissals below */
//                         if (result.dismiss === Swal.DismissReason.timer) {
//                           $('.add-category').text('Add');
//                           $('.category').val('');
//                           // $('#item-table').DataTable().ajax.reload();
//                           location.reload();
//                           console.log('I was closed by the timer')
//                         }
//                       })
                  
//               } else {
                
//                 $('.add-category').text('Add');
//                 Swal.fire({
//                   icon: 'error',
//                   title: 'Oops...',
//                   text: 'Already Exist',
//                 })
//                 $('.add-category').text('Add');
//                 $('.category').val('');
//               }
//           }
//         });
//       }
//              console.log(data);
     
//     }
//   });
  
  $(document).on('click', '.add-category', function (e) {
      e.preventDefault();
  
  
      var data = {
          // 'item id': $('.item-id').val(),
          'category': $('.category').val(),
      }
      if( data.category == "")
      {
        Swal.fire('Input Category!!', '', 'info')
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
          url: "add-category",
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
                          $('.add-category').text('Add');
                          $('.mode-of-category').val('');
                          // $('#item-table').DataTable().ajax.reload();
                          location.reload();
                          console.log('I was closed by the timer')
                        }
                      })
                  
              } else {
                
                $('.add-category').text('Add');
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Already Exist',
                })
                $('.add-category').text('Add');
                $('.mode-of-category').val('');
              }
          }
        });
      }
            //  console.log(data);
  
  
  });
  
  
  
  //delete
  $(document).on('click', '.delete-category', function (e) {
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
                url: "delete-category",
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
        url: "show-category",
        data:{'id' :id},
        success: function (response) {
          if(response['status'] == 200) {
          // console.log(response['data'][0]['item_name']);
          // console.log(response);
           $("#editmodal").modal('show');
           $.ajax({
            cache      : false,
            beforeSend : function(html){
              $('.update').val('Pls wait...');
              $('.update-id').val('Pls wait...');
            },
            success: function(html){
              $('.title').text('Category');
              $('.update').val(response['data'][0]['category']);
              $('.update-id').val(response['id']);
              },
            });
           
          }
        }
      });
  });
  
  //update
  
  // var input = document.getElementById("update");
  // input.addEventListener("keypress", function(event) {
  //   if (event.key === "Enter") {
  //     event.preventDefault();
  
  //     var data = {
  //       'category': $('.update').val(),
  //       'id': $('.update-id').val(),
  //   }
  //   if( data.category == "")
  //   {
  //     Swal.fire('Complete the needed data', '', 'info')
  //   }
  //   else{
  //     // $(this).text('Saving..');
  //     $.ajaxSetup({
  //       headers: {
  //           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //       }
  //     });
  //       $.ajax({
  //         type: "POST",
  //         url: "update-category",
  //         data: data,
  //         dataType: "json",
  //         success: function (response) {
  //           // console.log(response);
  //           if(response['status'] == 200) {
              
  //             $("#editmodal").modal('hide');
  //             Swal.fire({
  //               title: 'Updated!!!',
  //               html: 'Update Successfully!',
  //               icon: 'success',
  //               timer: 1000,
  //               timerProgressBar: true,
  //               didOpen: () => {
  //                 Swal.showLoading()
  //                 const b = Swal.getHtmlContainer().querySelector('b')
  //                 timerInterval = setInterval(() => {
  //                   b.textContent = Swal.getTimerLeft()
  //                 }, 100)
  //               },
  //               willClose: () => {
  //                 clearInterval(timerInterval)
  //               }
  //               }).then((result) => {
  //                 /* Read more about handling dismissals below */
  //                 if (result.dismiss === Swal.DismissReason.timer) {
  //                   location.reload();
  //                   console.log('I was closed by the timer')
  //                 }
  //               })
  //             }else{
  //               Swal.fire({
  //                 icon: 'error',
  //                 title: 'Oops...',
  //                 text: 'Already Exist',
  //               })
  //               $(this).text('Update');
  //             }
  //         }
  //       })   
  //     } 
  //   }
  // });
  
  $(document).on('click', '.update-btn', function (e) {
    e.preventDefault();
    var data = {
        'category': $('.update').val(),
        'id': $('.update-id').val(),
    }
  
    if( data.category == "")
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
        url: "update-category",
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
// $(document).ready(function() {
//   $('.js-example-basic-single').select2();
// });

$(document).on('click', '.generatepdf', function (e) {
  var year = $(".Year").val();

  console.log(year);
  if(year == ""){
    Swal.fire('Complete the needed data', '', 'info')
  }
  else{
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "app-non-cse-generate",
      data:{'year': year},
      xhrFields: {
        responseType: 'blob'
      },
      success: function (response) {
        // if(response['status'] == 400){
        //   Swal.fire({
        //       icon: 'error',
        //       title: 'Oops...',
        //       text: 'Incomplete Inputs',
        //     })
        // }else{
          var blob = new Blob([response]);
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "APP_NON_CSE.pdf";
          link.click();
        // }
        // console.log(response);
      },
      error: function(blob){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Incomplete Inputs',
          })
          // console.log(blob);
      }
    });
  }
});

$(document).on('click', '.signaturiesEdit', function (e) {

  var id = $(this).attr("value");
  console.log(id);
// $("edit-signatories").modal('show');
  
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });

  $.ajax({
    type: "post",
    url: "show-signatories",
    data:{'id' :id},
    success: function (response) {
      console.log(response['data'][0]['Title']);
      if(response['status'] == 200) {
          $("#edit_signatories").modal('show');
          var value = response['data'][0]['Name'];

          // $("#inputName").val(response['data'][0]['Name']);
          // $('#choose').text(value);

          // $('selected[name=name]').val(value);
          $('.selectpicker').selectpicker('val', value)

          if(response['data'][0]['Title']=="0"){
            $("#edutitle").val("");
          }
          else{
            $("#edutitle").val(response['data'][0]['Title']);
          }

          $("#inputProfession").val(response['data'][0]['Profession']);
          $(".submitedit").val(response['data'][0]['id']);
      // console.log(response);
      //  $.ajax({
      //   cache      : false,
      //   beforeSend : function(html){
      //     $("#inputName").text('Pls. Wait...');
      //   },
      //   success: function(html){
      //     },
      // });

      //  $(document).on('click', '.category', function (b) {
      //   b.preventDefault();
      //   $('.item-category').val( $('.category'));
      //  });
       
      }
    }
  });
      
});

//show CampusInfo in modal
$(document).on('click', '.campusinfoEdit', function (e) {

  var id = $(this).attr("value");
  console.log(id);
// $("edit-signatories").modal('show');
  
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });

  $.ajax({
    type: "post",
    url: "show-campusinfo",
    data:{'id' :id},
    success: function (response) {
      // console.log(response['data'][0]['Name']);
      if(response['status'] == 200) {
          $("#edit_campusinfo").modal('show');

          $("#address").val(response['data'][0]['address']);
          
          $("#website").val(response['data'][0]['website']);
          $("#email").val(response['data'][0]['email']);
          $("#contact_number").val(response['data'][0]['contact_number']);
          $(".campusinfoedit").val(response['data'][0]['id']);
      // console.log(response);
      //  $.ajax({
      //   cache      : false,
      //   beforeSend : function(html){
      //     $("#inputName").text('Pls. Wait...');
      //   },
      //   success: function(html){
      //     },
      // });

      //  $(document).on('click', '.category', function (b) {
      //   b.preventDefault();
      //   $('.item-category').val( $('.category'));
      //  });
       
      }
    }
  });
      
});

//update Signatories
$(document).on('click', '.submitedit', function (e) {
  var data = {
    'Name': $("#inputName").val(),
    'Profession': $('#inputProfession').val(),
    'Title': $('#edutitle').val(),
    'id': $(this).val(),
  }
  // console.log(data);
  if(data.Name == "" || data.Profession == "" || data.Title == ""){
    Swal.fire('Complete the needed data', '', 'info')
  }else{
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
    
      $.ajax({
        type: "post",
        url: "update-signatories",
        data:data,
        success: function (response) {
          // console.log(response);
          if(response['status'] == 200) {
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
              text: 'Check Inputs',
            })
          }
        }
      });
  }
  

      
});

//update Campus Informations
$(document).on('click', '.campusinfoedit', function (e) {
  var data = {
    'Address': $("#address").val(),
    'Website': $('#website').val(),
    'Email': $('#email').val(),
    'Contact': $('#contact_number').val(),
    'id': $(this).val(),
  }
  console.log(data);
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });

  $.ajax({
    type: "post",
    url: "update-campusinfo",
    data:data,
    success: function (response) {
      console.log(response);
      if(response['status'] == 200) {
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
          text: 'Check Inputs',
        })
      }
    }
  });
});

//edit campus Logo
$(document).on('click', '.campuslogo', function (e) {

  var id = $(this).attr("value");
  // console.log(id);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $("#edit_campuslogo").modal('show');
    $(".campusLogo").val(id);

    // $.ajax({
    //   type: "post",
    //   url: "show-campuslogo",
    //   data:{'id' :id},
    //   success: function (response) {
    //     // console.log(response['data'][0]['Name']);
    //     if(response['status'] == 200) {
        
    //     }
    //   }
    // });
});

//update Campus Logo
$(document).on('click', '.campusLogo', function (e) {
  var data = {
    'Logo': $("#campuslogo").val(),
    'id': $(this).val(),
  }
  // console.log(data);
  if($("#campuslogo").val()==""){
    Swal.fire('Complete the needed data', '', 'info')
  }
  else{
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
    
      $.ajax({
        type: "post",
        url: "update-campuslogo",
        data:data,
        success: function (response) {
          // console.log(response);
          if(response['status'] == 200) {
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
              text: 'Check Inputs',
            })
          }else if(response['status'] == 600){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Controller Error',
            })
          }
        }
      });
  }

      
});

//edit other Logo
$(document).on('click', '.logoEdit', function (e) {

  var id = $(this).attr("value");
  // console.log(id);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $("#edit_logo").modal('show');
    $(".Logo").val(id);

    // $.ajax({
    //   type: "post",
    //   url: "show-campuslogo",
    //   data:{'id' :id},
    //   success: function (response) {
    //     // console.log(response['data'][0]['Name']);
    //     if(response['status'] == 200) {
        
    //     }
    //   }
    // });
});

//update other Logo
$(document).on('click', '.campusLogo', function (e) {
  var data = {
    'Logo': $("#campuslogo").val(),
    'id': $(this).val(),
  }
  // console.log(data);
  if($("#campuslogo").val()==""){
    Swal.fire('Complete the needed data', '', 'info')
  }
  else{
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
    
      $.ajax({
        type: "post",
        url: "update-campuslogo",
        data:data,
        success: function (response) {
          // console.log(response);
          if(response['status'] == 200) {
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
              text: 'Check Inputs',
            })
          }else if(response['status'] == 600){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Controller Error',
            })
          }
        }
      });
  }

      
});

$(document).on('click', '.generateexcel', function (e) {
  var table2excel = new Table2Excel();
  table2excel.export(document.querySelectorAll("#table"));
});

$(document).on('click', '.view', function (e) {
    e.preventDefault();
    var id = $(this).attr("href");
  
    $("#viewmodal").modal('show');
    console.log(id);
    
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });
  
    //   $.ajax({
    //     type: "post",
    //     url: "app-non-cse-view",
    //     data:{'id' :id},
    //     success: function (response) {
    //       if(response['status'] == 200) {
    //     //   // console.log(response['data'][0]['item_name']);
    //     //   // console.log(response);
    //        $("#viewmodal").modal('show');
    //     //    $.ajax({
    //     //     cache      : false,
    //     //     beforeSend : function(html){
    //     //       $('.update').val('Pls wait...');
    //     //       $('.update-id').val('Pls wait...');
    //     //     },
    //     //     success: function(html){
    //     //       $('.title').text('Mode of Procurement');
    //     //       $('.update').val(response['data'][0]['mode_of_procurement']);
    //     //       $('.update-id').val(response['id']);
    //     //       },
    //     //   });
    //       }
           
    //       }
    //     })
  });

  //trigger edit new prepared modal
$(document).on('click', '.newpreparedby', function (e) {
  // console.log($(".Year").val());
  $("#edit_newprepared").modal('show');
  $(".year").val($(".Year").val());
});

$(document).on('click', '.submitprepared', function (e) {

  var data = {
    'Name': $("#preparedName").val(),
    'Profession': $('.preparedProfession').val(),
    'Title': $('.preparedtitle').val(),
    'Year': $(".year").val(),
  }
  console.log(data);
  if(data.Name == "" || data.Profession == "" || data.Title == ""){
    Swal.fire('Complete the needed data', '', 'info')
  }else{
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });

      $.ajax({
        type: "post",
        url: "add-preparedby",
        data:data,
        success: function (response) {
          if(response['status'] == 200) {
            Swal.fire({
              title: 'Submitted!!!',
              html: 'Submitted Successfully!',
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

  //trigger edit new approved modal
$(document).on('click', '.newapprovedby', function (e) {
  $("#edit_newapproval").modal('show');  
  $(".year").val($(".Year").val());
});

$(document).on('click', '.submitapproval', function (e) {

  var data = {
    'Name': $("#approvedName").val(),
    'Profession': $('.approvedProfession').val(),
    'Title': $('.approvededutitle').val(),
    'Year': $(".year").val(),
  }
  if(data.Name == "choose" || data.Profession == "" || data.Title == ""){
    Swal.fire('Complete the needed data', '', 'info')
  }else{
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.ajax({
          type: "post",
          url: "add-approvedby",
          data:data,
          success: function (response) {
            if(response['status'] == 200) {
              Swal.fire({
                title: 'Submitted!!!',
                html: 'Submitted Successfully!',
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

//trigger edit new recommendingapproval modal
// $(document).on('click', '.newrecommendingapproval', function (e) {
//   $("#edit_newrecommendingapproval").modal('show');  
//   $(".year").val($(".Year").val());
// });


$(document).on('click', '.newrecommendingapproval', function (e) {
  // $(".recommending_approval_add").show();

  var btn = document.getElementById("recommending_approval_add");
  btn.style.display = 'block';
  var btn = document.getElementById("newrecommendingapproval");
  btn.style.display = 'none';
  
});

//trigger edit new recommendingapproval modal
$(document).on('click', '.add_recommendingapproval', function (e) {
  $(".submitrecommendingapproval").val($(this).attr("value"));
  $("#edit_newrecommendingapproval").modal('show');  
  $(".year").val($(".Year").val());
  console.log($(this).attr("value"));
});

$(document).on('click', '.submitrecommendingapproval', function (e) {

  var data = {
    'Position': $(this).attr("value"),
    'Name': $("#recommendingapprovalName").val(),
    'Profession': $('.recommendingapprovalProfession').val(),
    'Title': $('.recommendingapprovaltitle').val(),
    'Year': $(".year").val(),
  }
  
  console.log(data);

  if(data.Name == "choose" || data.Profession == "" || data.Title == ""){
    Swal.fire('Complete the needed data', '', 'info')
  }
  else{
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "add-recommendingapproval",
      data:data,
      success: function (response) {
        if(response['status'] == 200) {
          Swal.fire({
            title: 'Submitted!!!',
            html: 'Submitted Successfully!',
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
  
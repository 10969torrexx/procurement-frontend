// $(document).ready(function() {
//   $('.js-example-basic-single').select2();
// });

$(document).on('click', '.generatepdf', function (e) {
  // var year = $(".Year").val();
  // var campusCheck = $(".campusCheck").val();
  var data = {
    'year' :  $(".Year").val(),
    'campusCheck' : $(".campusCheck").val(),
    'category' : $(".project_category").val()
  }

  console.log(data);
  if(data.year == ""){
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
      data:data,
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
            text: 'Incomplete Signatories',
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
      console.log(response);
      if(response['status'] == 200) {
          $("#edit_signatories").modal('show');
          var value = response['data'][0]['users_id'];

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
    'Name': $("#inputName :selected").text(),
    'users_id': $("#inputName").val(),
    'Profession': $('#inputProfession').val(),
    'Title': $('#edutitle').val(),
    'id': $(this).val(),
    'project_category': $(".project_category").val(),
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
    'Name': $("#preparedName :selected").text(),
    'users_id': $("#preparedName").val(),
    'Profession': $('.preparedProfession').val(),
    'Title': $('.preparedtitle').val(),
    'Year': $(".year").val(),
    'project_category': $(".project_category").val(),
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
    'Name': $("#approvedName :selected").text(),
    'users_id': $("#approvedName").val(),
    'Profession': $('.approvedProfession').val(),
    'Title': $('.approvededutitle').val(),
    'Year': $(".year").val(),
    'project_category': $(".project_category").val(),
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
  // console.log($(this).attr("value"));
  

  // $.ajax({
  //   type: 'post',
  //   url: "add_recommendingapproval_modal",
  //   headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
  //   data: {'year' : $(".Year").val()},
  //   success: function(response) {
  //   // console.log(response);
  //   // console.log(response['users']);
  //     if(response['status'] == 200){
  //       // var a = ;
  //       $('.recommendingapprovalName').html("");
  //       var b = document.createElement('option');
  //           b.text = "Choose...";
  //           b.value = "bisan";
  //           b.disabled = true;
  //           b.selected = true;
  //           $('.recommendingapprovalName').append(b);
  //         for (let i = 0; i < response['users'].length; i++){ 
  //           var auth_option = document.createElement('option');
  //           auth_option.value = response['users'][i]['id'];
  //           auth_option.text = response['users'][i]['name']; 
  //           $('.recommendingapprovalName').append(auth_option);
  //         }
  //     }else{

  //     }
  //   },
  //   error: function (data){
  //     console.log(data);
  //   }
  // });
  
});

$(document).on('click', '.submitrecommendingapproval', function (e) {

  var data = {
    'Position': $(this).attr("value"),
    'users_id': $("#recommendingapprovalName").val(),
    'Name': $(".recommendingapprovalName :selected").text(),
    'Profession': $('.recommendingapprovalProfession').val(),
    'Title': $('.recommendingapprovaltitle').val(),
    'Year': $(".year").val(),
    'project_category': $(".project_category").val(),
  }
  
  console.log(data);

  if(data.Name == "choose.." || data.Profession == "" || data.Title == ""){
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
  
//allowed main campus to view
$(document).on('click', '.endorse', function (e) {
  var year = $(".Year").val();
  var data = {
    'year_created' :  $(".Year").val(),
    'endorse' : $(".endorseval").val(),
    'project_category' : $("#project_category").val(),
    'signed_app' : $(".signed_app").val(),
    'file_name' : $(".file_name").val()
  }
// console.log($(".president_stat").val());
console.log(data);
  if ($(".president_stat").val() == 0 && $(".bac_committee_stat").val() == 0) {
    Swal.fire({
      icon: 'info',
      title: 'Oops...',
      text: 'Signatories must be recommended and approved',
    })
  } else if(data.file_name == "" || data.signed_app == ""){
    // Swal.fire({
    //   icon: 'info',
    //   title: 'Oops...',
    //   text: 'Fill-in the needed data !!',
    // })
  }else {
    console.log(data);
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  
    $.ajax({
      type: "POST",
      url: "app-non-cse-done",
      data: data,
      dataType: "json",
      success: function (response) {
        // console.log(response);
        if(response['status'] == 200) {
          Swal.fire({
            title: '',
            html: 'Loading...',
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
              location.reload();
            })
        }else if(response['status'] == 500){
          Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: 'Incomplete Signatories',
          })
          // $(this).text('Shared');
        }else{ 
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Already Exist',
          })
          // $(this).text('Shared');
        }
      }
    })

  }
});

//submit to university president
$(document).on('click', '.submittopresident', function (e) {
  var year = $(".Year").val();
  var data = {
    'year' :  $(".Year").val(),
    'scope' :  $(".scope").val(),
    'submit' : $('.submittopresident').val(),
    'category' : $(this).attr("data-id")
  }
  console.log(data);
  
  Swal.fire({
    title: 'Submit to President?',
    showDenyButton: true,
    confirmButtonText: 'Continue',
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
          type: "POST",
          url: "app-non-cse-submitpres",
          data: data,
          dataType: "json",
          success: function (response) {
            // console.log(response);
            if(response['status'] == 200) {
              Swal.fire({
                title: '',
                html: 'Loading...',
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
                  location.reload();
                })
            }else if(response['status'] == 500){
              Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Incomplete Signatories',
              })
              // $(this).text('Shared');
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Already Exist',
              })
              // $(this).text('Shared');
            }
          }
        })
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
  })

});

$(document).on('click', '.univ_wide', function (e) {
  // var year = $(".Year").val();
  var data = {
    // 'year' :  $(".Year").val(),
    // 'submit' : $(this).val(),
    'category' : $(".project_category").val()
  }
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    type: "POST",
    url: "show-all",
    data: data,
    dataType: "json",
    success: function (response) {
    }
  })
});


$(document).on('click', '.print', function (e) {
  var data = {
      'year' :  $(".Year").val(),
      'campusCheck' : $(".campusCheck").val(),
      'category' : $(".project_category").val(),
      'app_type' : $(".app_type").val(),
      // 'value' : $(this).val()
  };
console.log(data);
$.ajax({
type: 'post',
url: "app-non-cse-print",
headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
data: data,
success: function(viewContent) {
console.log(viewContent);
  if(viewContent){
      var css = '@page { size: Legal landscape; } .campus{background-color:blue;}',
      head = document.head || document.getElementsByTagName('head')[0],
      style = document.createElement('style');

      style.type = 'text/css';
      style.media = 'print';

      if (style.styleSheet){
      style.styleSheet.cssText = css;
      } else {
      style.appendChild(document.createTextNode(css));
      }

      head.appendChild(style);

        var originalContents = document.body.innerHTML;
        document.body.innerHTML = viewContent;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
  }else{
      toastr.error('Can\'t print. Error!')
  }
},
error: function (data){
  console.log(data);
}
});
});

$("form#OtherLogo").submit(function(e) {
  e.preventDefault();
 
  var formData = new FormData(this);    
  
  $.ajax({
      url:'/bac/update-logo',
      type: 'POST',
      data: formData,
      success: function (response) {
          // alert(data)
          if (response.status == 200) {
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Success',
            //   text: response.message,
            //   })
              $('#edit_logo').modal('hide');
              Swal.fire({
                title: 'Saved',
                icon: 'success',
                html: response.message,
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
                }
              })
          }
          if (response.status == 400) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message,
              })
          }
      },
      cache: false,
      contentType: false,
      processData: false
  });


});

$("form#CampusLogo").submit(function(e) {
  e.preventDefault();
 
  var formData = new FormData(this);    
  
  $.ajax({
      url:'/bac/update-campuslogo',
      type: 'POST',
      data: formData,
      success: function (response) {
          // alert(data)
          if (response.status == 200) {
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Success',
            //   text: response.message,
            //   })
              $('#edit_logo').modal('hide');
              Swal.fire({
                title: 'Saved',
                icon: 'success',
                html: response.message,
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
                }
              })
          }
          if (response.status == 400) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message,
              })
          }
      },
      cache: false,
      contentType: false,
      processData: false
  });


});



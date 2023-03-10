$(document).ready(function() {
 
  // $('table input[type=checkbox]').attr('disabled', 'true');
  var pr_no = $('.pr_no').text();
  
    var project_code = $(".project_code").val();
    var employee = $( "#selectEmployee option:selected" ).val();
    var approving_officer = $( "#selectApprovingOfficer option:selected" ).val();
    // var data:  {somekey:$('#AttorneyEmpresa').val()}
    // alert(approving_officer);
    var quantity = 0;
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var data = {
      'project_code': project_code,
      } 

    $.ajax({
      type: "GET",
      url: "/PR/purchaseRequest/getItems",
      data: data,
      beforeSend : function(){
        $('.item').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
      },
      success: function (response) {
        $('.item').empty()
        $('.item').append('<option value="" selected disabled>-- Select Item --</option>')
        for(var i = 0; i < response['data'].length; i++) {
          $('.item').append(
            '<option value="'+ response['data'][i]['id'] + ' ">' 
                              + response['data'][i]['item_name']+ '</option>')
        }
      }
    });

    $.ajax({
      type: "GET",
      url: "/PR/routing_slip/pr_routing_slip/getData",
      data: {pr_no},
      success: function (response) {
// console.log(response)
      response['data'].forEach(element => {
        // console.log(element.date_received)
        $('#myCheckbox'+element.activity).attr('checked', true);
        $('.received'+element.activity).append(element.date_received);
        $('.released'+element.activity).append(element.date_released);
        $('.remark'+element.activity).append(element.remark);
        $('.name'+element.activity).append((element.name).toUpperCase());
      });
        // $('#myCheckbox1').attr('checked', true); 
      }
    });

    $.ajax({
      type: "GET",
      url: "/PR/purchaseRequest/getEmployees",
      beforeSend : function(){
        $('.selectEmployee').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
      },
      success: function (response) {
        // console.log(response)
        $('.selectEmployee').empty()
        $('.selectEmployee').append('<option value="" style="border: none;text-align:center;font-weight:bold;" selected disabled>-- Select Employee --</option>')
        for(var i = 0; i < response['data'].length; i++) {
          if(response['data'][i]['name'] == employee){
            $('.selectEmployee').append(
              '<option style="border: none;text-align:center;font-weight:bold;" value="'+ response['data'][i]['id'] + ' " selected>' 
                                + response['data'][i]['name'] + '</option>')
          }else{
            $('.selectEmployee').append(
              '<option style="border: none;text-align:center;font-weight:bold;" value="'+ response['data'][i]['id'] + ' ">' 
                                + response['data'][i]['name'] + '</option>')
          }
        }
        // $('#EmployeeEditModal').modal('show')

      }
  });

  var total = $(".total").val();
  // alert(total)
    $.ajax({
      type: "GET",
      url: "/PR/purchaseRequest/getApprovingOfficers",
      data: {total},
      beforeSend : function(){
        $('.selectApprovingOfficer').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
      },
      success: function (response) {
        // console.log(response)
        $('.selectApprovingOfficer').empty()
        $('.selectApprovingOfficer').append('<option value="" style="border: none;text-align:center;font-weight:bold;" selected disabled>-- Select Approving Officer --</option>')
        for(var i = 0; i < response['data'].length; i++) {
          if(response['data'][i]['name'] == approving_officer){
            $('.selectApprovingOfficer').append(
              '<option style="border: none;text-align:center;font-weight:bold;" value="'+ response['data'][i]['id'] + '*'+ response['data'][i]['designation'] +  ' " selected>' 
                                + response['data'][i]['name'] + ', '+ response['data'][i]['title'] + '</option>')
            $(".designationApprovingOfficer").text(response['data'][i]['designation']);
            
          }else{
            $('.selectApprovingOfficer').append(
              '<option style="border: none;text-align:center;font-weight:bold;" value="'+ response['data'][i]['id'] + '*'+ response['data'][i]['designation'] + ' ">' 
                                + response['data'][i]['name'] +', '+response['data'][i]['title'] +'</option>')
          }
        }
        // $('#EmployeeEditModal').modal('show')

        // $(".designationApprovingOfficer").text("drgtdfgh");
      }
  });


  $(function(){$(".selectApprovingOfficer").change(function(){

    var designation = $( ".selectApprovingOfficer option:selected" ).val();
    var splitDesignation = designation.split('*');
    $(".designationApprovingOfficer").text(splitDesignation[1]);
  
    });
  })
  
});

$("#PreviewPRModal").on("shown.bs.modal", function(e){



})

$(function(){$(".item").change(function(){
  var project_code = $(".project_code").val();
  var item = $(".item option:selected").val();
// alert(item);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
  });
  var data = {
    'item': item,
    'project_code': project_code,
    } 
  $.ajax({
    type: "GET",
    url: "/PR/purchaseRequest/getItem",
    data: data,
    success: function (response) {
      
      if (response.status == 200) {
        $('.quantity').val(response['data']); 
      }
      if (response.status == 400) {
        $('.quantity').val(''); 
        Swal.fire({
          icon: 'info',
          title: 'Information',
          text: response.message,
          })
        document.getElementById('item').getElementsByTagName('option')[0].selected = 'selected';

      }
    }
  });

});
})

$(document).on('click', '.create-PR-button', function (e) {
    e.preventDefault();
    var id = $(this).attr("href"); 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
});

$('.select-all').click(function(event) {   
  if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
          this.checked = true;                        
      });
  } else {
      $(':checkbox').each(function() {
          this.checked = false;                       
      });
  }
}); 

$(document).on('click', '.PR_button', function (e) {
  e.preventDefault();
  
  var item = $(".item").val();
  var quantity = $(".quantity").val();
  var file_name = $(".file").val();
  var specification = $(".specification").val();
  var project_code = $(".project_code1").val();

  // alert(item);
  
  if(item==null){
    Swal.fire({
        icon: 'error',
        title: 'Incomplete',
        text: 'Please select item for PR!',
        })
   }else if(quantity=='' || quantity <= 0){
  Swal.fire({
      icon: 'error',
      title: 'Incomplete',
      text: 'Please enter quantity!',
      })
}else if(file_name==''){
  Swal.fire({
      icon: 'error',
      title: 'Incomplete',
      text: 'Please select a file!',
      })
}else if(specification==''){
  Swal.fire({
      icon: 'error',
      title: 'Incomplete',
      text: 'Please enter specification!',
      })
}else{
          var data = {
              'item': item,
              'quantity': quantity,
              'file_name': file_name,
              'specification': specification,
              'project_code': project_code,
              }

              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
      
              $.ajax({
                  type: "POST",
                  url: "/PR/purchaseRequest/addItem",
                  data: data,
                  dataType: "json",
                  success: function (response) {
                      if (response.status == 200) {
                              Swal.fire({
                                title: 'Added',
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
                          
                      } if(response.status == 400){
                        Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: response.message,
                        })
                          // $('.submitbutton').text('Save');
                      }
                  }
              });
      }
  // alert('itemArray '+itemArray);
      
});

// $(document).on('click', '.PR_button', function (e) {
//     e.preventDefault();
    

//     var item = $(".item").val();
//     var quantity = $(".quantity").val();
//     alert(quantity);
//     var itemCount = $(".itemCount").val();
//     var itemChecked = document.getElementsByClassName('form-check-input');
//     var itemArray = [];
//         for (let i = 0; i < itemCount; i++) {
//                 if(itemChecked[i].checked){
//                 itemArray.push(itemChecked[i].value); 
//             }
//         }

 //   var itemCount = $(".itemCount").val();
 //   var itemChecked = document.getElementsByClassName('form-check-input');
 //   var itemArray = [];
  //      for (let i = 0; i < itemCount; i++) {
 //           if(itemChecked[i].checked){
 //              itemArray.push(itemChecked[i].value); 
  //          }
 //       }

        
//         if(itemArray==''){
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: 'Please select items for PR!',
//                 })
//         }else{
//             var data = {
//                 'items': itemArray,
//                 // 'itemCount': itemCount
//                 }

//                 $.ajaxSetup({
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     }
//                 });
        
//                 $.ajax({
//                     type: "POST",
//                     url: "/PR/purchaseRequest/add_Items_To_PR",
//                     data: data,
//                     dataType: "json",
//                     success: function (response) {
//                         if (response.status == 200) {
//                                 Swal.fire({
//                                   title: 'Added',
//                                   icon: 'success',
//                                   html: 'Items Added Successfully!',
//                                   timer: 1000,
//                                   timerProgressBar: true,
//                                   didOpen: () => {
//                                     Swal.showLoading()
//                                     const b = Swal.getHtmlContainer().querySelector('b')
//                                     timerInterval = setInterval(() => {
//                                       b.textContent = Swal.getTimerLeft()
//                                     }, 100)
//                                   },
//                                   willClose: () => {
//                                     clearInterval(timerInterval)
//                                   }
//                                 }).then((result) => {
//                                   if (result.dismiss === Swal.DismissReason.timer) {
//                                     location.reload();
//                                   }
//                                 })
                            
//                         } if(response.status == 400){
//                           Swal.fire({
//                             icon: 'error',
//                             title: 'Error',
//                             text: 'Item Already Exist!',
//                           })
//                             $('.submitbutton').text('Save');
//                         }
//                     }
//                 });
//         }
//     // alert('itemArray '+itemArray);
        
// });

$(document).on('click', '.btnCompletePR', function (e) {
  e.preventDefault();
  
  var purpose_input = $(".purpose_input").val();
  var designation_input = $(".designation_input").val();
  var selectEmployee = $(".selectEmployee").val();
  var approvingOfficer = $(".selectApprovingOfficer").val();

      if(purpose_input==''){
          Swal.fire({
              icon: 'error',
              title: 'Incomplete',
              text: 'Please enter Purpose!',
              })
      }else if(designation_input==''){
        Swal.fire({
            icon: 'error',
            title: 'Incomplete',
            text: 'Please enter Designation!',
            })
      }else if(selectEmployee==null){
        Swal.fire({
            icon: 'error',
            title: 'Incomplete',
            text: 'Please select Employee!',
            })
      }else if(approvingOfficer==null){
        Swal.fire({
            icon: 'error',
            title: 'Incomplete',
            text: 'Please select Approving Officer!',
            })
      }else{
        var fund_source = $(".fund_source_id").val()
        var project_code = $(".project_code").val()
        var pr_no = $(".pr_no").val();

        var data = {
                'employee': selectEmployee,
                'approvingOfficer': approvingOfficer,
                'purpose': purpose_input,
                'designation': designation_input,
                'fund_source': fund_source,
                'project_code': project_code,
                'pr_no': pr_no,
                }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/PR/purchaseRequest/savePR",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                  
                      $('#PreviewPRModal').modal('hide')

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
                    
                } if(response.status == 400){
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong!',
                  })
                    $('.btnCompletePR').text('Complete PR');
                }
            }
        });
      }
});

$(document).on('click', '.employeeEdit', function (e) {
  e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
      type: "GET",
      url: "/PR/purchaseRequest/getEmployees",

      success: function (response) {
        $('.employee').empty()
        $('.employee').append('<option value="" selected disabled>-- Choose --</option>')
        for(var i = 0; i < response['data'].length; i++) {
          $('.employee').append(
            '<option value="'+ response['data'][i]['id'] + ' ">' 
                              + response['data'][i]['name'] + '</option>')
        }
        // $('#EmployeeEditModal').modal('show')

      }
  });
});

$(document).on('click', '.saveButton', function (e) {
  e.preventDefault();

  var employeeVal = $(".employee").val();
  var employeeText = $( ".employee option:selected" ).text();
// alert(employee1)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

      if(employeeVal==null){
          Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please select employee!',
              })
      }else{
        $('#EmployeeEditModal').modal('hide')
        $('.selectEmployee').text(employeeText.toUpperCase());
        $('.selectEmployee').val(employeeVal);
        $('.selectEmployee').append(
          '<i class="fa-solid fa-pen-to-square employeeEdit" value="" style="margin-left:5px;">')
      }
});

$(document).on('click', '.removebutton', function (e) {
  e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var id = $(this).attr("href");
    var pr_no = $(".pr_no").val();
    var data = {
      'id': id,
      'pr_no': pr_no,
    }
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
            if (result.isConfirmed) {
              $.ajax({
                type: "POST",
                url: "/PR/purchaseRequest/createPR/remove_item",
                data:data,
                success: function (response) {
                      if(response['status'] == 200) {
                        Swal.fire({
                          title: 'Removed!',
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
                        }
                      })
                    } if(response.status == 400){
                      Swal.fire({ 
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                      })
                    }
                }
            });
            }else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
      })
});


$(document).on('click', '.print', function (e) {
  var data = {
      // 'fund_cluster' :  $(".fund_cluster").text(),
      // 'department_name' :  $(".department_name").text(),
      // 'pr_no' :  $(".pr_no").text(),
      'id' : $(".id").val(),
      // 'category' : $(".project_category").val(),
      // 'app_type' : $(".app_type").val(),
      // 'value' : $(this).val()
  };

// console.log(data);
    $.ajax({
    type: 'post',
    url: "/department/trackPR/view_pr/printPR",
    headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
    data: data,
    success: function(viewContent) {
    // console.log(viewContent);
        if(viewContent){
            var css = '@page { size: A4 portrait; }',
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
              // location.reload();
        }else{
            toastr.error('Can\'t print. Error!')
        }
        },
      error: function (data){
        console.log(data);
      }
    });

// console.log(data);
  $.ajax({
  type: 'post',
  url: "/PR/trackPR/view_pr/printPR",
  headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
  data: data,
  success: function(viewContent) {
  // console.log(viewContent);
    if(viewContent){
        var css = '@page { size: A4 portrait; }',
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
          // location.reload();
    }else{
        toastr.error('Can\'t print. Error!')
    }
  },
  error: function (data){
    console.log(data);
  }
  });

});

$(document).on('click', '.editbutton', function (e) {
  e.preventDefault();
  var project_code = $(".project_code").val();
  var id = $(this).attr("href");

  // alert(id);
    $.ajax({
      type: 'GET',
      url: "/purchase-request/get-item",
      headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
      data: {
        'id'  : id
      },
      success: function(response) {
        // ! show when success
          if(response['status'] == 200) {
            console.log(response['data']);
            response['data'].forEach(element => {
                $('#default-id').val(element.id);
            });
          } 
        // ! show when error
          if(response['status'] == 400) {
            Swal.fire({ 
              icon: 'error',
              title: 'Error',
              text: response['message'],
            })
          }
      }
    });



  // alert(project_code);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
  });
  var data = {
    'item': id,
    'project_code': project_code,
    } 
  $.ajax({
    type: "GET",
    url: "/PR/purchaseRequest/editPRItem",
    data: data,
    success: function (response) {
      // console.log(response.data)
      if (response.status == 200) {
            // Get a reference to our file input
            // const fileInput = document.querySelector('.updatefile');

            // Create a new File object
            // const myFile = new File(['new file'], response.file_name, {
                // type: 'text/plain',
                // lastModified: Date.now(),
            // });

            // Now let's create a DataTransfer to get a FileList
            // const dataTransfer = new DataTransfer();
            // dataTransfer.items.add(myFile);
            // fileInput.files = dataTransfer.files;

        // var dataArray = [];
        // for(var i=0; i < response.data.length;i++){
        //   dataArray.push(response.data[i])
        // }
        // console.log(dataArray);
        // $('.data').val(response.data); 
        $('.updatename').val(response.item_name); 
        $('.updatequantity').val(response.quantity); 
        $('.updateid').val(response.item_id); 
        $('.id').val(response.id); 
        $('.updatespecification').val(response.specification); 
        $('#EditPRModal').modal('show');

      }
      if (response.status == 400) {
        $('.quantity').val(''); 
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.message,
          })
        document.getElementById('item').getElementsByTagName('option')[0].selected = 'selected';
      }
    }
  });


});
// END EDIT BUTTON
$(document).on('click', '.edit_SignedPR_button', function (e) {
  e.preventDefault();

  var id = $(this).attr("href");
  var data = {
      'id': id,
    } 
    $.ajax({
      type: "GET",
      url: "/PR/signed_pr/edit_signed_pr",
      data: data,
      success: function (response) {
        if (response.status == 200) {
          // console.log(response)
          response['data'].forEach(element => {
            $('#signedPR_id').val(element.id);
            $('#update_pr_no').val(element.pr_no);
           });
          $('#update_file_name').val(response['file_name'][0]);
          $('#EditSignedPRModal').modal('show');
        }
        if (response.status == 400) {
          $('.quantity').val(''); 
          Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message,
            })
        }
      }
  });


});

$("form#createPR").submit(function(e) {
  e.preventDefault();
//  alert('success')
  var formData = new FormData(this);    
  
  $.ajax({
      url:'/PR/purchaseRequest/addItem',
      type: 'POST',
      data: formData,
      success: function (response) {
          // alert(data)
          if (response.status == 200) {
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

$("form#updateSignedPR").submit(function(e) {
  e.preventDefault();
 
  var formData = new FormData(this);    
  
  $.ajax({
      url:'/PR/signed_pr/update_signed_pr',
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
              $('#EditSignedPRModal').modal('hide');
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

$("form#updateItem").submit(function(e) {
  e.preventDefault();
 
  var formData = new FormData(this);    
  
  $.ajax({
      url:'/PR/purchaseRequest/updateItem',
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
              $('#EditPRModal').modal('hide');
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

$("form#upload_signed_pr").submit(function(e) {
  e.preventDefault();
//  alert('success');  
 var formData = new FormData(this);    
  
  $.ajax({
      url:'/PR/signed_pr/upload_signed_pr',
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

$(document).on('click', '.deletebutton', function (e) {
  e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var id = $(this).attr("href");
    var pr_no = $(this).attr("rel");
    var data = {
      'id': id,
      'pr_no': pr_no,
      };
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
            if (result.isConfirmed) {
              $.ajax({
                type: "POST",
                url: "/PR/trackPR/delete_pr",
                data:data,
                success: function (response) {
                      if(response['status'] == 200) {
                        Swal.fire({ 
                          icon: 'success',
                          title: 'Success',
                          text: response['message'],
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
                      }if(response.status == 400){
                        Swal.fire({ 
                          icon: 'error',
                          title: 'Error',
                          text: response.message,
                        })
                      }
                }
            });
            }else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
      })
});

$(document).on('click', '.delete_SignedPR_button', function (e) {
  e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var id = $(this).attr("href");
    var data = {
      'id': id,
      };
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
            if (result.isConfirmed) {
              $.ajax({
                type: "POST",
                url: "/PR/signed_pr/delete_signed_pr",
                data:data,
                success: function (response) {
                      if(response['status'] == 200) {
                      location.reload();
                        Swal.fire({
                          title: 'Success',
                          html: response.message,
                          icon: 'success',
                        })
                      }if(response.status == 400){
                        Swal.fire({ 
                          icon: 'error',
                          title: 'Error',
                          text: response.message,
                        })
                      }
                }
            });
            }else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
      })
});

$(document).on('click', '.approve_pr', function (e) {
  e.preventDefault();

  var pr_no = $(".pr_no").text();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
      type: 'POST',
      url: "/purchase_request/pending_prs/approve_or_disapprove",
      headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
      data: {
        'status'  : 2,
        'pr_no'  : pr_no,
        'remark'  : 'Approved',
      },
      success: function(response) {
        // ! show when success
          if(response.status == 200) {
            
            Swal.fire({ 
              icon: 'success',
              title: 'Success',
              text: response['message'],
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
        // ! show when error
          if(response['status'] == 400) {
            Swal.fire({ 
              icon: 'info',
              title: 'Information',
              text: response['message'],
            })
          }
      }
    });
  
});

$(document).on('click', '.disapprove_pr_modal', function (e) {
  e.preventDefault();

    $('#DisapprovePRModal').modal('show');
  
});

$(document).on('click', '.disapprove_pr', function (e) {
  e.preventDefault();

  var pr_no = $(".pr_no").text();
  var remark = $(".reject_remarks").val();

  if(remark == ''){
    Swal.fire({ 
      icon: 'error',
      title: 'Error',
      text: 'Please enter remark!',
    })
  }else{

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: 'POST',
      url: "/purchase_request/pending_prs/approve_or_disapprove",
      headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
      data: {
        'status'  : 3,
        'pr_no'  : pr_no,
        'remark'  : remark,
      },
      success: function(response) {
        // ! show when success
          if(response.status == 200) {
            
            $('#DisapprovePRModal').modal('hide');
            
            Swal.fire({ 
              icon: 'success',
              title: 'Success',
              text: response['message'],
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
        // ! show when error
          if(response['status'] == 400) {
            Swal.fire({ 
              icon: 'info',
              title: 'Information',
              text: response['message'],
            })
          }
      }
    });
  }
  
});

$("form#RSSubmit").submit(function(e) {
  e.preventDefault();
//  alert('success')
  var formData = new FormData(this);    

$.ajax({
  type: 'POST',
  url: "/PR/routing_slip/pr_routing_slip/saveChanges",
  data: formData,
  success: function(response) {
    // ! show when success
      if(response.status == 200) {
        $('#RoutingSlipModal').modal('hide');
        
        Swal.fire({ 
          icon: 'success',
          title: 'Success',
          text: response['message'],
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
    // ! show when error
      if(response['status'] == 400) {
        Swal.fire({ 
          icon: 'info',
          title: 'Information',
          text: response['message'],
        })
      }
    },
    cache: false,
    contentType: false,
    processData: false
  });

});

$(document).on('click','.saveChanges', function (e) {
  e.preventDefault();
  // alert('azdfg');
    var countCheckbox = $('input[type="checkbox"]').length-1;
    // var checkboxes = $('input:checkbox:checked').length-1;
    // var checkboxes = $('input:checkbox:not(":checked")').length;
    // var checkboxes = $('#myCheckbox').val(1);

    // var checkboxes = document.getElementById('myCheckbox');
    var checkedArray = [];
    var date= $('#received'+'1').val();
    var date= $('#received'+'1').val();
    // alert(date)
      $("input:checkbox:checked").each(function(){
          checkedArray.push($(this).val());
      });

     
  
    // console.log(checkedArray)
});


$("input[type='checkbox']").change(function() {
  // e.preventDefault();
  if(this.checked){
    var activityNumber =  $(this).val();
    $('.activityNumber').val(activityNumber);
    $('#RoutingSlipModal').modal('show');
  }
  
//   var value = $(this).val()
//   var countCheckbox = $('input[type="checkbox"]').length;
// // alert(countCheckbox)
// $("input:checkbox[id=myCheckbox"+value+"]:checked").each(function(){
//   $('#received'+value).removeAttr('hidden');
//   $('#released'+value).removeAttr('hidden');
//   $('#remark'+value).removeAttr('hidden');


// });

// if(this.checked==false){
//     for (let i = 0; i < countCheckbox; i++) {
//       if(i ==value){
//         // $("#reeived2").show();
//         $("#received"+value).attr("hidden",true);
//         $("#released"+value).attr("hidden",true);
//         $("#remark"+value).attr("hidden",true);

//       }
//     }
//   }
});
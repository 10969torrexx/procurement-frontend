$(document).on('click', '.create-PR-button', function (e) {
    e.preventDefault();
    var id = $(this).attr("href");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
});

$(document).on('click', '.PR_button', function (e) {
    e.preventDefault();
    
    var itemCount = $(".itemCount").val();
    var itemChecked = document.getElementsByClassName('form-check-input');
    var itemArray = [];
        for (let i = 0; i < itemCount; i++) {
            if(itemChecked[i].checked){
                itemArray.push(itemChecked[i].value); 
            }
        }
        
        if(itemArray==''){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select items for PR!',
                })
        }else{
            var data = {
                'items': itemArray,
                // 'itemCount': itemCount
                }
                // console.log(data);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        
                $.ajax({
                    type: "POST",
                    url: "/department/purchaseRequest/add_Items_To_PR",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                      console.log(response);
                        if (response.status == 200) {
                                Swal.fire({
                                  title: 'Added',
                                  icon: 'success',
                                  html: 'Items Added Successfully!',
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
                            
                        } if(response.status == 400){
                          Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Department Already Exist!',
                          })
                            $('.submitbutton').text('Save');
                        }
                    }
                });
        }
    // alert('itemArray '+itemArray);
        
});

$(document).on('click', '.btnCompletePR', function (e) {
  e.preventDefault();
  
  var purpose_input = $(".purpose_input").val();
  var designation_input = $(".designation_input").val();
  var selectEmployee = $(".selectEmployee").val();

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
      }else if(selectEmployee==''){
        Swal.fire({
            icon: 'error',
            title: 'Incomplete',
            text: 'Please select Employee!',
            })
      }else{
        var selectEmployee = $(".selectEmployee").val();
        var purpose_input = $(".purpose_input").val();
        var designation_input = $(".designation_input").val()
        var fund_source = $(".fund_source_id").val()
        var project_code = $(".project_code").val()
          
        var data = {
                'employee': selectEmployee,
                'purpose': purpose_input,
                'designation': designation_input,
                'fund_source': fund_source,
                'project_code': project_code,
                }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/department/purchaseRequest/savePR",
            data: data,
            dataType: "json",
            success: function (response) {
              console.log(response);
                if (response.status == 200) {
                        Swal.fire({
                          title: 'Saved',
                          icon: 'success',
                          html: 'Purchase Request Completed Successfully!',
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
      url: "/department/purchaseRequest/getEmployees",

      success: function (response) {
        $('.employee').empty()
        $('.employee').append('<option value="" selected disabled>-- Choose --</option>')
        for(var i = 0; i < response['data'].length; i++) {
          $('.employee').append(
            '<option value="'+ response['data'][i]['id'] + ' ">' 
                              + response['data'][i]['name'] + '</option>')
        }
        $('#EmployeeEditModal').modal('show')

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

$("#AllocateBudgetModal").on("hidden.bs.modal", function(e){
  // location.reload();
  // $('.department').val('');
  //     $('.fund_source').val('');
  //     $('.budget').val('');
  //     $('.mandatory_expenditures').val('');
})
$("#UpdateAllocateBudgetModal").on("hidden.bs.modal", function(e){
  // location.reload();
})
// $("#AllocateBudgetModal").on("show.bs.modal", function(e){
//   // $("#bodyModal").html('<i class="bx bx-loader-circle"></i> Please wait...');
//   $.ajax({
//     cache      : false,
//     beforeSend : function(){
//       $('.modal-body').html('<i class="spinner-border spinner-border-sm"></i> Loading... ');

//     },
//     success: function(result){
//       $('.modal-body').show();
//     },
//   });
//   // $("#bodyModalImage").html('<i class="bx bx-loader-circle"></i> Please wait...');
// })
$(document).ready(function() {
  $('.mandatory_expenditures').select2({
    theme: 'bootstrap-5',
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    // placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false
  });
  $('.update_mandatory_expenditures').select2({
    theme: 'bootstrap-5',
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false
  });
});

$(function(){$(".year").change(function(){
    
  var year = $(".year option:selected").val();

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
  });
  $.ajax({
    type: "GET",
    url: "getDeadlineByYear",
    data: {year},
    dataType: "json",
    success: function (response) {
      // alert(response);
      $('.end_date').empty()
      // console.log(response.deadline)
          $('.end_date').val(response['deadline'][0]['end_date']);
        }
    });

});
})

$(function(){$(".type").change(function(){
    
  var type = $(".type option:selected").val();
  var type1 = $(".type option:selected").text();

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
  });
  $.ajax({
    type: "GET",
    url: "getYearByType",
    data: {type},
    dataType: "json",
    success: function (response) {
      // alert(response);
      // $('.year').empty()
      // console.log(response.years)
          // $('.year').val(response['years']);
          $('.year').empty()
          $('.year').append('<option value="" selected disabled>-- Select Year --</option>')
          for(var i = 0; i < response['years'].length; i++) {
            $('.year').append(
              '<option value="' + response['years'][i]['year'] + '">' 
                                + response['years'][i]['year']+ '</option>')
          }if(response['years'].length == 0){
            Swal.fire({
              icon: 'info',
              title: 'Information',
              text: 'Please set Deadline first for '+type1+'!',
            })
          }
        }
    });

});
})

$(document).on('click', '.AllocateBudget', function (e) {
  e.preventDefault();

      $('.type').val('');
      $('.department').val('');
      $('.year').val('');
      $('.fund_source').val('');
      $('.budget').val('');
      $('.mandatory_expenditures').val('');

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      } 
    });

  $.ajax({
      type: "GET",
      url: "get_Departments",
      beforeSend : function(){
              $('.department').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
      },
      success: function (response) { 
            $('.department').empty()
            $('.department').append('<option value="" selected disabled>-- Select Department --</option>')
        for(var i = 0; i < response['departments'].length; i++) {
          $('.department').append(
            '<option value="' + response['departments'][i]['id'] + '">' 
                              + response['departments'][i]['department_name'] + '</option>')

        }
      }
  });
//   $.ajax({
//     type: "GET",
//     url: "get_Years",
//     // data: {department},
//     dataType: "json",
//     beforeSend : function(){
//       $(".year").html('<option class="bx bx-loader-circle">Please wait...</option> ');
//         },
//     success: function (response) {
//       // alert(response);
//       $('.year').empty()
//       $('.year').append('<option value="" selected disabled>-- Select Year --</option>')
//       // console.log(response.years)
//       for(var i = 0; i < response['years'].length; i++) {
//         // appending response years to the unit of measurement element
//         $('.year').append(
//           '<option value="' + response['years'][i]['year'] + ' ">' 
//                             + response['years'][i]['year'] + ' </option>')

//       }
//     }
// });
  $.ajax({
    type: "GET",
    url: "get_FundSources",
    beforeSend : function(){
      $('.fund_source').html('<option class="spinner-border spinner-border-sm">Please wait... </option> ');
    },
    success: function (response) {
      // alert(response);
      // console.log(response.data)
      $('.fund_source').empty()
      $('.fund_source').append('<option value="" selected disabled>-- Select Fund Source --</option>')
      for(var i = 0; i < response['fund_sources'].length; i++) {
        // appending response data to the unit of measurement element
        $('.fund_source').append(
          '<option value="' + response['fund_sources'][i]['id'] + '">' 
                            + response['fund_sources'][i]['fund_source'] + '</option>')

      }
    }
  });
  $.ajax({
    type: "GET",
    url: "get_Mandatory_Expenditures",
    success: function (response) {
      $('.mandatory_expenditures').empty()
      for(var i = 0; i < response['expenditures'].length; i++) {
        $('.mandatory_expenditures').append(
          '<option value="' + response['expenditures'][i]['id'] + '">' 
                            + response['expenditures'][i]['expenditure'] + '</option>')
      }
    }
  });
});

// START ALLOCATE BUDGET BUTTON
$(document).on('click', '.btnAllocateBudget', function (e) {
  e.preventDefault();
  
  // alert('success');
  $(this).text('Allocating Budget..');

      var type = document.getElementById('type').value;
      var department = document.getElementById('department').value;
      var year = document.getElementById('year').value;
      var fund_source = document.getElementById('fund_source').value;
      var budget = document.getElementById('budget').value;
      var mandatory_expenditure = document.getElementById('mandatory_expenditures').value;
      if(type==''|| department==''|| year=='' || fund_source=='' || budget==''|| mandatory_expenditure==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
        $(this).text('Allocate Budget');
      }else{
        var mandatory_expenditures_array = [];
      for (var option of document.querySelector('.mandatory_expenditures').options)
      {
          if (option.selected) {
            mandatory_expenditures_array.push(option.text);
          }
      }

      var mandatory_expenditures = mandatory_expenditures_array.join(',');
      // alert(mandatory_expenditures);
      var data = {
          'year': $('.year').val(),
          'type': $('.type').val(),
          // 'start_date': $('.start_date').val(),
          'end_date': $('.end_date').val(),
          'department': $('.department').val(),
          'fund_source': $('.fund_source').val(),
          'budget': $('.budget').val(),
          'mandatory_expenditures': mandatory_expenditures,
      }
      // console.log(data);
  
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $.ajax({
          type: "POST",
          url: "save_allocate_budget",
          data: data,
          dataType: "json",
          success: function (response) {
            // console.log(response);
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
                          // $('#AllocateBudgetModal').modal('hide');
                          location.reload();
                          console.log('I was closed by the timer')
                        }
                      })
                  
              } if(response.status == 400){
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response.message,
                })
                  // $('#AddUserModal').find('input').val('');
                  $('.btnAllocateBudget').text('Allocate Budget');
                  // $('#AddUserModal').modal('hide');
              }
          }
      });
      }
      
})
// END ALLOCATE BUDGET BUTTON

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
              url: "delete_allocated_budget",
              data:{'id':id},
              success: function (response) {
                // alert(response.status);
                if (response.status == 200) {
                  Swal.fire({
                    title: 'Deleted!',
                    html: 'Deadline Deleted Successfully!',
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

        $.ajax({
          type: "GET",
          url: "get_Departments",
          success: function (response) {
                $('.update_department').empty()
                $('.update_department').append('<option value="" selected disabled>-- Select Department --</option>')
            for(var i = 0; i < response['departments'].length; i++) {
              $('.update_department').append(
                '<option value="' + response['departments'][i]['id'] + '">' 
                                  + response['departments'][i]['department_name'] + '</option>')

            }
          }
        });

        $.ajax({
          type: "GET",
          url: "get_FundSources",
          success: function (response) {
            $('.update_fund_source').empty()
            $('.update_fund_source').append('<option value="" selected disabled>-- Select Fund Source --</option>')
            for(var i = 0; i < response['fund_sources'].length; i++) {
              $('.update_fund_source').append(
                '<option value="' + response['fund_sources'][i]['id'] + '">' 
                                  + response['fund_sources'][i]['fund_source'] + '</option>')
            }
          }
        });

        $.ajax({
          type: "GET",
          url: "get_Mandatory_Expenditures",
          success: function (response) {
            $('.update_mandatory_expenditures').empty()
            for(var i = 0; i < response['expenditures'].length; i++) {
              $('.update_mandatory_expenditures').append(
                '<option selected value="' + [response['expenditures'][i]['id']] + '">' 
                                  + [response['expenditures'][i]['expenditure']] + '</option>')
            }
          }
        });

        $(function(){$(".update_type").change(function(){
    
          var type = $(".update_type option:selected").val();
          var type1 = $(".update_type option:selected").text();
        
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } 
          });
          $.ajax({
            type: "GET",
            url: "getYearByType",
            data: {type},
            dataType: "json",
            success: function (response) {
              // alert(response);
              // $('.year').empty()
              // console.log(response.years)
                  // $('.year').val(response['years']);
                  $('.update_year').empty()
                  $('.update_year').append('<option value="" selected disabled>-- Select Year --</option>')
                  for(var i = 0; i < response['years'].length; i++) {
                    $('.update_year').append(
                      '<option value="' + response['years'][i]['year'] + '">' 
                                        + response['years'][i]['year']+ '</option>')
                  }if(response['years'].length == 0){
                    Swal.fire({
                      icon: 'info',
                      title: 'Information',
                      text: 'Please set Deadline first for '+type1+'!',
                    })
                    $('#UpdateAllocateBudgetModal').modal('hide');

                  }
                }
            });
        
        });
        })
// START EDIT BUTTON
$(document).on('click', '.editbutton', function (e) {
  e.preventDefault();
  // $('#UpdateAllocateBudgetModal').modal('show');

  var id = $(this).attr("href");
  // alert(id);
   $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      
  $.ajax({
      type: "POST",
      url: "edit_allocated_budget",
      data:{'id':id},
      success: function (response) {
        console.log(response);
          if (response.status == 200) {
            var department_id = response['allocated_budget'][0]['department_id'];
            var fund_source_id = response['allocated_budget'][0]['fund_source_id'];
            var type = response['allocated_budget'][0]['procurement_type'];
            var array_department = [];
            var array_fundsources = [];
            var array_type = ['Indicative','Supplemental','PPMP'];

            for (let i = 0; i < response['departments'].length; i++) {
              array_department.push(response['departments'][i]['id']);
            }

            for (let i = 0; i < response['fund_sources'].length; i++) {
              array_fundsources.push(response['fund_sources'][i]['id']);
            }
            var type_index = array_type.indexOf(type);
            var department_index = array_department.indexOf(parseInt(department_id));
            var fundsource_index = array_fundsources.indexOf(parseInt(fund_source_id));

            document.getElementById('update_type').getElementsByTagName('option')[type_index+1].selected = 'selected';
            document.getElementById('update_department').getElementsByTagName('option')[department_index+1].selected = 'selected';
            document.getElementById('update_fund_source').getElementsByTagName('option')[fundsource_index+1].selected = 'selected';
            $('.update_budget').val(response['allocated_budget'][0]['allocated_budget']);
            $('.updateid').val(response['allocated_budget'][0]['id']);
            $('#UpdateAllocateBudgetModal').modal('show');
          } 
      }
  });
  $('.btn-close').find('input').val('');

});
// END EDIT BUTTON

// START UPDATE BUTTON
$(document).on('click', '.updatebutton', function (e) {
  e.preventDefault();

  $(this).text('Updating..');
  var type = document.getElementById('update_type').value;
  var department = document.getElementById('update_department').value;
  var fund_source = document.getElementById('update_fund_source').value;
  var budget = document.getElementById('update_budget').value;
  var mandatory_expenditure = document.getElementById('update_mandatory_expenditures').value;
  if(type==''|| department==''|| fund_source=='' || budget==''|| mandatory_expenditure==''){
    Swal.fire({
          icon: 'error',
          title: 'Error', 
          text: 'Please provide the needed information!',
        })
    $(this).text('Update');
  }else{
    var mandatory_expenditures_array = [];
    for (var option of document.querySelector('.update_mandatory_expenditures').options)
    {
        if (option.selected) {
          mandatory_expenditures_array.push(option.text);
        }
    }
    var mandatory_expenditures = mandatory_expenditures_array.join(', ');
  
    var data = {
        'update_type': $('.update_type').val(),
        'update_department': $('.update_department').val(),
        'update_fund_source': $('.update_fund_source').val(),
        'update_budget': $('.update_budget').val(),
        'update_mandatory_expenditures': mandatory_expenditures,
        'updateid': $('.updateid').val(),
    }
  // console.log(data);
  
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  
    $.ajax({
      type: "POST",
      url: "update_allocated_budget",
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
                  location.reload();
                  // $('#UpdateAllocateBudgetModal').modal('hide');
                  console.log('I was closed by the timer')
            }
          })
          }if(response.status == 400){
            Swal.fire({ 
              icon: 'error',
              title: 'Error',
              text: response.message,
            })
              // $('#AddUserModal').find('input').val('');
              $('.updatebutton').text('Update');
              // $('#AddUserModal').modal('hide');
          }
      }
    })
  }
  
});
// END UPDATE BUTTON
$("#MandatoryExpenditureModal").on("hidden.bs.modal", function(e){
  // location.reload();
})
$(document).on('click', '.add_btn', function (e) {
// e.preventDefault();

$('.expenditure').val('');
$('.price').val('');

$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  } 
});

$.ajax({
  type: "GET",
  url: "getDepartments",
  cache: false,
  beforeSend : function(){
          $('.department').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
  },
  success: function (response) { 
  // console.log(response)
        $('.department').empty()
        $('.department').append('<option value="" selected disabled>-- Select Department --</option>')
    for(var i = 0; i < response.length; i++) {
      $('.department').append(
        '<option value="' + response[i]['id'] + '">' 
                          + response[i]['department_name'] + '</option>')

    }
  }
});
$.ajax({
type: "GET",
url: "getFundSources",
cache: false,
beforeSend : function(){
  $('.fund_source').html('<option class="spinner-border spinner-border-sm">Please wait... </option> ');
},
success: function (response) {
  // alert(response);
  // console.log(response.data)
  $('.fund_source').empty()
  $('.fund_source').append('<option value="" selected disabled>-- Select Fund Source --</option>')
  for(var i = 0; i < response.length; i++) {
    // appending response data to the unit of measurement element
    $('.fund_source').append(
      '<option value="' + response[i]['id'] + '">' 
                        + response[i]['fund_source'] + '</option>')

  }
}
});
$.ajax({
type: "GET",
url: "getYears",
  cache: false,
  beforeSend : function(){
  $(".year").html('<option class="bx bx-loader-circle">Please wait...</option> ');
    },
success: function (response) {
  // alert(response);
  $('.year').empty()
  $('.year').append('<option value="" selected disabled>-- Select Year --</option>')
  // console.log(response.years)
  for(var i = 0; i < response.length; i++) {
    // appending response years to the unit of measurement element
    $('.year').append(
      '<option value="' + response[i]['year'] + ' ">' 
                        + response[i]['year'] + ' </option>')

  }
}
});
$.ajax({
  type: "GET",
  url: "getMandatoryExpenditures",
  cache: false,
  beforeSend : function(){
    $(".expenditure").html('<option class="bx bx-loader-circle">Please wait...</option> ');
      },
  success: function (response) {
    // alert(response);
    $('.expenditure').empty()
    $('.expenditure').append('<option value="" selected disabled>-- Select Expenditure --</option>')
    // console.log(response.years)
    for(var i = 0; i < response.length; i++) {
      // appending response years to the unit of measurement element
      $('.expenditure').append(
        '<option value="' + response[i]['id'] + ' ">' 
                          + response[i]['expenditure'] + ' </option>')

    }
    $('#MandatoryExpenditureModal').modal('show');
  }
});

});

$(document).on('click', '.btnAddExpenditure', function (e) {
e.preventDefault();
// alert('hey');
var department = document.getElementById('department').value;
var fund_source = document.getElementById('fund_source').value;
var year = document.getElementById('year').value;
var expenditure = document.getElementById('expenditure').value;
var price = document.getElementById('price').value;

$.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      } 
    });

if(department==''|| fund_source==''|| year==''|| expenditure==''|| price==''){
      Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please provide the needed information!',
          })
}
else{
      var data = {
            'department': department,
            'fund_source': fund_source,
            'year': year,
            'expenditure': expenditure,
            'price': price,
      }
      // alert(data);
      $.ajax({
            type: "POST",
            url: "addMandatoryExpenditure",
            data: data,
            dataType: "json",
            cache: false,
            success: function (response) {
              // console.log(response);
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

$.ajax({
type: "GET",
url: "getDepartments",
  cache: false,
  success: function (response) {
    $('.update_department').empty()
    $('.update_department').append('<option value="" selected disabled>-- Select Department --</option>')
for(var i = 0; i < response.length; i++) {
  $('.update_department').append(
    '<option value="' + response[i]['id'] + '">' 
                      + response[i]['department_name'] + '</option>')

}
}
});

$.ajax({
type: "GET",
url: "getFundSources",
cache: false,
success: function (response) {
$('.update_fund_source').empty()
$('.update_fund_source').append('<option value="" selected disabled>-- Select Fund Source --</option>')
for(var i = 0; i < response.length; i++) {
  $('.update_fund_source').append(
    '<option value="' + response[i]['id'] + '">' 
                      + response[i]['fund_source'] + '</option>')
}
}
});

$.ajax({
type: "GET",
url: "getMandatoryExpenditures",
cache: false,
success: function (response) {
$('.update_expenditure').empty()
for(var i = 0; i < response.length; i++) {
  $('.update_expenditure').append(
    '<option selected value="' + [response[i]['id']] + '">' 
                      + [response[i]['expenditure']] + '</option>')
}
}
});

$.ajax({
type: "GET",
url: "getYears",
cache: false,
beforeSend : function(){
$(".update_year").html('<option class="bx bx-loader-circle">Please wait...</option> ');
  },
success: function (response) {
// alert(response);
$('.update_year').empty()
$('.update_year').append('<option value="" selected disabled>-- Select Year --</option>')
// console.log(response.years)
for(var i = 0; i < response.length; i++) {
  // appending response years to the unit of measurement element
  $('.update_year').append(
    '<option value="' + response[i]['year'] + ' ">' 
                      + response[i]['year'] + ' </option>')

}
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
      url: "editMandatoryExpenditure",
      data:{'id':id},
      cache: false,
      success: function (response) {

          if (response.status == 200) {
            

            var array_department = [];
            for (let i = 0; i < response['department_ids'].length; i++) {
              array_department.push(response['department_ids'][i]['id']);
            }

            var array_fundsources = [];
            for (let i = 0; i < response['fund_source_ids'].length; i++) {
              array_fundsources.push(response['fund_source_ids'][i]['id']);
            }

            var array_expenditures = [];
            for (let i = 0; i < response['expenditure_ids'].length; i++) {
              array_expenditures.push(response['expenditure_ids'][i]['id']);
            }
            
            var array_years = [];
            for (let i = 0; i < response['years'].length; i++) {
              array_years.push(response['years'][i]['year']);
            }

            var department_id = response['data'][0]['department_id'];
            var expenditure_id = response['data'][0]['expenditure_id'];
            var fund_source_id = response['data'][0]['fund_source_id'];
            var year = response['data'][0]['year'];

            var department_index = array_department.indexOf(parseInt(department_id));
            var expenditure_index = array_expenditures.indexOf(parseInt(expenditure_id));
            var fundsource_index = array_fundsources.indexOf(parseInt(fund_source_id));
            var year_index = array_years.indexOf(year.toString());

            document.getElementById('update_department').getElementsByTagName('option')[department_index+1].selected = 'selected';
            document.getElementById('update_expenditure').getElementsByTagName('option')[expenditure_index].selected = 'selected';
            document.getElementById('update_fund_source').getElementsByTagName('option')[fundsource_index+1].selected = 'selected';
            document.getElementById('update_year').getElementsByTagName('option')[year_index+1].selected = 'selected';

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

var department = document.getElementById('update_department').value;
var fund_source = document.getElementById('update_fund_source').value;
var year = document.getElementById('update_year').value;
var expenditure = document.getElementById('update_expenditure').value;
var price = document.getElementById('update_price').value;
var id = document.getElementById('update_id').value;
// alert(expenditure); 
// console.log(expenditure);     

if(department==''||fund_source==''||year==''||expenditure==''||price==''){
      Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please provide the needed information!',
          })
          $('.btnUpdateExpenditure').text('Update');
}
else{
      var data = {
            'department': department,
            'fund_source': fund_source,
            'year': year,
            'expenditure': expenditure,
            'price': price,
            'id': id,
      }
	  // console.log(data);
      // alert(expenditure); 
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } 
          });
      $.ajax({
            type: "POST",
            url: "updateMandatoryExpenditure",
            data: data,
            dataType: "json",
            cache: false,
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
        url: "deleteMandatoryExpenditure",
        data:{'id':id},
        cache: false,
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
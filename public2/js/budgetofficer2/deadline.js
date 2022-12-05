$("#SetDeadlineModal").on("hidden.bs.modal", function(e){
  // location.reload();
})
$("#UpdateDeadlineModal").on("hidden.bs.modal", function(e){
  // location.reload();
})

// $("#datepicker").datepicker( {
//   format: "mm-yyyy",
//   startView: "months", 
//   minViewMode: "months"
// });
// $(function() {
//   $( ".start_date" ).datepicker({  minDate: new Date() });
// });
$(document).on('click', '.set_deadline', function (e) {
  $('.year').val('');
  $('.start_date').val('');
  $('.end_date').val('');
});

$(function(){
    $(".year").change(function(){
        var year = $(".year option:selected").text();
        $(".get_year").val(year);
    });
  })

$(document).on('click', '.btnSetDeadline', function (e) {
    e.preventDefault();
    $(this).text('Saving..');

      var year = document.getElementById('year').value;
      var start_date = document.getElementById('start_date').value;
      var end_date = document.getElementById('end_date').value;
      if(year==''|| start_date=='' || end_date==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please select date!',
            })
        $(this).text('Save');
      }else{
        var data = {
          'year': $('.year').val(),
          'start_date': $('.start_date').val(),
          'end_date': $('.end_date').val(),
      }
// alert(data);
              // console.log(data);

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
  
        $.ajax({
            type: "POST",
            url: "ppmp_deadline",
            data: data,
            dataType: "json",
            success: function (response) {
              // console.log(response);
                if (response.status == 200) {
                        Swal.fire({
                          title: 'Saved!',
                          icon: 'success',
                          html: response.message,
                          timer: 1500,
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
                            $('#SetDeadlineModal').modal('hide');
                            location.reload();
                            console.log('I was closed by the timer')
                          }
                        })
                    
                }else if(response.status == 400){
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                  })
                    // $('#AddUserModal').find('input').val('');
                    $('.btnSetDeadline').text('Set Deadline');
                    // $('#AddUserModal').modal('hide');
                }
            }
        });
      }
});

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
              url: "delete_deadline",
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

$.ajax({
  type: "GET",
  url: "ppmp_deadline",
  // beforeSend : function(){
  //         $('.update_department').html('<option class="spinner-border spinner-border-sm">Please wait... </option>');
  // },
  success: function (response) {
    // alert(response);
    // console.log(response.data)
    //     $('.update_department').empty()
    //     $('.update_department').append('<option value="" selected disabled>-- Select Department --</option>')
    // for(var i = 0; i < response['departments'].length; i++) {
    //   // appending response data to the unit of measurement element
    //   $('.update_department').append(
    //     '<option value="' + response['departments'][i]['id'] + '">' 
    //                       + response['departments'][i]['department_name'] + '</option>')

    // }
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
      url: "edit_deadline",
      data:{'id':id},
      success: function (response) {

          if (response.status == 200) {
          console.log(response);
          // $('.update_year').pickadate({
          //   format: 'yyyy/mm/dd',
          //   formatSubmit: 'yyyy/mm/dd'
          // })
          var year = response['deadline'][0]['year'];
          // var update_start_date = response[0]['start_date'];
          // var update_end_date = response[0]['end_date'];
          // const collection = document.getElementsByTagName("li");
          // document.getElementById("demo").innerHTML = collection[1].innerHTML;
          // document.getElementById('update_year').options[0].selected = 'selected';
          // document.getElementById('update_year').getElementsByTagName('option')[year].selected = 'selected'
          // document.getElementById('update_year').options[document.getElementById('update_year').selectedIndex].text;
          // document.getElementById('update_start_date').getElementsByTagName('option')[update_start_date].selected = 'selected'
          // document.getElementById('update_end_date').getElementsByTagName('option')[update_end_date].selected = 'selected'
            // $('#update_year').val(response['deadline'][0]['year']);
            // $('.update_start_date').val(response['deadline'][0]['start_date']);
            // $('.update_end_date').val(response['deadline'][0]['end_date']);
            $('.updateid').val(response['id']);  
            $('#UpdateDeadlineModal').modal('show');
          } 
      }
  });
  $('.btn-close').find('input').val('');

});

//update
$(document).on('click', '.updatebutton', function (e) {
  e.preventDefault();

  $(this).text('Updating..');
  var update_year = document.getElementById('update_year').value;
  var update_start_date = document.getElementById('update_start_date').value;
  var update_end_date = document.getElementById('update_end_date').value;
  if(update_year==''|| update_start_date=='' || update_end_date==''){
    Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Please select date!',
        })
    $(this).text('Update');
  }else{
    var data = {
      'update_year': $('.update_year').val(),
      'update_start_date': $('.update_start_date').val(),
      'update_end_date': $('.update_end_date').val(),
      'updateid': $('.updateid').val(),
  }
console.log(data);

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    type: "POST",
    url: "update_deadline",
    data: data,
    dataType: "json",
    success: function (response) {
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
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {
                $('#UpdateDeadlineModal').modal('hide');
                location.reload();
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
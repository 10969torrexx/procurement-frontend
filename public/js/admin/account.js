 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function(){

    $.ajax({
      type: "GET",
      url: "get_accounts_HRMIS",
      dataType: "json",
      cache: false,
      beforeSend : function(){
        $(".employee").html('<option class="bx bx-loader-circle">Please wait...</option> ');
          },
      success: function (response) {
        // alert(response);
        $('.employee').empty()
        $('.employee').append('<option value="" selected disabled>-- Select Employee --</option>')
        // console.log(response.data)
        for(var i = 0; i < response['data'].length; i++) {
          // appending response data to the unit of measurement element
          $('.employee').append(
            '<option value="' + response['data'][i]['FirstName'] + '*' 
                              + response['data'][i]['MiddleName'] + '*' 
                              + response['data'][i]['LastName'] + '*' 
                              + response['data'][i]['EmailAddress'] + '*' 
                              + response['data'][i]['Departmentid'] + '*' 
                              + response['data'][i]['id'] + ' ">' 
                              + response['data'][i]['LastName'] + ', ' 
                              + response['data'][i]['FirstName'] + '</option>')
        }
      },
  error: function(error){
    console.log(error);
  }
  });

        $.ajax({
        type: "get",
        url: "getDepartmentsByCampus",
        dataType: "json",
        beforeSend : function(){
          $(".department").html('<option class="bx bx-loader-circle">Please wait...</option> ');
            },
        success: function (response) {
          // alert(response);
          $('.department').empty()
          $('.department').append('<option value="" selected disabled>-- Select Department --</option>')
          // console.log(response)
          for(var i = 0; i < response.length; i++) {
            // appending response data to the unit of measurement element
            $('.department').append(
              '<option value="' + response[i]['id'] + ' ">' 
                                + response[i]['department_name'] + '</option>')

          }
        }
    });

    $("#AddUserModal").on("hidden.bs.modal", function(e){
      // location.reload();
    })
    $("#UpdateUserModal").on("hidden.bs.modal", function(e){
      // location.reload();
    })
    $("#AddUserModal").on("show.bs.modal", function(e){
          $("#bodyModalCreate").html('<i class="bx bx-loader-circle"></i> Please wait...');
      // $("#bodyModalImage").html('<i class="bx bx-loader-circle"></i> Please wait...');
    })

    
    $(document).on('click', '.btnAddUser', function (e) {
      e.preventDefault();
      $('.employee').val('');
      $('.role').val('');
      $('.department').val('');
      
     
    });

    $(document).on('click', '.submitbutton', function (e) {
      e.preventDefault();
      
      $(this).text('Saving..');
      var employee = document.getElementById('employee').value;
      var role = document.getElementById('role').value;
      var department = document.getElementById('department').value;

      if(employee=='' || role=='' || department==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
        $(this).text('Save');
      }else{
        var data = {
          'employee': $('.employee').val(),
          'role': $('.role').val(),
          'department': $('.department').val(),
      }
      
      $.ajax({
          type: "POST",
          url: "save_account",
          data: data,
          dataType: "json",
          success: function (response) {
            // console.log(response);
              if (response.status == 200) {
                      Swal.fire({
                        title: 'Saved',
                        icon: 'success',
                        html: 'Account Saved Successfully!',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                          Swal.showLoading()
                          const b = Swal.getHtmlContainer().querySelector('b')
                          timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                          }, 100)
                          // location.reload();

                        },
                        willClose: () => {
                          clearInterval(timerInterval)
                        }
                      }).then((result) => {
                        // $('.submitbutton').text('Save');
                        // $('.accountname').val('');
                        // $('.selectrole').option('Select Role');
                        // $('.email').val('');
                        // $('.rolename').val('');
                        // $('.campus').val('');
                          // $('#AddUserModal').modal('hide');

                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {

                          $('.submitbutton').text('Save');
                          $('.campus').val('');
                          $('.employee').val('');
                          $('.role').val('');
                          $('.department').val('');
                          $("#AddUserModal").on("hidden.bs.modal", function(e){
                            location.reload();
                          })
                          // $('#AddUserModal').modal('hide');
                          // location.reload();
                          // console.log('I was closed by the timer')
                        }
                      })
                  
              } if(response.status == 400){
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'User Already Exist!',
                })
                  // $('#AddUserModal').find('input').val('');
                  $('.submitbutton').text('Save');
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
          if (result.isConfirmed) {
            $.ajax({
                  type: "post",
                  url: "delete_account",
                  data:{'id':id},
                  dataType: "json",
                  success: function (response) {
                    if (response.status == 200) {
                      Swal.fire({
                        title: 'Deleted!',
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
          }else{
            Swal.fire('dddChanges are not saved', '', 'info')
          }
        })
    });
  
    $(document).on('click', '.editbutton', function (e) {
      e.preventDefault();
      var id = $(this).attr("href");
      // alert(id);
      
      $.ajax({
          type: "POST",
          url: "edit_account",
          data:{'id':id},
          success: function (response) {

              if (response.status == 200) {
              var role = response['user'][0]['role'];
              var department = response['user'][0]['department_id'];

              document.getElementById('updateselectrole').getElementsByTagName('option')[role].selected = 'selected'

                $('.updatename').val(response['user'][0]['name']);
                $('.updateid').val(response['id']);

                $.ajax({
                  type: "get",
                  url: "getDepartmentsByCampus",
                  dataType: "json",
                  beforeSend : function(){
                    $(".updatedepartment").html('<option class="bx bx-loader-circle">Please wait...</option> ');
                      },
                  success: function (response) {
                    $('.updatedepartment').empty()
                    $('.updatedepartment').append('<option value="" selected disabled>-- Select Department --</option>')
                    var array_department = [];
                    for(var i = 0; i < response.length; i++) {
                      array_department.push(response[i]['id']);
                      $('.updatedepartment').append(
                        '<option value="' + response[i]['id'] + ' ">' 
                                          + response[i]['department_name'] + '</option>')
                    }

                    var department_index = array_department.indexOf(parseInt(department));
                    // console.log(department);
                    document.getElementById('updatedepartment').getElementsByTagName('option')[department_index+1].selected = 'selected';

                    $('#UpdateUserModal').modal('show');
            
                  }
              })
              } 
          }
      });
      $('.btn-close').find('input').val('');

  });

//update
$(document).on('click', '.updatebutton', function (e) {
  e.preventDefault();

  $(this).text('Updating..');
  // $(this).text('Saving..');
  var updatename = document.getElementById('updatename').value;
  var updateselectrole = document.getElementById('updateselectrole').value;
  var updatedepartment = document.getElementById('updatedepartment').value;
  // var updateemail = document.getElementById('updateemail').value;
  if(updatename==''|| updateselectrole=='' || updatedepartment==''){
    Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Please provide the needed information!',
        })
    $(this).text('Update');
  }else{
    var data = {
      'updatename': $('.updatename').val(),
      'updateselectrole': $('.updateselectrole').val(),
      'updatedepartment': $('.updatedepartment').val(),
      // 'updateemail': $('.updateemail').val(),
      'updateid': $('.updateid').val(),
  }


  $.ajax({
    type: "POST",
    url: "update_account",
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
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {
                $('#UpdateUserModal').modal('hide');
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

    $(document).on("click", '#btnAllAjaxSearch', function(e){
        e.preventDefault();
        $('#btnAllAjaxSearch').html("Searching...");
        $("#searchresult").html('');
        $("#allMsg").html("");
        $.ajax({
            type: "POST",
            url: "/router",
            cache: false,
            data: $("#frmAllImage").serialize(),
            success: function (data) {
              $('#btnAllAjaxSearch').html('<i class="bx bx-search"></i> Search');
              $("#searchresult").html(data);
            }
        });
    });
  })
   
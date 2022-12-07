    // $(document).ready(function () {
    //   $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    
    // });
  
  //   $(function(){
  //       $(".selectrole").change(function(){
  //           var rolename = $(".selectrole option:selected").text();
  //           $(".rolename").val(rolename);
  //           // $(".rolename").val(rolename);
  //       });
  //   })

  //   $(function(){
  //     $(".selectcampus").change(function(){
  //         var campus = $(".selectcampus option:selected").text();
  //         $(".campus").val(campus);
  //     });
  //   })

  //   $(function(){
  //     $(".updateselectrole").change(function(){
  //         var rolename = $(".updateselectrole option:selected").text();
  //         $(".updaterolename").val(rolename);
  //         // $(".rolename").val(rolename);
  //     });
  // })





  // START -- This will get the campus and pass it to HRMIS API
 
  // END this will get the campus and pass it to HRMIS API

  // $(function(){
  //   var department = "Department";
  //   $(".role").change(function(){
  //       if( $('.role').val()== department){
  //         $('.departmentrow').append(department);
  //       }
  //   });
  // })
    
    $("#AddDepartmentModal").on("hidden.bs.modal", function(e){
      // location.reload();
    })
    $("#UpdateDepartmentModal").on("hidden.bs.modal", function(e){
      // location.reload();
    })
    $("#AddUserModal").on("show.bs.modal", function(e){
          $("#bodyModalCreate").html('<i class="bx bx-loader-circle"></i> Please wait...');
      // $("#bodyModalImage").html('<i class="bx bx-loader-circle"></i> Please wait...');
    })

  // START -- This will get the campus and pass it to HRMIS API
  $(function(){
    $(".campus").change(function(){
    
        var campus = $(".campus option:selected").val();

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          } 
        });

      $.ajax({
          type: "POST",
          url: "getUsers",
          data: {campus},
          dataType: "json",
          beforeSend : function(){
            $(".department_head").html('<option class="bx bx-loader-circle">Please wait...</option> ');
              },
          success: function (response) {
            // alert(response);
            $('.department_head').empty()
            $('.department_head').append('<option value="" selected disabled>-- Select Department Head --</option>')
            $('.immediate_supervisor').empty()
            $('.immediate_supervisor').append('<option value="" selected disabled>-- Select Immediate Supervisor--</option>')
            // console.log(response)
            for(var i = 0; i < response.length; i++) {
              // appending response data to the unit of measurement element
              $('.department_head').append(
                // + response['message'][i]['id'] + '
                '<option value="' + response[i]['name'] + ' ">' 
                                  + response[i]['name'] + '</option>')

              $('.immediate_supervisor').append(
                // + response['message'][i]['id'] + '
                '<option value="' + response[i]['name'] + ' ">' 
                                  + response[i]['name'] + '</option>')
            }
          }
      });

    //   $.ajax({
    //     type: "POST",
    //     url: "getSupervisors",
    //     data: {campus},
    //     dataType: "json",
    //     beforeSend : function(){
    //       $(".immediate_supervisor").html('<option class="bx bx-loader-circle">Please wait...</option> ');
    //         },
    //     success: function (response) {
    //       // console.log(response);
    //       // alert(response);
    //       $('.immediate_supervisor').empty()
    //       $('.immediate_supervisor').append('<option value="" selected disabled>-- Select Immediate Supervisor--</option>')
    //       // console.log(response.data)
    //       for(var i = 0; i < response['message'].length; i++) {
    //         // appending response data to the unit of measurement element
    //         $('.immediate_supervisor').append(
    //           // + response['message'][i]['id'] + '
    //           '<option value="' + response['message'][i]['FirstName'] + ' ' 
    //                             + response['message'][i]['LastName'] + ' ">' 
    //                             + response['message'][i]['FirstName'] + ' ' 
    //                             + response['message'][i]['LastName'] + '</option>')

    //       }
    //     }
    // });
    });
  })
  // END this will get the campus and pass it to HRMIS API
  
  $(document).on('click', '.btnNewDepartment', function (e) {
    e.preventDefault();
    $('.campus').val('');
    $('.department_name').val('');
    $('.department_description').val('');
    $('.department_head').val('');
    $('.immediate_supervisor').val('');
  });

    $(document).on('click', '.submitbutton', function (e) {
      e.preventDefault();

      $(this).text('Saving..');
      var campus = document.getElementById('campus').value;
      var department_name = document.getElementById('department_name').value;
      var department_description = document.getElementById('department_description').value;
      var department_head = document.getElementById('department_head').value;
      var immediate_supervisor = document.getElementById('immediate_supervisor').value;
      if(campus==''|| department_name=='' || department_description==''|| department_head==''|| immediate_supervisor==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
        $(this).text('Save');
      }else{
        var data = {
          'campus': $('.campus').val(),
          'department_name': $('.department_name').val(),
          'department_description': $('.department_description').val(),
          'department_head': $('.department_head').val(),
          'immediate_supervisor': $('.immediate_supervisor').val(),
          }
              // console.log(data);
      // alert([data);
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $.ajax({
          type: "POST",
          url: "saveDepartment",
          data: data,
          dataType: "json",
          success: function (response) {
            console.log(response);
              if (response.status == 200) {
                      Swal.fire({
                        title: 'Saved',
                        icon: 'success',
                        html: 'Department Saved Successfully!',
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
                        // $('.submitbutton').text('Save');
                        // $('.accountname').val('');
                        // $('.selectrole').option('Select Role');
                        // $('.email').val('');
                        // $('.rolename').val('');
                        // $('.campus').val('');
                          // $('#AddUserModal').modal('hide');

                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                          $('#AddDepartmentModal').modal('hide');
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
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          if (result.isConfirmed) {
            $.ajax({
                  type: "post",
                  url: "deleteDepartment",
                  data:{'id':id},
                  success: function (response) {
                    // alert(response.status);
                    if (response.status == 200) {
                      Swal.fire({
                        title: 'Deleted!',
                        html: 'Department Deleted Successfully!',
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

    $(function(){
      $(".update_campus").change(function(){
      
          var campus = $(".update_campus option:selected").val();
  
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } 
          });
  
        $.ajax({
            type: "POST",
            url: "getDepartmentHeads",
            data: {campus},
            dataType: "json",
            beforeSend : function(){
              $(".update_department_head").html('<option class="bx bx-loader-circle">Please wait...</option> ');
                },
            success: function (response) {
              // alert(response);
              $('.update_department_head').empty()
              $('.update_department_head').append('<option value="" selected disabled>-- Select Department Head --</option>')
              console.log(response.data)
              for(var i = 0; i < response['message'].length; i++) {
                // appending response data to the unit of measurement element
                $('.update_department_head').append(
                  // '<option value="' + response['message'][i]['id'] + ' ">' 
                  '<option value="' + response['message'][i]['FirstName'] + ' ' 
                                    + response['message'][i]['LastName'] + ' ">' 
                                    + response['message'][i]['FirstName'] + ' ' 
                                    + response['message'][i]['LastName'] + '</option>')
  
              }
            }
        });

        $.ajax({
          type: "POST",
          url: "getSupervisors",
          data: {campus},
          dataType: "json",
          beforeSend : function(){
            $(".update_immediate_supervisor").html('<option class="bx bx-loader-circle">Please wait...</option> ');
              },
          success: function (response) {
            // console.log(response);
            // alert(response);
            $('.update_immediate_supervisor').empty()
            $('.update_immediate_supervisor').append('<option value="" selected disabled>-- Select Immediate Supervisor--</option>')
            // console.log(response.data)
            for(var i = 0; i < response['message'].length; i++) {
              // appending response data to the unit of measurement element
              $('.update_immediate_supervisor').append(
                // + response['message'][i]['id'] + '
                '<option value="' + response['message'][i]['FirstName'] + ' ' 
                                  + response['message'][i]['LastName'] + ' ">' 
                                  + response['message'][i]['FirstName'] + ' ' 
                                  + response['message'][i]['LastName'] + '</option>')
  
            }
          }
      });
      });
    })

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
          url: "editDepartment",
          data:{'id':id},
          success: function (response) {

              if (response.status == 200) {
              // console.log(response);

                var old_campus = response['department'][0]['campus'];

                document.getElementById('update_campus').getElementsByTagName('option')[old_campus].selected = 'selected'
                $('.update_department_name').val(response['department'][0]['department_name']);
                $('.update_department_description').val(response['department'][0]['description']);
                $('.update_department_head').val(response['department'][0]['department_head']);
                $('.update_immediate_supervisor').val(response['department'][0]['immediate_supervisor']);
                $('.update_id').val(response['id']);
                // $('#UpdateDepartmentModal').modal('show');
              } 

              var departmentHead = response['department'][0]['department_head'];
              var supervisor = response['department'][0]['immediate_supervisor'];
              var campus = $(".update_campus option:selected").val();

              $.ajax({
                type: "POST",
                url: "getDepartmentHeads",
                data:{'campus':campus},
                success: function (response) {
        
                    if (response.status == 200) {
                    // console.log(response);
                    // alert('success');
        
                    $('.update_department_head').empty()
                    $('.update_department_head').append('<option value="" selected disabled>-- Select Department Head --</option>')
                    // console.log(response.data)
                    for(var i = 0; i < response['message'].length; i++) {
                      // appending response data to the unit of measurement element
                      $('.update_department_head').append(
                        // '<option value="' + response['message'][i]['id'] + ' ">' 
                        '<option value="' + response['message'][i]['FirstName'] + ' ' 
                                          + response['message'][i]['LastName'] + ' ">' 
                                          + response['message'][i]['FirstName'] + ' ' 
                                          + response['message'][i]['LastName'] + '</option>')
                    }
                    var array_departmentHead = [];
                    for (let i = 0; i < response['message'].length; i++) {
                      array_departmentHead.push(response['message'][i]['FirstName']+' '+response['message'][i]['LastName']);
                    }
                    var departmentHead_index = array_departmentHead.indexOf(departmentHead);
                    // console.log('departmentHead_index: '.departmentHead_index);
                      document.getElementById('update_department_head').getElementsByTagName('option')[departmentHead_index+1].selected = 'selected';
                      // $('#UpdateDepartmentModal').modal('show');

                    } 
                }
            });

            $.ajax({
              type: "POST",
              url: "getSupervisors",
              data:{'campus':campus},
              success: function (response) {
      
                  if (response.status == 200) {
                  console.log(response);
                  // alert('success');
      
                  $('.update_immediate_supervisor').empty()
                  $('.update_immediate_supervisor').append('<option value="" selected disabled>-- Select Department Head --</option>')
                  // console.log(response.data)
                  for(var i = 0; i < response['message'].length; i++) {
                    // appending response data to the unit of measurement element
                    $('.update_immediate_supervisor').append(
                      // '<option value="' + response['message'][i]['id'] + ' ">' 
                      '<option value="' + response['message'][i]['FirstName'] + ' ' 
                                        + response['message'][i]['LastName'] + ' ">' 
                                        + response['message'][i]['FirstName'] + ' ' 
                                        + response['message'][i]['LastName'] + '</option>')
                  }
                  var array_supervisor = [];
                  for (let i = 0; i < response['message'].length; i++) {
                    array_supervisor.push(response['message'][i]['FirstName']+' '+response['message'][i]['LastName']);
                  }
                  var supervisor_index = array_supervisor.indexOf(supervisor);
                  // console.log('supervisor_index: '.supervisor_index);
                    document.getElementById('update_immediate_supervisor').getElementsByTagName('option')[supervisor_index+1].selected = 'selected';
                    $('#UpdateDepartmentModal').modal('show');

                  } 
              }
          });


          }
      });

     
      $('.btn-close').find('input').val('');

  });

//update
$(document).on('click', '.updatebutton', function (e) {
  e.preventDefault();

  $(this).text('Updating..');
      // var campus = document.getElementById('campus').value;
      var department_name = document.getElementById('update_department_name').value;
      var department_description = document.getElementById('update_department_description').value;
      var department_head = document.getElementById('update_department_head').value;
      var immediate_supervisor = document.getElementById('update_immediate_supervisor').value;
      if(department_name==''|| department_description==''|| department_head==''|| immediate_supervisor==''){
        Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please provide the needed information!',
            })
        $(this).text('Update');
      }else{
        var data = {
          'update_campus': $('.update_campus').val(),
          'update_department_name': $('.update_department_name').val(),
          'update_department_description': $('.update_department_description').val(),
          'update_department_head': $('.update_department_head').val(),
          'update_immediate_supervisor': $('.update_immediate_supervisor').val(),
          'update_id': $('.update_id').val(),
      }
      // console.log(data);
    
      
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    
      $.ajax({
        type: "POST",
        url: "updateDepartment",
        data: data,
        dataType: "json",
        success: function (response) {
              if(response['status'] == 200) {
                Swal.fire({
                  title: 'Updated!',
                  html: response['message'],
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
                    $('#UpdateDepartmentModal').modal('hide');
                    location.reload();
                    console.log('I was closed by the timer')
              }
            })
            }else if(response['status'] == 400) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Department Already Exist!',
                })
                  $('.updatebutton').text('Update');
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
  
   
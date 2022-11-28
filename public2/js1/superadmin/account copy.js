    // $(document).ready(function () {
    //     $("#txtdate2").datepicker({
    //         minDate: 0
    //     });
    // });
  
    $(function(){
        $("#SelectRole").change(function(){
            var rolename = $("#SelectRole option:selected").text();
            $("#RoleName").val(rolename);
        });
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
    $("#allModal").on("hidden.bs.modal", function(e){
        location.reload();
    })
  
    $("#allModal").on("show.bs.modal", function(e){
      $("#bodyModal").html('<i class="bx bx-loader-circle"></i> Please wait...');
      // $("#bodyModalImage").html('<i class="bx bx-loader-circle"></i> Please wait...');
    })

    $("#createmodal").on("hidden.bs.modal", function(e){
      location.reload();
  })
    $("#createmodal").on("show.bs.modal", function(e){
      $("#bodyModalCreate").html('<i class="bx bx-loader-circle"></i> Please wait...');
      // $("#bodyModalImage").html('<i class="bx bx-loader-circle"></i> Please wait...');
    })

    $("#bodyModalCreate").on("shown.bs.modal", function(e){
      e.preventDefault();
      var data = {
        'name': $('.AccountName').val(),
        'role': $('.SelectRole').val(),
        'email': $('.Email').val(),
        'account_type': $('.RoleName').val(),
        'campus': $('.Campus').val(),
    }
      $.ajax({
          type: "POST",
          url: "/save",
          cache: false,
          data: {data},
          success: function (data) {
            $("#bodyModalCreate").html(data);
          }
      });

  })
  
    $("#allModal").on("shown.bs.modal", function(e){
        var flag = $(e.relatedTarget).data('flag');
        var button = $(e.relatedTarget).data('button');
        var id = $(e.relatedTarget).data('id');
        var empid = $(e.relatedTarget).data('empid');
     
        $.ajax({
            type: "POST",
            url: "/save",
            cache: false,
            data: {flag:flag,button:button,id:id,empid:empid},
            success: function (data) {
              $("#bodyModal").html(data);
            }
        });
  
    })
  
    // $("#allModalImage").on("shown.bs.modal", function(e){
    //     var flag = $(e.relatedTarget).data('flag');
    //     var button = $(e.relatedTarget).data('button');
    //     var id = $(e.relatedTarget).data('id');
    //     var empid = $(e.relatedTarget).data('empid');
      
    //     $.ajax({
    //         type: "POST",
    //         url: "/router",
    //         cache: false,
    //         data: {flag:flag,button:button,id:id,empid:empid},
    //         success: function (data) {
    //           $("#bodyModalImage").html(data);
    //         }
    //     });
  
    // })
  
    $("#btnAccept").click(function(e){
        $(this).prop("disabled",true);
        var info = $('#allMsg');
        info.html("<div class = 'alert bg-rgba-info'>Processing...</div>");
        $.ajax({
            type: "POST",
            url: "/save",
            cache: false,
            data: $("#frmAll").serialize(),
  
            success: function(response) {
              $("#btnAccept").prop("disabled",false);
              info.html(response);
              info.slideDown();
            },
            error: function(error) {
              $("#btnAccept").prop("disabled",false);
              info.html(error);
            }
  
        });
    })
  
    $(document).on("submit", "#frmAllImage", function(e){
        e.preventDefault();
        $("#btnAcceptImage").html("Saving...");
        var formData = new FormData(this);
        $(this).prop("disabled",true);
        var info = $('#allMsg');
        info.html("");
        $.ajax({
            type: "POST",
            url: "/save",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(response) {
              $("#btnAcceptImage").html('<i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Save</span>');
              $("#btnAcceptImage").prop("disabled",false);
              info.html(response);
              info.slideDown();
            },
            error: function(error) {
              $("#btnAcceptImage").html('<i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Save</span>');
              $("#btnAcceptImage").prop("disabled",false);
              info.html(error);
              // console.log(error);
            }
  
        });
    })
  
  //   $(document).on('click', '.submitbutton', function (e) {
  //     e.preventDefault();

  //     $(this).text('Sending..');

  //     var data = {
  //         'name': $('.name').val(),
  //         'course': $('.course').val(),
  //         'email': $('.email').val(),
  //         'phone': $('.phone').val(),
  //     }

  //     $.ajaxSetup({
  //         headers: {
  //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //         }
  //     });

  //     $.ajax({
  //         type: "POST",
  //         url: "/students",
  //         data: data,
  //         dataType: "json",
  //         success: function (response) {
  //             // console.log(response);
  //             if (response.status == 400) {
  //                 $('#save_msgList').html("");
  //                 $('#save_msgList').addClass('alert alert-danger');
  //                 $.each(response.errors, function (key, err_value) {
  //                     $('#save_msgList').append('<li>' + err_value + '</li>');
  //                 });
  //                 $('.add_student').text('Save');
  //             } else {
  //                 $('#save_msgList').html("");
  //                 $('#success_message').addClass('alert alert-success');
  //                 $('#success_message').text(response.message);
  //                 $('#AddStudentModal').find('input').val('');
  //                 $('.add_student').text('Save');
  //                 $('#AddStudentModal').modal('hide');
  //                 fetchstudent();
  //             }
  //         }
  //     });

  // });

  //   $(document).on("click", '.submitbutton', function(e){
  //     e.preventDefault();
 
  //     let timerInterval
  //       Swal.fire({
  //         title: 'Saved',
  //         html: 'I will close in <b></b> milliseconds.',
  //         timer: 2000,
  //         timerProgressBar: true,
  //         didOpen: () => {
  //           Swal.showLoading()
  //           const b = Swal.getHtmlContainer().querySelector('b')
  //           timerInterval = setInterval(() => {
  //             b.textContent = Swal.getTimerLeft()
  //           }, 100)
  //         },
  //         willClose: () => {
  //           clearInterval(timerInterval)
  //         }
  //       }).then((result) => {
  //         /* Read more about handling dismissals below */
  //         if (result.dismiss === Swal.DismissReason.timer) {
  //           console.log('I was closed by the timer')
  //         }
  //       })
      
  // })
      //  Swal.fire({
      //   title: 'Are you sure?',
      //   text: "You won't be able to revert this!",
      //   type: 'warning',
      //   showCancelButton: true,
      //   confirmButtonColor: '#3085d6',  
      //   cancelButtonColor: '#d33',
      //   confirmButtonText: 'Yes',
      //   confirmButtonClass: 'btn btn-primary',
      //   cancelButtonClass: 'btn btn-danger ml-1',
      //   buttonsStyling: false,
      // })        
          
          // location.reload();
    // $(document).on("click", ".hrefdelete" , function(e) {
    //   // $('a.btn-account-delete').on('click', function(e){
    //         e.preventDefault();
   
    //        var thisval = $(this);
   
    //        const id = $(this).data('id');
    //            Swal.fire({
    //              title: 'Are you sureeee?',
    //              text: "You want delete this!",
    //              type: 'warning',
    //              showCancelButton: true,
    //              confirmButtonColor: '#3085d6',
    //              cancelButtonColor: '#d33',
    //              confirmButtonText: 'Yes, delete it!',
    //              confirmButtonClass: 'btn btn-warning',
    //              cancelButtonClass: 'btn btn-danger ml-1',
    //              buttonsStyling: false,
    //            }).then(function (result) {
    //              if (result.value) {
    //                    $.ajax({
    //                       type: "POST",
    //                       url: "/save",
    //                       cache: false,
    //                       data: {'flag':flag,'id':id,'button':button},
    //                        beforeSend:function(){
    //                        // $(form).find('span.error-text').text('');
    //                        },
    //                        success:function(data){
    //                         thisval.closest("tr").remove();
    //                          Swal.fire({
    //                            type: "success",
    //                            title: 'Deleted!',
    //                            text: data.message,
    //                            confirmButtonClass: 'btn btn-success',
    //                            })
    //                        }
               
    //                    });
    //              }
    //            });
    //    });

    $(document).on("click", '.hrefdelete', function(e){
        e.preventDefault();
        var flag = $(this).attr("flag");
        var id = $(this).attr("href");
        var button = $(this).attr("button");
        var ctr = $(this).attr("ctr");
        var par = $(this).attr("par");
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
        }).then(function (result) {
          if (result) {
            if (result.value) {
              $.ajax({
                    type: "DELETE",
                    url: "/delete",
                    cache: false,
                    data: {'flag':flag,'id':id,'button':button},
                    success: function(response) {
                      if (response == "1"){
                        $("#"+ctr).hide();
                          Swal.fire(
                          {
                            type: "success",
                            title: 'Done!',
                            text: 'Your request has been processed.',
                            confirmButtonClass: 'btn btn-success',
                          });
                      }else if (response == "2"){
                          
                          Swal.fire(
                          {
                            type: "success",
                            title: 'Done!',
                            text: 'Your request has been processed.',
                            confirmButtonClass: 'btn btn-success',
                          });
  
                          location.reload();
                      }else{
                        Swal.fire({
                          title: response,
                          text: " Please try again!",
                          type: "error",
                          confirmButtonClass: 'btn btn-primary',
                          buttonsStyling: false,
                        });
                      }                    
                    }
  
              });
            }
            
            location.reload();
          } 
         
            
        })
    })
  
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
  
   
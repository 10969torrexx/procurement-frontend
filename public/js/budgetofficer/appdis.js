var array_status = [];
var array_item_id = [];
var array_remarks = [];
var project_code;
var array_estimated_price = [];
var sub_total = $('.sub_total').val();;

$.ajaxSetup({
   headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
   });
   
   $(document).on('click', '#superbtn', function (e) {
      var ppmpDone = document.getElementById("ppmpDone");
        if (ppmpDone.style.display === 'none'){
         ppmpDone.style.display = 'block';
        }
    });
    
   $(document).on('click', '.accept_all', function (e) {
      // alert('ssss')
      var data = {
         'id': $(this).attr("data-id"),
         'remarks': "Accepted",
         // 'total': $('.overalltotal').val(),
         'value': $(this).val(),
      }

      $.ajax({

            type: "post",
            url: "/budgetofficer/view_ppmp/showPPMP/accept-reject-all",
            data:data,
            success: function (response) {
               if (response.status == 200) {
               Swal.fire({
                  title: 'Accepted!!!',
                  html: 'Project Accepted',
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
               }else if (response.status == 500) {
                  Swal.fire('All Items are already Approved', '', 'info')
               }
            }
         });
    });
   
    $(document).on('click', '.reject_all', function (e) {
      
      $("#reject_all_ppmp_modal").modal('show');
      $('.reject_val').val($(this).val());
      $('.reject_id').val($(this).attr("data-id"));
   
    });
    
    $(document).on('click', '.submitreject', function (e) {
      var data = {
         'id': $('.reject_id').val(),
         'remarks': $('.reject_remarks').val(),
         'value': $('.reject_val').val(),
      }
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      console.log(data);
      $.ajax({
            type: "post",
            url: "/budgetofficer/view_ppmp/showPPMP/accept-reject-all",
            data:data,
            success: function (response) {
               if (response.status == 200) {
               Swal.fire({
                  title: 'Rejected!!!',
                  html: 'Project Rejected',
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
               }else if (response.status == 500) {
                  console.log(response);
                  Swal.fire('All Items are already Rejected', '', 'info')
               }
            }
         });
    });

$(document).on('click', '.done', function (e) {
   // console.log('array_status: '+array_status);
   // console.log('array_item_id: '+array_item_id);
   // console.log('array_remarks: '+array_remarks);
   // console.log('project_code: '+project_code);;

   var count_disapproved = 0;
   var estimated_price = 0;
   var count = 0;
   for(var i = 0; i < array_status.length; ++i){
      count++;
      estimated_price += parseInt(array_estimated_price[i]);
       if(array_status[i] == 5)
       count_disapproved++;
   }
   if(count==0){
      Swal.fire({
         icon: 'error',
         title: 'Error',
         text: 'No Changes Made!',
       })
   }else{
      // console.log('count_disapproved: '+count_disapproved);
      var data = {
         'item_id': array_item_id,
         'project_code': project_code,
         'status': array_status,
         'remarks': array_remarks,
         'count_disapproved': count_disapproved,
         'sub_total': sub_total,
         'estimated_price': estimated_price,
      }

// $.ajaxSetup({
   // headers: {
   //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   // }
   // });

   $.ajax({
      type: "post",
      url: "/budgetofficer/view_ppmp/showPPMP/ppmp-status",
      data:data,
   //    beforeSend : function(html){
   //    },
   //    success: function (response) {
   //       if(response['status'] == 200) { 
            
   //          // console.log(i );
   //          Swal.fire({
   //             title: '',
   //             html: 'Submitted Successfully!',
   //             icon: 'success',
   //             timer: 1000,
   //             timerProgressBar: true,
   //             didOpen: () => {
   //               Swal.showLoading()
   //               const b = Swal.getHtmlContainer().querySelector('b')
   //               timerInterval = setInterval(() => {
   //                 b.textContent = Swal.getTimerLeft()
   //               }, 100)
   //             },
   //             willClose: () => {
   //               clearInterval(timerInterval)
   //             }
   //           }).then((result) => {
   //    $('#status'+ i).text("Disapproved");
   //    var element = document.getElementById("status"+i);
   //    element.style.color = "red";
   //    var btn = document.getElementById("action"+i);
   //    btn.style.display = 'none';
   //          $("#viewDisapprovedPPMPmodal").modal('hide');
   //        })
   
   //       // }
   //    }else{
   //       Swal.fire({
   //          icon: 'error',
   //          title: 'Error',
   //          text: response.message,
   //        })
   //    }
   // }
});
      $.ajax({
         type: "post",
         url: "/budgetofficer/view_ppmp/showPPMP/ppmp-timeline",
         data:data,
         beforeSend : function(html){
         },
         success: function (response) {
            if(response['status'] == 200) { 
               // alert('success');
               Swal.fire({
                  title: 'Saved!',
                  icon: 'success',
                  html: 'Changes saved successfully!',
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
                    $('#SetDeadlineModal').modal('hide');
                    location.reload();
                    console.log('I was closed by the timer')
                  }
                })
               // console.log(i );
               // $('#status'+ i).text("Approved");
               // var element = document.getElementById("status"+i);
               // element.style.color = "green";
   
               
               // var btn = document.getElementById("action"+i);
               // btn.style.display = 'none';

               // var i = $('.approve').val();
               // console.log(i);
               // // for(var i = 0; i < value; i++) {
               //    var element = document.getElementById("status"+i);
               //    // element.innerHTML= "approved";
               //    $('.status'+i).text("approved")
               //    element.style.color = "green";
                     //  $('.status'+i).style.color = "blue";
               // }
            }
         }
      });
   }
   

   array_status = [];
   array_item_id = [];
   array_remarks = [];
   project_code = 0;
});

$(document).on('click', '.approve', function (e) {
   e.preventDefault();
      // console.log($(this).attr('data-button'));
      // console.log($(this).attr("data-button"));
      var i = $(this).attr('data-index');
      if($('#status'+ i).text()=='Accepted'){
         Swal.fire({
            icon: 'error',
            title: 'Error',
            text:'Already Accepted!',
          })
      }else{
         array_status.push('4');
         array_item_id.push($(this).attr("data-toggle"));
         array_estimated_price.push($(this).attr("data-button"));
         project_code = $(this).attr("data-id");
         array_remarks.push('Approved');
         // estimated_price.push($('.data-button').val());

         $('#status'+ i).text("Accepted");
         var element = document.getElementById("status"+i);
         element.style.color = "green";

         
         var btn = document.getElementById("action"+i);
         btn.style.display = 'none';
      }


   // var data = {
   //    'item_id': $(this).attr("data-toggle"),
   //    'project_code': $(this).attr("data-id"),
   //    'status': 4,
   //    'remarks': 'Approved',
   // }
   //       console.log(data);
   // $.ajaxSetup({
   // headers: {
   //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   // }
   // });

   // $.ajax({
   //    type: "post",
   //    url: "/budgetofficer/view_ppmp/showPPMP/ppmp-approved",
   //    data:data,
   //    beforeSend : function(html){
   //    },
   //    success: function (response) {
   //       if(response['status'] == 200) { 
            
   //          // console.log(i );


   //          // var i = $('.approve').val();
   //          // console.log(i);
   //          // // for(var i = 0; i < value; i++) {
   //          //    var element = document.getElementById("status"+i);
   //          //    // element.innerHTML= "approved";
   //          //    $('.status'+i).text("approved")
   //          //    element.style.color = "green";
   //                //  $('.status'+i).style.color = "blue";
   //          // }
   //       }
   //    }
   // });
});

//show modal
$(document).on('click', '.disapprove', function (e) {
   e.preventDefault();
   var i = $(this).attr('data-index');
   if($('#status'+ i).text()=='Rejected'){
      Swal.fire({
         icon: 'error',
         title: 'Error',
         text:'Already Rejected!',
       })
   }else{
      $("#viewDisapprovedPPMPmodal").modal('show');
      $('.item_id').val($(this).attr("data-toggle"));
      $('.code').val( $(this).attr("data-id"));
      $('.index').val($(this).attr("data-index"));
      $('.estimated_price').val($(this).attr("data-button"));
      $('.remarks').val("")
   }

});

//disaaproved
$(document).on('click', '.save', function (e) {
   e.preventDefault();
   
   remarks = $('.remarks').val();
   // alert(remarks);
   if(remarks != ''){
      array_status.push('5');
      array_item_id.push($('.item_id').val());
      array_remarks.push( $('.remarks').val());
      project_code =  $('.code').val();
      array_estimated_price.push($('.estimated_price').val());

      // var data = {
      //    'item_id': array_item_id,
      //    'project_code': project_code,
      //    'status': array_status,
      //    'remarks': array_remarks,
      //    // 'count_disapproved': count_disapproved,
      // }

      // $.ajax({
      //       type: "post",
      //       url: "/budgetofficer/view_ppmp/showPPMP/ppmp-status",
      //       data:data,
      //       beforeSend : function(html){
      //       },
      //       success: function (response) {
      //          if(response['status'] == 200) { 
      //             Swal.fire({
      //                title: '',
      //                html: 'Saved!',
      //                icon: 'success',
      //                timer: 1000,
      //                timerProgressBar: true,
      //                didOpen: () => {
      //                  Swal.showLoading()
      //                  const b = Swal.getHtmlContainer().querySelector('b')
      //                  timerInterval = setInterval(() => {
      //                    b.textContent = Swal.getTimerLeft()
      //                  }, 100)
      //                },
      //                willClose: () => {
      //                  clearInterval(timerInterval)
      //                }
      //              }).then((result) => {
                     
                     $("#viewDisapprovedPPMPmodal").modal('hide');
      //              })
                  var i = $('.index').val();
                  $('#status'+ i).text("Rejected");
                  var element = document.getElementById("status"+i);
                  element.style.color = "red";
                  var btn = document.getElementById("action"+i);
                  btn.style.display = 'none';
      //          }else{
      //                   Swal.fire({
      //                      icon: 'error',
      //                      title: 'Error',
      //                      text: response.message,
      //                    })
      //                }
      //       }
      //    });
                  
   }else{
      Swal.fire({
         icon: 'error',
         title: 'Error',
         text: 'Please enter remarks!',
       })
   }
  
      // console.log($(this).attr('data-index'));
   // var data = {
   //    'item_id': $('.item_id').val(),
   //    'project_code': $('.code').val(),
   //    'status': 5,
   //    'estimated_price': $('.estimated_price').val(),
   //    'remarks': $('.remarks').val(),
   // }
         // console.log(data);
   // $.ajaxSetup({
   // headers: {
   //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   // }
   // });

   // $.ajax({
   //    type: "post",
   //    url: "/budgetofficer/view_ppmp/showPPMP/ppmp-disapproved",
   //    data:data,
   //    beforeSend : function(html){
   //    },
   //    success: function (response) {
   //       if(response['status'] == 200) { 
            
   //          // console.log(i );
   //          Swal.fire({
   //             title: '',
   //             html: 'Submitted Successfully!',
   //             icon: 'success',
   //             timer: 1000,
   //             timerProgressBar: true,
   //             didOpen: () => {
   //               Swal.showLoading()
   //               const b = Swal.getHtmlContainer().querySelector('b')
   //               timerInterval = setInterval(() => {
   //                 b.textContent = Swal.getTimerLeft()
   //               }, 100)
   //             },
   //             willClose: () => {
   //               clearInterval(timerInterval)
   //             }
   //           }).then((result) => {
               
   //             $("#viewDisapprovedPPMPmodal").modal('hide');
   //           })
            
   //          // }
   //       }else{
   //          Swal.fire({
   //             icon: 'error',
   //             title: 'Error',
   //             text: response.message,
   //           })
   //       }
   //    }
   // });
});

$(document).on('click', '.edit', function (e) {
   e.preventDefault();
   
   var i = $(this).attr('data-index');
   console.log(i);
   var btn = document.getElementById("action"+i);
   if (btn.style.display === 'none'){
      btn.style.display = 'block';
   }else{
      btn.style.display = 'none';
   }
});
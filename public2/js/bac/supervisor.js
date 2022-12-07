
// $(document).ready(function() {
//     var index = $('.index').val();
//     // var status = $('.index').attr('data-toggle');
//     console.log(status);
//     for (var i = 0; i < index; i++)
//     {
//         var status = $('#status'+ i).attr('data-toggle');

//         if(status == 1 || status == 6)
//         {
//             var btn = document.getElementById("action"+i);
//             btn.style.display = 'block';
//         }
//         else{
//             var btn = document.getElementById("action"+i);
//             btn.style.display = 'none';
//         }
//     }
// });
var item_id = [];
var project_code = [];
var stat = [];
var remarks = [];
$(document).on('click', '.approve', function (e) {
   e.preventDefault();
      // console.log($(this).attr('data-index'));

      item_id.push($(this).attr("data-toggle"));   
      project_code.push($(this).attr("data-id"));  
      // var stat = 2;
      stat.push($(this).attr("value"));  
      
      var i = $(this).attr("data-index");
      $('#status'+ i).text("Accepted");
         var element = document.getElementById("status"+i);
         element.style.color = "green";
         var btn = document.getElementById("action"+i);
         btn.style.display = 'none';
   // var data = {
   //    'item_id': $(this).attr("data-toggle"),
   //    'project_code': $(this).attr("data-id"),
   //    'status': 2,
   // }
   //          var i = $(this).attr('data-index');
         // console.log(item_id);
   // $.ajaxSetup({
   // headers: {
   //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   // }
   // });

   // $.ajax({
   //    type: "post",
   //    url: "supervisor-ppmp-approved",
   //    data:data,
   //    beforeSend : function(html){
   //    },
   //    success: function (response) {
   //       if(response['status'] == 200) { 
            
   //          // console.log(i );
   //          $('#status'+ i).text("Accepted");
   //          var element = document.getElementById("status"+i);
   //          element.style.color = "green";

            
   //          var btn = document.getElementById("action"+i);
   //          btn.style.display = 'none';

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
   console.log($(this).attr("value"));
   $("#viewDisapprovedPPMPmodal").modal('show');
   $('.item_id').val($(this).attr("data-toggle"));
   $('.code').val( $(this).attr("data-id"));
   $('.index').val($(this).attr("data-index"));
   $('.status').val($(this).attr("value"));
   $('.remarks').val("")
});

//disaaproved
$(document).on('click', '.save', function (e) {
   e.preventDefault();

   item_id.push($('.item_id').val());   
   project_code.push($('.code').val());  
   remarks.push($('.remarks').val()); 
   // var stat = 3;
   stat.push($('.status').val());  
   console.log(stat);
   $("#viewDisapprovedPPMPmodal").modal('hide');
   //    console.log($(this).attr('data-index'));
   // var data = {
   //    'item_id': $('.item_id').val(),
   //    'project_code': $('.code').val(),
   //    'status': 3,
   //    'remarks': $('.remarks').val(),
   // }
            var i = $('.index').val();
   //       console.log(data);
   // $.ajaxSetup({
   // headers: {
   //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   // }
   // });

   // $.ajax({
   //    type: "post",
   //    url: "supervisor-ppmp-disapproved",
   //    data:data,
   //    beforeSend : function(html){
   //    },
   //    success: function (response) {
   //       console.log(response);
   //       if(response['status'] == 200) { 
            
   //          // console.log(i );
   //          Swal.fire({
   //             title: '',
   //             html: 'Submitted Successfully!',
   //             icon: 'success',
   //             timer: 2000,
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
               $('#status'+ i).text("Need Revision");
               var element = document.getElementById("status"+i);
               element.style.color = "red";
               var btn = document.getElementById("action"+i);
               btn.style.display = 'none';
               // $("#viewDisapprovedPPMPmodal").modal('hide');
   //           })
            
   //          // }
   //       }else{
   //          Swal.fire({
   //             icon: 'error',
   //             title: 'Oops...',
   //             text: 'Check Inputs',
   //           })
   //       }
   //    }
   // });
});
$(document).on('click', '.ppmpDone', function (e) {

   var count = 0;
   for(var i =0 ; i < stat.length; i++){

      console.log(stat[i])
      if(stat[i] == 2){

         var data = {
               'item_id': item_id[i],
               'project_code': project_code[i],
               'status': stat[i],
               'remarks':remarks[i],
            }
            
      console.log(data);

         $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
         
            $.ajax({
               type: "post",
               url: "supervisor-ppmp-approved",
               data:data,
               beforeSend : function(html){
               },
               success: function (response) {
                  
                     // console.log(response);
                  // if(response['status'] == 200) { 
                     
                     // console.log(i );
                     // $('#status'+ i).text("Accepted");
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
                  // }
               }
            });
      }else if(stat[i] == 3){

         count++;
         var data = {
            'item_id': item_id[i],
            'project_code': project_code[i],
            'status': stat[i],
            'remarks':remarks[i],
         }

         console.log(data);
         $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
         
            $.ajax({
               method: "post",
               //url: "supervisor-ppmp-disapproved",
			   url: "/bac/supervisor/supervisor-ppmp-disapproved",
               data:data,
               beforeSend : function(html){
               },
               success: function (response) {
                  // console.log(response);
                  // if(response['status'] == 200) { 
                     
                  //    // console.log(i );
                  //    Swal.fire({
                  //       title: '',
                  //       html: 'Submitted Successfully!',
                  //       icon: 'success',
                  //       timer: 2000,
                  //       timerProgressBar: true,
                  //       didOpen: () => {
                  //         Swal.showLoading()
                  //         const b = Swal.getHtmlContainer().querySelector('b')
                  //         timerInterval = setInterval(() => {
                  //           b.textContent = Swal.getTimerLeft()
                  //         }, 100)
                  //       },
                  //       willClose: () => {
                  //         clearInterval(timerInterval)
                  //       }
                  //     }).then((result) => {
                  //       $('#status'+ i).text("Need Revision");
                  //       var element = document.getElementById("status"+i);
                  //       element.style.color = "red";
                  //       var btn = document.getElementById("action"+i);
                  //       btn.style.display = 'none';
                  //       $("#viewDisapprovedPPMPmodal").modal('hide');
                  //     })
                     
                  //    // }
                  // }else{
                  //    Swal.fire({
                  //       icon: 'error',
                  //       title: 'Oops...',
                  //       text: 'Check Inputs',
                  //     })
                  // }
               }
            });
      }
      console.log(count);

      if(count == 0 ){
         var data2 = {
            'project_id': $(this).attr("data-id"),
            'status' : 2
         }
   
         $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
         
            $.ajax({
               type: "post",
               url: "supervisor-ppmp-done",
               data:data2,
               beforeSend : function(html){
               },
               success: function (response) {
                  console.log(response);
                  if(response['status'] == 200) { 
                     
                     // console.log(i );
                     Swal.fire({
                        title: '',
                        html: 'Submitted Successfully!',
                        icon: 'success',
                        timer: 2000,
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
                        // $('#status'+ i).text("Need Revision");
                        // var element = document.getElementById("status"+i);
                        // element.style.color = "red";
                        // var btn = document.getElementById("action"+i);
                        // btn.style.display = 'none';
                        // $("#viewDisapprovedPPMPmodal").modal('hide');
                      })
                     
                     // }
                  }else{
                     Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Check Inputs',
                      })
                  }
               }
            });
      }else{
         var data2 = {
            'project_id': $(this).attr("data-id"),
            'status' : 3
         }
   
         $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
         
            $.ajax({
               type: "post",
               url: "supervisor-ppmp-done",
               data:data2,
               beforeSend : function(html){
               },
               success: function (response) {
                  console.log(response);
                  if(response['status'] == 200) { 
                     
                     // console.log(i );
                     Swal.fire({
                        title: '',
                        html: 'Submitted Successfully!',
                        icon: 'success',
                        timer: 2000,
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
                        // $('#status'+ i).text("Need Revision");
                        // var element = document.getElementById("status"+i);
                        // element.style.color = "red";
                        // var btn = document.getElementById("action"+i);
                        // btn.style.display = 'none';
                        // $("#viewDisapprovedPPMPmodal").modal('hide');
                      })
                     
                     // }
                  }else{
                     Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Check Inputs',
                      })
                  }
               }
            });
      }
   }
});

$(document).on('click', '.edit', function (e) {
   e.preventDefault();
   
   var i = $(this).attr('data-index');
   console.log(i);
   var btn = document.getElementById("action"+i);
   if (btn.style.display === 'none'){
      btn.style.display = 'block';
   }else{
      btn.style.display = 'none'
   }
});

$(document).ready(function() {
    
});
var array_status = [];
var array_item_id = [];
var array_remarks = [];
var project_code;

$(document).on('click', '.done', function (e) {
   console.log('array_status: '+array_status);
   console.log('array_item_id: '+array_item_id);
   console.log('array_remarks: '+array_remarks);
   console.log('project_code: '+project_code);

   var count_disapproved = 0;
   for(var i = 0; i < array_status.length; ++i){
       if(array_status[i] == 5)
       count_disapproved++;
   }
   // var disapproved = array_status.count(5);
   console.log('count_disapproved: '+count_disapproved);
   if(count_disapproved == 0){
      // alert('success');
      var data = {
         'item_id': array_item_id,
         'project_code': project_code,
         'status': array_status,
      }

      $.ajaxSetup({
         headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });

      $.ajax({
         type: "post",
         url: "/budgetofficer/view_ppmp/showPPMP/ppmp-approved",
         data: data,
         beforeSend : function(html){
         },
         success: function (response) {
            if(response['status'] == 200) { 
               $('#status'+ i).text("Approved");
               var element = document.getElementById("status"+i);
               element.style.color = "green";
    
               var btn = document.getElementById("action"+i);
               btn.style.display = 'none';
            }
         }
      });

   }else{
      var data = {
         'item_id': array_item_id,
         'project_code': project_code,
         'status': 4,
         'remarks': array_remarks,
      }

      $.ajaxSetup({
         headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });
      $.ajax({
      type: "post",
      url: "/budgetofficer/view_ppmp/showPPMP/ppmp-disapproved",
      data:data,
      beforeSend : function(html){
      },
      success: function (response) {
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
               $('#status'+ i).text("Disapproved");
               var element = document.getElementById("status"+i);
               element.style.color = "red";
               var btn = document.getElementById("action"+i);
               btn.style.display = 'none';
               $("#viewDisapprovedPPMPmodal").modal('hide');
             })
            
            // }
         }
      }
   });

   }
      var data = {
      'item_id': $(this).attr("data-toggle"),
      'project_code': $(this).attr("data-id"),
      'status': 4,
      'remarks': 4,
   }
   array_status = [];
   array_item_id = [];
   project_code = 0;
   
});
   
$(document).on('click', '.approve', function (e) {
   e.preventDefault();

   array_status.push('4');
   array_item_id.push($(this).attr("data-toggle"));
   project_code = $(this).attr("data-id");
   array_remarks.push('Approved');

      // console.log($(this).attr('data-index'));
   // var data = {
   //    'item_id': $(this).attr("data-toggle"),
   //    'project_code': $(this).attr("data-id"),
   //    'status': 4,
   // }
   //          var i = $(this).attr('data-index');
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
   //          $('#status'+ i).text("Approved");
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

   $("#viewDisapprovedPPMPmodal").modal('show');
   $('.item_id').val($(this).attr("data-toggle"));
   $('.code').val( $(this).attr("data-id"));
   $('.index').val($(this).attr("data-index"));
   $('.remarks').val("")
});

//disaaproved
$(document).on('click', '.save', function (e) {
   e.preventDefault();
   
   array_status.push('5');
   array_item_id.push($('.item_id').val());
   array_remarks.push( $('.remarks').val());
   project_code =  $('.code').val();

   // $("#viewDisapprovedPPMPmodal").modal('show');
   //    console.log($(this).attr('data-index'));
   // var data = {
   //    'item_id': $('.item_id').val(),
   //    'project_code': $('.code').val(),
   //    'status': 5,
   //    'remarks': $('.remarks').val(),
   // }
   //          var i = $('.index').val();
   //       console.log(data);
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
   //             $('#status'+ i).text("Disapproved");
   //             var element = document.getElementById("status"+i);
   //             element.style.color = "red";
   //             var btn = document.getElementById("action"+i);
   //             btn.style.display = 'none';
   //             $("#viewDisapprovedPPMPmodal").modal('hide');
   //           })
            
   //          // }
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
var item_id = [];
var project_code = [];
var stat = [];
var balance = [];
var remarks = [];
$(document).on('click', '.approve', function (e) {
   e.preventDefault();
   // console.log($('.statusval').val());
   $check = 0 ;
   if($.isEmptyObject(item_id)){
      item_id.push($(this).attr("data-toggle"));   
      project_code.push($(this).attr("data-id"));  
      // var remark = "";
      balance.push($(this).attr("data-button")); 
      stat.push($(this).attr("value"));  
      remarks.push("Accepted");  
      
      var i = $(this).attr("data-index");
      $('#status'+ i).text("Accepted");
      var element = document.getElementById("status"+i);
      element.style.color = "green";
      var btn = document.getElementById("action"+i);
      btn.style.display = 'none';
   }else{
      for(var a = 0 ; a < item_id.length ; a++){
         if(item_id[a] == $(this).attr("data-toggle"))
         {
            if(stat[a] == $(this).attr("value")){
               $check++;
            }else{
               stat[a] = $(this).attr("value");
               remarks[a] = "";
               balance[a] = 0;
            }
         }
      }
      if($check > 0){
         Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: 'Already Accepted',
         })
      }else{
         item_id.push($(this).attr("data-toggle"));   
         project_code.push($(this).attr("data-id"));  
         var bal = 0;
         balance.push(bal); 
         stat.push($(this).attr("value"));  
         remarks.push("Accepted");
         
         var i = $(this).attr("data-index");
         $('#status'+ i).text("Accepted");
         var element = document.getElementById("status"+i);
         element.style.color = "green";
         var btn = document.getElementById("action"+i);
         btn.style.display = 'none';
      }
   }
});

//show modal
$(document).on('click', '.disapprove', function (e) {
   e.preventDefault();
   // console.log($(this).attr("data-button"));
   $("#viewDisapprovedPPMPmodal").modal('show');
   $('.item_id').val($(this).attr("data-toggle"));
   $('.balance').val($(this).attr("data-button"));
   // $('.balance').val($('.estimatedprice').val());
   $('.code').val( $(this).attr("data-id"));
   $('.index').val($(this).attr("data-index"));
   $('.status').val($(this).attr("value"));
   $('.remarks').val("")
});

//disaaproved
$(document).on('click', '.save', function (e) {
   e.preventDefault();
   // $idd = "";
   // $codee = "";
   // $remarkss = "";
   // $balancee = "";
   // $status = "";
   $check = 0;
   console.log($('.item_id').val());

   if($.isEmptyObject(item_id)){
      item_id.push($('.item_id').val());   
      project_code.push($('.code').val());  
      remarks.push($('.remarks').val()); 
      balance.push($('.balance').val()); 
      // var stat = 3;
      stat.push($('.status').val()); 
      $("#viewDisapprovedPPMPmodal").modal('hide');

      var i = $('.index').val();
      $('#status'+ i).text("Need Revision");
      var element = document.getElementById("status"+i);
      element.style.color = "red";
      var btn = document.getElementById("action"+i);
      btn.style.display = 'none';
   }else{
      for(var a = 0 ; a < item_id.length ; a++){
         if(item_id[a] == $('.item_id').val())
         {
            if(stat[a] == $('.status').val()){
               $check++;
            }else{
               stat[a] = $('.status').val();
               remarks[a] = $('.remarks').val();
            }
         }
      }
      if($check > 0){
         Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: 'Already Rejected',
         })
      }else{
         item_id.push($('.item_id').val());   
         project_code.push($('.code').val());  
         remarks.push($('.remarks').val()); 
         balance.push($('.balance').val()); 
         // var stat = 3;
         stat.push($('.status').val()); 
         $("#viewDisapprovedPPMPmodal").modal('hide');

         var i = $('.index').val();
         $('#status'+ i).text("Need Revision");
         var element = document.getElementById("status"+i);
         element.style.color = "red";
         var btn = document.getElementById("action"+i);
         btn.style.display = 'none';
      }
   }
});


$(document).on('click', '.ppmpDone', function (e) {
   
   console.log(stat.length);
   if($.isEmptyObject(item_id)){
      Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: 'No Changes Made',
       })
   }else{
      
      $totalBalance = 0
      for(var i = 0 ; i < stat.length; i++){
         
         // $totalBalance += intval(balance[i]);
         console.log(stat[i])
         if(stat[i] == 2){
            // count++;
            var data = {
                  'item_id': item_id[i],
                  'balance': balance[i],
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
                  }
               });
         }else if(stat[i] == 3){
            // count++;
            var data = {
               'item_id': item_id[i],
               'balance': balance[i],
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
                  url: "supervisor-ppmp-disapproved",
                  data:data,
                  beforeSend : function(html){
                  },
                  success: function (response) {
                  }
               });
         }
         // console.log(count);
      }
   //   $yuyu =  parseInt(stat.length)
   //   console.log(count);
      // if(count == $yuyu ){
      //    var data2 = {
      //       'project_id': $(this).attr("data-id"),
      //       'status' : 2
      //    }
   
      //    $.ajaxSetup({
      //       headers: {
      //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //       }
      //       });
         
      //       $.ajax({
      //          type: "post",
      //          url: "supervisor-ppmp-done",
      //          data:data2,
      //          beforeSend : function(html){
      //          },
      //          success: function (response) {
      //             console.log(response);
      //             if(response['status'] == 200) { 
                     
      //                // console.log(i );
      //                Swal.fire({
      //                   title: '',
      //                   html: 'Submitted Successfully!',
      //                   icon: 'success',
      //                   timer: 2000,
      //                   timerProgressBar: true,
      //                   didOpen: () => {
      //                     Swal.showLoading()
      //                     const b = Swal.getHtmlContainer().querySelector('b')
      //                     timerInterval = setInterval(() => {
      //                       b.textContent = Swal.getTimerLeft()
      //                     }, 100)
      //                   },
      //                   willClose: () => {
      //                     clearInterval(timerInterval)
      //                   }
      //                 }).then((result) => {
      //                   if (result.dismiss === Swal.DismissReason.timer) {
      //                      location.reload();
      //                      console.log('I was closed by the timer')
      //                    }
      //                 })
                     
      //                // }
      //             }else{
      //                Swal.fire({
      //                   icon: 'error',
      //                   title: 'Oops...',
      //                   text: 'Check Inputs',
      //                 })
      //             }
      //          }
      //       });
      // }else{
         for(var i =0 ; i < balance.length; i++){
            
            $totalBalance += parseInt(balance[i]);
         }$count = 0;
         for(var i =0 ; i < stat.length; i++){
           if(stat[i] == 3){
           $count++;
           }
         }

         if($count > 0)
         {
            $titlestatus = 3
         }else
         {
            $titlestatus = 2
         }

         var data2 = {
            'balance': $totalBalance,
            'project_id': $(this).attr("data-id"),
            'status' : $titlestatus
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
                           //  b.textContent = Swal.getTimerLeft()
                          }, 100)
                        },
                        willClose: () => {
                          clearInterval(timerInterval)
                        }
                  // location.reload();
                      }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                           location.reload();
                           console.log('I was closed by the timer')
                         }
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
      // }
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
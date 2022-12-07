$(function(){

    // fetchEmployee()
     function fetchEmployee(){
           var request =  $.ajax({
             url: 'superadmin',
             type: 'GET',
             dataType: 'json',
             beforeSend: function () {
            //  xhr.setRequestHeader('Authorization', 'Bearer '+token+'');
             },
             success: function(response){
               //  console.log('response:',response);
 
                 // if(response.status === 200){
                 //           //     $bdate = $employee['DateOfBirth'];
                 //                     //     $age = 0;
                 //                     //     if (!empty($bdate)){
                 //                     //         $bdate = App\Http\Controllers\AESCipher::decrypt($bdate);
                 //                     //         $from = new DateTime($bdate);
                 //                     //         $to   = new DateTime('today');
                 //                     //         $age = $from->diff($to)->y;
                 //                     //     }
                   
                 //         $('tbody').html('');
                 //         $.each(response.data , function (key, item){
                 //             $('tbody').append('<tr>\
                 //             <td>'+item.FirstName+' '+item.LastName+'</td>\
                 //             <td>'+item.Sex+'</td>\
                 //             <td>'+item.DateOfBirth+'</td>\
                 //             <td>'+item.Cellphone+'</td>\
                 //             <td>'+item.CivilStatus+'</td>\
                 //             </tr>');
                 //         }); 
 
                 // }
                 //else{
                 //     console.log('NO DATA IN EMPLOYEE');
                 // }
             //     $('tbody').html('');
             //    $.each(response.data , function (key, item){
 
             //     $('tbody').append('<tr>\
             //     <td><div class="dropdown">\
             //             <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer icon-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>\
             //             <div class="dropdown-menu dropdown-menu-right">\
             //                 <a class="dropdown-item" href="/employee/edit/'+btoa(item.id)+'"><i class="bx bx-edit-alt mr-1"></i> edit</a>\
             //                 <a class="dropdown-item btn-delete-employee" href="#" data-id="'+item.id+'"><i class="bx bx-trash mr-1"></i> delete</a>\
             //             </div>\
             //         </div></td>\
             //     <td>'+item.FirstName+'</td>\
             //     <td>'+item.MiddleName+'</td>\
             //     <td>'+item.LastName+'</td>\
             //     <td>'+item.Sex+'</td>\
             //     <td>'+item.EmailAddress+'</td>\
             //     <td>'+item.Telephone+'</td>\
             //     <td>'+item.Cellphone+'</td>\
             //     <td>'+item.EmploymentStatus+'</td>\
             //     <td>'+item.AgencyNumber+'</td>\
             //     </tr>');
             //    });  
             // } ,
             // error: function (err) { 
             //         if(err.status === 401){
             //             alert('Unathorized Access!')
             //             window.location.href = '/auth/admin/login'
             //         }
                 
                 },
         });
 
         // console.log(request.status);
         // request.error(function(httpObj, textStatus) {       
         // if(httpObj.status==200)
         //    alert('success')
         // else
         //     alert('fail')
         //  });
 
         }
 
     $('#add-employee-btn').on('click', function(e){
         e.preventDefault();
         $('#allEmployeeModal').modal('show');
        
     });


     $('.btnrefresh').on('click', function(){
         location.reload();
     });
 
     
 
     
     $('#btnemployeeSubmit').on('click', function(e){
         e.preventDefault();
         $.ajax({
             url: '/employee/create',
             method: 'post',
             data: $('#add-employee-form').serialize(),
             dataType   :'json',
             beforeSend:function(){
             // $(form).find('span.error-text').text('');
             $('#add-employee-form').find('span').text('');
             $('#btnemployeeSubmit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...');
             },
             success:function(data){
               
                 if(data.status === 200){
                    $('#allEmployeeModal').modal('hide');
                    toastr.success(data.message+"<br>Page will refresh after 3 seconds.");
                    window.setTimeout(function(){location.reload()},3000);                     
                 }else if(data.status === 400){
                    
                     $.each(data.message, function(prefix,val){
                         $('#add-employee-form').find('span.'+prefix+'_error').text(val[0]);
                     });
                 }
                 else if(data.status === 401 || data.status === 500 || data.status === 404){
                     toastr.error(data.message);
                 }
                 $('#btnemployeeSubmit').html('<i class="bx bx-save"></i> Save');
             }
 
         });
     });
 
     $(document).on("click", ".btn-employee-delete" , function() {
     //$('.btn-employee-delete').on('click', function(e){
        // e.preventDefault();
 
         var thisval = $(this);
 
         const id = $(this).data('id');
             Swal.fire({
               title: 'Are you sure?',
               text: "You want to delete this!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, delete it!',
               confirmButtonClass: 'btn btn-warning',
               cancelButtonClass: 'btn btn-danger ml-1',
               buttonsStyling: false,
             }).then(function (result) {
               if (result.value) {
                     $.ajax({
                         url: '/employee/delete',
                         method: 'post',
                         data: {id : id},
                         beforeSend:function(){
                         // $(form).find('span.error-text').text('');
                         },
                         success:function(data){
                          thisval.closest("tr").remove();
                           Swal.fire({
                             type: "success",
                             title: 'Deleted!',
                             text: data.message,
                             confirmButtonClass: 'btn btn-success',
                             })
                         }
             
                     });
               }
             });
     });
 
     $('#btn-update-employee-personal-profile').on('click', function(e){
         e.preventDefault();
         // alert ("FF");
         $.ajax({
             url: '/employee/update',
             method: 'post',
             data: $('#form-update-employee-personal-profile').serialize(),
             // processData: false,
             // dataType: false,
             // contentType: false,
             beforeSend:function(){
                 $('#btn-update-employee-personal-profile').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Saving...');
                 $('#form-update-employee-personal-profile').find('span').text('');
             },
             success:function(result){
            
                if(result.status === 200){
                    toastr.success(result.message);
                }else if (result.status == 400){
                    $.each(result.message, function(prefix,val){
                        $('#form-update-employee-personal-profile').find('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    toastr.error(result.message);
                }

                $('#btn-update-employee-personal-profile').html('Saving Changes');
             }
 
         });
 
     });

 
 });
 
 
$(document).on('click', '.ics_closed', function (e) {
  location.reload();
  });
  
  $(document).on('click', '.parSave', function (e) {
    // console.log("ghsdcg");
    // $("#allmodal").modal('show');
    var data = {
      'EmployeeId': $(".EmployeeName").val(),
      'Type': $('.Type').val(),
      'FundCluster': $('.FundCluster').val(),
      'ItemName': $('.ItemName').val(),
      'Description': $('.Description').val(),
      'Quantity': $('.Quantity').val(),
      'Unit': $('.Unit').val(),
      'UnitPrice': $('.UnitPrice').val(),
      'Issuedby': $('.Issuedby').val(),
      'DateIssued': $('.DateIssued').val(),
      'DateAcquired': $('.DateAcquired').val(),
      'PONumber': $('.PONumber').val(),
      'ICSNumber': $('.ICSNumber').val(),
      'DateReceived': $('.DateReceived').val(),
      'Supplier': $('.Supplier').val(),
      'Estimatedusefullife': $('.Estimatedusefullife').val(),
      // 'id': $(this).val(),
    }
    console.log(data);
    if(data.EmployeeName == "Choose..." || data.Type == "" || data.FundCluster == "Choose..." || data.ItemName == "" || 
       data.Description == "" || data.Quantity == "" || data.Unit == "Choose..." || data.UnitPrice == "" || 
       data.Issuedby == "Choose..." || data.DateIssued == "" || data.DateAcquired == "" || data.DateAcquired == "" || 
       data.PONumber == "" || data.ICSNumber == "" || data.DateReceived == "" || data.Supplier == "" /* || data.Estimatedusefullife == "" */   ){
      Swal.fire('Complete the needed data', '', 'info')
    }else{
      console.log(data);
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
  
      $.ajax({
        type: "post",
        url: "assign-ics",
        data:data,
        success: function (response) {
          console.log(response);
          if(response['status'] == 200) {
            Swal.fire({
              title: 'Added!!!',
              html: 'Added Successfully!',
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
                  // location.reload();
                  console.log('I was closed by the timer')
                }
              })
              $(".EmployeeName").val("Choose...");
              $('.FundCluster').val("Choose...");
              $('.ItemName').val("");
              $('.Description').val("");
              $('.Quantity').val("");
              $('.Unit').val("Choose...");
              $('.UnitPrice').val("");
              $('.Issuedby').val("");
              $('.DateIssued').val("");
              $('.DateAcquired').val("");
              $('.ICSNumber').val("");
              $('.PONumber').val("");
              $('.DateReceived').val("");
              $('.Supplier').val("");
              $('.Estimatedusefullife').val("");
          }else if(response['status'] == 400){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Check Inputs',
            })
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'PO Number Already Exist!!',
            })
          }
        }
      });
    }
    
  });

$(document).on('click', '.finalize', function (e) {
  var id = $(this).attr('data-id');
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "finalize-ics",
      data:{'id':id},
      success: function (response) {
        console.log(response);
        if(response['status'] == 200) {
          Swal.fire({
            title: 'Finalizing!!!',
            html: 'Finalizing',
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
        }else if(response['status'] == 400){
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Check Inputs',
          })
        }
      }
    });
});

$(document).on('click', '.deletepar', function (e) {
  var id = $(this).attr('data-id');
  Swal.fire({
    title: 'Are you sure you want to delete?',
    showDenyButton: true,
    confirmButtonText: 'Yes',
    icon: 'question',
    denyButtonText: 'No',
    customClass: {
      actions: 'my-actions',
      confirmButton: 'order-2',
      denyButton: 'order-3',
    }
  }).then((result) => {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      if (result.isConfirmed) {
        $.ajax({
              type: "post",
              url: "delete-ics",
              data:{'id' :id},
              success: function (response) {
                // alert(response.status);
                if (response.status == 200) {
                  Swal.fire({
                    title: 'Deleted!!!',
                    html: 'Deleted Successfully!',
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

$(document).on('click', '.editpar', function (e) {
  var id = $(this).attr('data-id');
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "edit-ics",
      data:{'id':id},
      success: function (response) {
        console.log(response);
       $("#selectedName").text(response[0]['EmployeeName']);
       $("#selectedName").val(response[0]['EmployeeID']);
       $('.EditType').val(response[0]['propertytype']);
       if(response[0]['FundCluster'] =="RAF"){
        $('#selectedFund').val(response[0]['FundCluster']);
        $('#selectedFund').text("Regular Agency Fund");
       }else if(response[0]['FundCluster'] =="IGF"){
        $('#selectedFund').val(response[0]['FundCluster']);
        $('#selectedFund').text("Internally Generated Funds");
       }else{
        $('#selectedFund').val(response[0]['FundCluster']);
        $('#selectedFund').text("Business Related Funds");
       }
       $('.EditItemName').val(response[0]['ItemName']);
       $('.EditDescription').val(response[0]['Description']);
       $('.EditQuantity').val(response[0]['Quantity']);
      //  $('.EditUnit').val(response[0]['Unit']);
       $("#EditUnitSelected").text(response[0]['unit']);
       $("#EditUnitSelected").val(response[0]['umID']);
       $('.EditUnitPrice').val(response[0]['UnitPrice']);
       $('#selectedIssuedBy').val(response[0]['IssuedBy']);
       $('#selectedIssuedBy').text(response[0]['IssuedbyName']);
       $('.EditDateIssued').val(response[0]['DateIssued']);
       $('.EditDateAcquired').val(response[0]['DateAcquired']);
       $('.EditPONumber').val(response[0]['PONumber']);
       $('.EditICSNo').val(response[0]['PARNo']);
      //  $('.EditICSNo').val(response[0]['PONumber']);
       $('.EditDateReceived').val(response[0]['DateReceived']);
       $('#selectedSupplier').val(response[0]['StoreName']);
       $('#selectedSupplier').text(response[0]['Storename']);
       $('.EditEstimatedusefullife').val(response[0]['Estimatedusefullife']);
       $('.submitEdit').val(response[0]['id']);
      }
    });
});

$(document).on('click', '.submitEdit', function (e) {
  // console.log("ghsdcg");
  // $("#allmodal").modal('show');
  var data = {
    'EmployeeId': $('.EditEmployeeName').val(),
    'Type': $('.EditType').val(),
    'FundCluster': $('.EditFundCluster').val(),
    'ItemName': $('.EditItemName').val(),
    'Description': $('.EditDescription').val(),
    'Quantity': $('.EditQuantity').val(),
    'Unit': $('.EditUnit').val(),
    'UnitPrice': $('.EditUnitPrice').val(),
    'Issuedby': $('.EditIssuedby').val(),
    'DateIssued': $('.EditDateIssued').val(),
    'DateAcquired': $('.EditDateAcquired').val(),
    'PONumber': $('.EditPONumber').val(),
    'ICSNumber': $('.EditICSNo').val(),
    'DateReceived': $('.EditDateReceived').val(),
    'Supplier': $('.EditSupplier').val(),
    'Estimatedusefullife': $('.EditEstimatedusefullife').val(),
    'id': $(this).val(),
  }
  console.log(data);
  if(data.EmployeeName == "" || data.Type == "" || data.FundCluster == "" || data.ItemName == "" || 
     data.Description == "" || data.Quantity == "" || data.Unit == "" || data.UnitPrice == "" || 
     data.Issuedby == "" || data.DateIssued == "" || data.DateAcquired == "" || data.DateAcquired == "" || 
     data.PONumber == "" || data.ICSNumber == "" || data.DateReceived == "" || data.Supplier == "" /* || data.Estimatedusefullife == "" */   ){
    Swal.fire('Complete the needed data', '', 'info')
  }else{
    console.log(data);
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "submitEdit-ics",
      data:data,
      success: function (response) {
        console.log(response);
        if(response['status'] == 200) {
          Swal.fire({
            title: 'Edited!!!',
            html: 'Edit Successfully!',
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
        }else if(response['status'] == 400){
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Check Inputs',
          })
        }
      }
    });
  }
  
});

$(document).on('click', '.additempar', function (e) {
  var id = $(this).attr('data-id');
  var par = $(this).attr('data-button');
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "additem-ics",
      data:{'id':id},
      success: function (response) {
        console.log(response);
        $('.addpropertytype').val(response[0]['propertytype']);
        $('.addType').text(response[0]['propertytype']);
       $('.addPAR').val(response[0]['PARNo']);
      //  $('.addPARNo').text(par);
       $('.addPARNo').text(response[0]['PARNo']);
       $('.addPONo').text(response[0]['PONumber']);
       $('.addPONumber').val(response[0]['PONumber']);
       $('.addIssuedBy').val(response[0]['IssuedBy']);
       $('.addDateIssued').val(response[0]['DateIssued']);
       $('.addDateAcquired').val(response[0]['DateAcquired']);
       $('.addPONumber').val(response[0]['PONumber']);
       $('.addDateReceived').val(response[0]['DateReceived']);
       $('.addSupplier').val(response[0]['StoreName']);
      }
    });
});

$(document).on('click', '.addPar', function (e) {
  // console.log("ghsdcg");
  // $("#allmodal").modal('show');
  var data = {
    'EmployeeId': $('.addEmployeeName').val(),
    'PONumber': $('.addPONumber').val(),
    'PARNo': $('.addPAR').val(),
    'Type': $('.addpropertytype').val(),
    'FundCluster': $('.addFundCluster').val(),
    'ItemName': $('.addItemName').val(),
    'Description': $('.addDescription').val(),
    'Quantity': $('.addQuantity').val(),
    'Unit': $('.addUnit').val(),
    'UnitPrice': $('.addUnitPrice').val(),
    'Issuedby': $('.addIssuedby').val(),
    'DateIssued': $('.addDateIssued').val(),
    'DateAcquired': $('.addDateAcquired').val(),
    'PONumber': $('.addPONumber').val(),
    'DateReceived': $('.addDateReceived').val(),
    'Supplier': $('.addSupplier').val(),
    'Estimatedusefullife': $('.addEstimatedusefullife').val(),
  }
  console.log(data);
  if(data.EmployeeName == "" || data.Type == "" || data.FundCluster == "" || data.ItemName == "" || 
     data.Description == "" || data.Quantity == "" || data.Unit == "" || data.UnitPrice == "" || 
     data.Issuedby == "" || data.DateIssued == "" || data.DateAcquired == "" || data.DateAcquired == "" || 
     data.PONumber == "" || data.DateReceived == "" || data.Supplier == "" /* || data.Estimatedusefullife == "" */   ){
    Swal.fire('Complete the needed data', '', 'info')
  }else{
    console.log(data);
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "submitadd-ics",
      data:data,
      success: function (response) {
        console.log(response);
        if(response['status'] == 200) {
          Swal.fire({
            title: 'Added!!!',
            html: 'Added Successfully!',
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
        }else if(response['status'] == 400){
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Check Inputs',
          })
        }
      }
    });
  }
  
});

$(document).on('click', '.finaldeleteics', function (e) {
  var id = $(this).attr('data-id');
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "finaldelete-ics",
      data:{'id':id},
      success: function (response) {
        console.log(response);
       $(".dataEmployeeName").text(response[0]['EmployeeName']);
       $('.dataItemName').text(response[0]['ItemName']);
       $('.dataDescription').text(response[0]['Description']);
       $('.dataQuantity').text(response[0]['Quantity']+" "+response[0]['unit']+" @ "+response[0]['UnitPrice']+".00");
       $('.checkQuantity').val(response[0]['Quantity']);
       $('.disposequantity').text(response[0]['Quantity']);
       $('.disposequantity').val(response[0]['Quantity']);
       $('.dataPONo').text(response[0]['PONumber']);
       $('.dataSupplier').text(response[0]['Storename']);
       $('.deletefinalics').val(response[0]['id']);
      }
    });
});

$(document).on('click', '.deletefinalics', function (e) {
  // console.log("ghsdcg");
  // $("#allmodal").modal('show');
  var data = {
    'Check': $('.checkQuantity').val(),
    'Quantity': $('.disposequantity').val(),
    'Remarks': $('.remarks').val(),
    'id': $(this).val(),
  }
  console.log(data);
  if(data.Quantity > data.Check){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Quantity exceeds the remaining Items',
    })
  }else{
    if(data.Quantity == "" || data.Remarks == ""){
      Swal.fire('Complete the needed data', '', 'info')
    }else{
      console.log(data);
      Swal.fire({
        title: 'Are you sure you want to delete?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        icon: 'question',
        denyButtonText: 'No',
        customClass: {
          actions: 'my-actions',
          confirmButton: 'order-2',
          denyButton: 'order-3',
        }
      }).then((result) => {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          if (result.isConfirmed) {
            $.ajax({
                  type: "post",
                  url: "submitdelete-ics",
                  data:data,
                  success: function (response) {
                    // alert(response.status);
                    if (response.status == 200) {
                      Swal.fire({
                        title: 'Deleted!!!',
                        html: 'Deleted Successfully!',
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
    }
  }
  
});

$(document).on('click', '.transferto', function (e) {
  var id = $(this).attr('data-id');
  $('.transfertoEmployeeName').html("");
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
      type: "post",
      url: "transfer-ics",
      data:{'id':id},
      success: function (response) {
        console.log(response);
       $(".transferEmployeeName").text(response[0]['EmployeeName']);
       $('.transferItemName').text(response[0]['ItemName']);
       $('.transferDescription').text(response[0]['Description']);
       $('.transferQuantity').text(response[0]['Quantity']+" "+response[0]['unit']+" @ "+response[0]['UnitPrice']+".00");
       $('.transfercheckQuantity').val(response[0]['Quantity']);
      //  for($emp = 0; $emp < response['1'].length; $emp++){
      //   $('.transfertoEmployeeName').append("<");
      //  }
        
      //   );
        const select = document.querySelector('.transfertoEmployeeName')

        const selected = document.createElement('option');
        selected.innerText = "Choose...";
        selected.disabled = true;
        selected.selected = true;
        select.append(selected);

        for ($emp = 0; $emp < response['1'].length; $emp++) {
          const opt = document.createElement('option');
          opt.value = response['1'][$emp]['id'];
          opt.innerHTML = response['1'][$emp]['name'];
          select.appendChild(opt);
        }

       $('.transferfrom').val(response[0]['EmployeeID']);
       $('.transfer2quantity').text(response[0]['Quantity']);
       $('.transfer2quantity').val(response[0]['Quantity']);
       $('.transferPONo').text(response[0]['PONumber']);
       $('.transferSupplier').text(response[0]['Storename']);
       $('.transferfinal').val(response[0]['id']);
      }
    });
});

$(document).on('click', '.transferfinal', function (e) {
  // console.log("ghsdcg");
  // $("#allmodal").modal('show');
  var data = {
    // 'Check':,
    'Employeefrom': $('.transferfrom').val(),
    'Employeeto': $('.transfertoEmployeeName').val(),
    'Quantity': $('.transfer2quantity').val(),
    'Remarks': $('.transferremarks').val(),
    'id': $(this).val(),
  }
  console.log(data);
  if(data.Quantity > $('.transfercheckQuantity').val()){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Quantity exceeds the remaining Items',
    })
  }else{
    if(data.Quantity == "" || data.Remarks == ""){
      Swal.fire('Complete the needed data', '', 'info')
    }else{
      console.log(data);
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
            type: "post",
            url: "submittransfer-ics",
            data:data,
            success: function (response) {
              // alert(response.status);
              if (response.status == 200) {
                Swal.fire({
                  title: 'Transferred!!!',
                  html: 'Transferred Successfully!',
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
    }
  }
  
  
});

$(document).on('click', '.print', function (e) {
          console.log($(this).attr('data-id'));
          // alert(1);
  var id = $(this).attr('data-id');

  $('.PrintBody').html('');

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $.ajax({
        type: "post",
        url: "print-ics",
        data:{'id':id},
        success: function (response) {
          console.log(response);
          if (response.status == 200) {
            $('.PrintBody').append(
             '<tr style="vertical-align: top;">\
                  <td style="text-align: center">'+response['data'][0]['Quantity']+'</td>\
                  <td style="text-align: center">'+response['data'][0]['unit']+'</td>\
                  <td><span style="font-weight: 900">'+response['data'][0]['ItemName']+'</span>\
                      <br>&nbsp;&nbsp;&nbsp;&nbsp;'+response['data'][0]['Description']+'\
                      <br><br>&nbsp;&nbsp;&nbsp;&nbsp;PO NO. '+response['data'][0]['PONumber']+'  \
                      <br>&nbsp;&nbsp;&nbsp;&nbsp;Store: '+response['data'][0]['SupplierName']+'   \
                  </td>\
                  <td> '+response['data'][0]['PropertyNumber']+'</td>  \
                  <td> '+response['data'][0]['DateAcquired']+'</td>\
                  <td> '+response['data'][0]['UnitPrice']+'</td>  \
                  <td> '+response['data'][0]['TotalAmount']+'</td>\
              </tr>'
            );

            $(".Ics").text('ICS No. : '+response['data'][0]['PARNo']);
            $(".Fund").text(response['data'][0]['FundCluster']);
            $(".employeeName").text(response['data'][0]['name']);
            $(".positionTitle").text(response['data'][0]['EmpPosition']);
            $(".IssuedName").text(response['data'][0]['Issuedby']);
            $(".IBpositionTitle").text(response['data'][0]['IsPosition']);
            $(".btnPrintList").val(response['data'][0]['id']);
             
          }
          else{

          }

        }
  });
});

$(document).on('click', '.closeIcs', function (e) {
  location.reload();
});

$(document).on('click', '.btnPrintList', function (e) {
      // $("#printModal").modal('hide');
  console.log($(this).attr("value"));
  const id = $(this).val();

  $.ajax({
  type: 'post',
  url: "printics",
  headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
  data: { 'id' : id },
  success: function(viewContent) {
    console.log(viewContent);
    /* console.log(viewContent); */
      /* if(viewContent){
          $.print(viewContent);
      }else{
          toastr.error('Can\'t print. Error!')
      }
      This is where the script calls the printer to print the viwe's content.
      $('.print-btn-RIO4').prop('disabled', false);
      $('.print-btn-RIO4').html('Print'); 

     // var printContents = document.getElementById(divName).innerHTML;
      /* var window = window.open(); */
      if(viewContent){
        /* setTimeout(function() { */
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = viewContent;
            /* window.document.close(); // necessary for IE >= 10
            window.focus(); // necessary for IE >= 10 */
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();

           /*  window.close(); */
        /* }, 250); */
      }else{
          toastr.error('Can\'t print. Error!')
      }
  },
  error: function (data){
      console.log(data);
  }
  });
});
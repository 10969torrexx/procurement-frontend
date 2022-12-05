$(document).on('click', '.par_add', function (e) {
    // console.log("ghsdcg");
    // $('.editSupplierName').val("");
    // $('.editAddress').val("");
    // $('.editContactNumber').val("");
    // $('.editDescription').val("");
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
      'DateReceived': $('.DateReceived').val(),
      'Supplier': $('.Supplier').val(),
      'Estimatedusefullife': $('.Estimatedusefullife').val(),
      // 'id': $(this).val(),
    }
    console.log(data);
    if(data.EmployeeName == "" || data.Type == "" || data.FundCluster == "" || data.ItemName == "" || 
       data.Description == "" || data.Quantity == "" || data.Unit == "" || data.UnitPrice == "" || 
       data.Issuedby == "" || data.DateIssued == "" || data.DateAcquired == "" || data.DateAcquired == "" || 
       data.PONumber == "" || data.DateReceived == "" || data.Supplier == "" || data.Estimatedusefullife == ""   ){
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
       $('.EditUnit').val(response[0]['Unit']);
       $('.EditUnitPrice').val(response[0]['UnitPrice']);
       $('#selectedIssuedBy').val(response[0]['IssuedBy']);
       $('#selectedIssuedBy').text(response[0]['IssuedbyName']);
       $('.EditDateIssued').val(response[0]['DateIssued']);
       $('.EditDateAcquired').val(response[0]['DateAcquired']);
       $('.EditPONumber').val(response[0]['PONumber']);
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
    'DateReceived': $('.EditDateReceived').val(),
    'Supplier': $('.EditSupplier').val(),
    'Estimatedusefullife': $('.EditEstimatedusefullife').val(),
    'id': $(this).val(),
  }
  console.log(data);
  if(data.EmployeeName == "" || data.Type == "" || data.FundCluster == "" || data.ItemName == "" || 
     data.Description == "" || data.Quantity == "" || data.Unit == "" || data.UnitPrice == "" || 
     data.Issuedby == "" || data.DateIssued == "" || data.DateAcquired == "" || data.DateAcquired == "" || 
     data.PONumber == "" || data.DateReceived == "" || data.Supplier == "" || data.Estimatedusefullife == ""   ){
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
       $('.addPARNo').text(par);
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
     data.PONumber == "" || data.DateReceived == "" || data.Supplier == "" || data.Estimatedusefullife == ""   ){
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
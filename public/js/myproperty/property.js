$(document).on('click', '.parClosed', function (e) {
      location.reload();
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
        url: "print-show",
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
            
            // $par = (response['data'][0]['DateIssued'].substring(0, 7)+"-"+response['data'][0]['PARNo'].padStart( 4, "0") );
            // console.log(response['data'][0]['propertytype']);
            $type = response['data'][0]['propertytype'];
            if($type == "PAR") {
              $(".Par").text('PAR No. : '+response['data'][0]['PARNo']);
            }else {
              $(".Par").text('ICS No. : '+response['data'][0]['PARNo']);
            }
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

$(document).on('click', '.closePar', function (e) {
  location.reload();
});

$(document).on('click', '.btnPrintList', function (e) {
      // $("#printModal").modal('hide');
  console.log($(this).attr("value"));
  const id = $(this).val();

  $.ajax({
  type: 'post',
  url: "print-property",
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

$(document).on('click', '.currentUser', function (e) {
  console.log($(this).attr('data-id'));
  // alert(1);
  var id = $(this).attr('data-id');
  var quantity = $(this).attr('value')

      // alert(quantity);
  // $(".CurrentUser").val($(this).attr('data-id'));
  $(".AddUser").val(id);
  $('.CurrentBody').html('');
  $('.userbutton').html('');

$.ajaxSetup({
headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$.ajax({
type: "post",
url: "current-user-list",
data:{'id':id},
success: function (response) {
  console.log(response);
  if (response.status == 200) {
    $(".remainingquantity").val(response['userQuantity']);
    $(".itemquantity").val(quantity);
    if (response['data'].length > 0) {
      $userquantity = 0 ;
      for(a = 0; a < response['data'].length; a++){
        $('.CurrentBody').append(
        '<tr style="vertical-align: top;">\
              <td style="text-align: center">\
                <div class="dropdown">\
                  <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>\
                  <div class="dropdown-menu dropdown-menu-left">\
                    <a class="dropdown-item edit" data-id = "<?=$aes->encrypt()?>" value="{{ }}" data-toggle = "modal" id="edit_item_Modal" href = "{{ $aes->encrypt($items->id) }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>\
                    <a class="dropdown-item delete-item" href = "{{ $aes->encrypt($items->id) }}">\
                    <i class="bx bx-trash mr-1"></i> delete\
                    </a>\
                  </div>\
                </div> \
              </td>\
              <td> '+response['data'][a]['ItemName']+'</td>  \
              <td> '+response['data'][a]['name']+'</td>\
              <td> '+response['data'][a]['quantity']+'</td>  \
              <td> '+response['data'][a]['created_at']+'</td>\
          </tr>'
        );
        
        $userquantity += response['data'][a]['quantity'];
      }

      if(response['userQuantity'] > 0/*  || response['data'].length <= 0  */){
        $('.userbutton').append(
          '<a href="#" type="button" class="btn btn-primary AddUserModal" data-toggle = "modal" data-target = "#AddUserModal" value="'+id+'"><i class="fa-solid fa-plus" value=""></i> &nbsp; Add</a>'
        );
      }else{
        $('.userbutton').append(
          '<div class="col-sm-12 p-1 bg-info text-white"><i class="fa-solid fa-circle-info"></i> &nbsp; You have reached the limit !</div>'
        );
      }
    } else {

      $('.userbutton').append(
        '<a href="#" type="button" class="btn btn-primary AddUserModal" data-toggle = "modal" data-target = "#AddUserModal" value=""><i class="fa-solid fa-plus" value="'+id+'"></i> &nbsp; Add</a>'
      );

      $('.CurrentBody').append(
       '<tr style="vertical-align: top;">\
            <td colspan="5" style="text-align:center"> No Data</td>\
        </tr>'
      );
    }
     
  }
  else{

  }

}
});
});

$(document).on('click', '.AddUser', function (e) {
  console.log($(this).val());
  // alert(1);
  
  var data = {
    // 'Check':,
    'id': $(this).val(),
    'name': $('.Username').val(),
    'quantity': $('.Userquantity').val(),
    'remainingquantity': $('.remainingquantity').val(),
    'itemquantity': $('.itemquantity').val()
  }
  console.log(data);
  if(data.name == "" || data.quantity == "" || data.quantity <= 0){
    Swal.fire('Complete the needed data!', '', 'info')
  }else{
    if(data.quantity > data.itemquantity){
        Swal.fire('Exceeds the quantity!', '', 'info')
    }else{
      if(data.remainingquantity == 0 || data.remainingquantity >= data.quantity) {
        $('.CurrentBody').html('');
        $('.userbutton').html('');
        $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        type: "post",
        url: "current-user-add",
        data:data,
        success: function (response) {
          console.log(response);
          if (response.status == 200) {
            $(".remainingquantity").val(response['userQuantity']);
            $(".itemquantity").val(data.itemquantity);
            $("#AddUserModal").modal('hide');
            if (response['data'].length > 0) {
              $userquantity = 0 ;
              for(a = 0; a < response['data'].length; a++){
                $('.CurrentBody').append(
                '<tr style="vertical-align: top;">\
                      <td style="text-align: center">\
                        <div class="dropdown">\
                          <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>\
                          <div class="dropdown-menu dropdown-menu-left">\
                            <a class="dropdown-item edit" data-id = "<?=$aes->encrypt()?>" value="{{ }}" data-toggle = "modal" id="edit_item_Modal" href = "{{ $aes->encrypt($items->id) }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>\
                            <a class="dropdown-item delete-item" href = "{{ $aes->encrypt($items->id) }}">\
                            <i class="bx bx-trash mr-1"></i> delete\
                            </a>\
                          </div>\
                        </div> \
                      </td>\
                      <td> '+response['data'][a]['ItemName']+'</td>  \
                      <td> '+response['data'][a]['name']+'</td>\
                      <td> '+response['data'][a]['quantity']+'</td>  \
                      <td> '+response['data'][a]['created_at']+'</td>\
                  </tr>'
                );
                
                $userquantity += response['data'][a]['quantity'];
              }
        
              if(response['quantity'] > $userquantity /* || response['data'].length <= 0 */ ){
                $('.userbutton').append(
                  '<a href="#" type="button" class="btn btn-primary AddUserModal" data-toggle = "modal" data-target = "#AddUserModal" value=""><i class="fa-solid fa-plus" value="'+data.id+'"></i> &nbsp; Add</a>'
                );
              }else{
                $('.userbutton').append(
                  '<div class="col-sm-12 p-1 bg-info text-white"><i class="fa-solid fa-circle-info"></i> &nbsp; You have reached the limit !</div>'
                );
              }
            } else {
        
              $('.userbutton').append(
                '<a href="#" type="button" class="btn btn-primary AddUserModal" data-toggle = "modal" data-target = "#AddUserModal" value="'+data.id+'"><i class="fa-solid fa-plus" value=""></i> &nbsp; Add</a>'
              );
        
              $('.CurrentBody').append(
              '<tr style="vertical-align: top;">\
                    <td colspan="5" style="text-align:center"> No Data</td>\
                </tr>'
              );
            }
            
          }
          else if(response.status == 300){
            Swal.fire('Exceeds the remaining quantity!', '', 'info')
          }
    
        }
        });
      }else if (data.remainingquantity < data.quantity) {
        Swal.fire('Exceeds the remaining quantity!', '', 'info')
      }
    }
  }
});

$(document).on('click', '.AddUserModal', function (e) {
  $('.Username').val("");
  $('.Userquantity').val("");
});
// {{-- {{number_format(str_replace(",","",$sub->UnitPrice),2,'.',',') }} --}}{{-- {{number_format(str_replace(",","",$sub->UnitPrice)*$sub->Quantity,2,'.',',') }} --}}

$(document).on('click', '.generatepdf', function (e) {
    // var year = $(".Year").val();
    // var campusCheck = $(".campusCheck").val();
    var data = {
      'year' :  $(".Year").val(),
      'campusCheck' : $(".campusCheck").val(),
      'category' : $(".project_category").val()
    }
  
    console.log(data);
    if(data.year == ""){
      Swal.fire('Complete the needed data', '', 'info')
    }
    else{
      $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
  
      $.ajax({
        type: "post",
        url: "pres_generatepdf",
        data:data,
        // xhrFields: {
        //   responseType: 'blob'
        // },
        success: function (response) {
          // if(response['status'] == 400){
          //   Swal.fire({
          //       icon: 'error',
          //       title: 'Oops...',
          //       text: 'Incomplete Inputs',
          //     })
          // }else{
            // var blob = new Blob([response]);
            // var link = document.createElement('a');
            // link.href = window.URL.createObjectURL(blob);
            // link.download = "APP_NON_CSE.pdf";
            // link.click();
          // }
          console.log(response);
        },
        error: function(blob){
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Incomplete Signatories',
            })
            console.log(blob);
        }
      });
    }
});

  
$(document).on('click', '.approve', function (e) {
    var data = {
        'year' :  $(".Year").val(),
        // 'campusCheck' : $(".campusCheck").val(),
        'category' : $(".project_category").val(),
        'app_type' : $(".app_type").val(),
        'value' : $(this).val()
    }
    
    console.log(data);
    if(data.year == ""){
    Swal.fire('Complete the needed data', '', 'info')
    }
    else{
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.ajax({
            type: "post",
            url: "pres_decision",
            data:data,
            success: function (response) {
            if(response['status'] == 200){
                Swal.fire({
                title: '',
                html: '',
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
            }else{}
                // var blob = new Blob([response]);
                // var link = document.createElement('a');
                // link.href = window.URL.createObjectURL(blob);
                // link.download = "APP_NON_CSE.pdf";
                // link.click();
            // }
            // console.log(response);
            },
            error: function(blob){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error',
                })
                console.log(blob);
            }
        });
    }
});

$(document).on('click', '.disapprove', function (e) {
    var data = {
        'year' :  $(".Year").val(),
        // 'campusCheck' : $(".campusCheck").val(),
        'category' : $(".project_category").val(),
        'app_type' : $(".app_type").val(),
        'value' : $(this).val()
    }
    
    console.log(data);
    if(data.year == ""){
    Swal.fire('Complete the needed data', '', 'info')
    }
    else{
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.ajax({
            type: "post",
            url: "pres_decision",
            data:data,
            success: function (response) {
            if(response['status'] == 200){
                Swal.fire({
                title: '',
                html: '',
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
            }else{}
                // var blob = new Blob([response]);
                // var link = document.createElement('a');
                // link.href = window.URL.createObjectURL(blob);
                // link.download = "APP_NON_CSE.pdf";
                // link.click();
            // }
            // console.log(response);
            },
            error: function(blob){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error',
                })
                console.log(blob);
            }
        });
    }
});

$(document).on('click', '.print', function (e) {
    var data = {
        'year' :  $(".Year").val(),
        'campusCheck' : $(".campusCheck").val(),
        'category' : $(".project_category").val(),
        'app_type' : $(".app_type").val(),
        // 'value' : $(this).val()
    };
console.log(data);
$.ajax({
type: 'post',
url: "pres_print",
headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
data: data,
success: function(viewContent) {
  console.log(viewContent);
    if(viewContent){
        var css = '@page { size: Legal landscape; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet){
        style.styleSheet.cssText = css;
        } else {
        style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

          var originalContents = document.body.innerHTML;
          document.body.innerHTML = viewContent;
          window.print();
          document.body.innerHTML = originalContents;
          location.reload();
    }else{
        toastr.error('Can\'t print. Error!')
    }
},
error: function (data){
    console.log(data);
}
});
});
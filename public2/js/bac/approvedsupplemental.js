
$(document).on('click', '.view', function (e) {
    e.preventDefault();

    var data = {
      'project_code': $(this).attr("href"),
      'employee_id': $(this).attr("data-toggle"),
      'department_id': $(this).attr("data-id"),
  }
   //  var id = $(this).attr("href");
          console.log(data);
    $.ajaxSetup({
    headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
 
    $.ajax({
       type: "post",
       url: "show-supplemental",
       data:data,
       beforeSend : function(html){
       },
       success: function (response) {
          if(response['status'] == 200) {
          $("#viewSupplementalmodal").modal('show');
             $('.code').text(response['data'][0]['project_code']);
             $('.project_title').text(response['data'][0]['project_title']);
             
             $('.tbody').html('')
             $('.tbody2').html('')

             var total1 = 0;
             var total2 = 0;

             for(var i = 0; i < response['data'].length; i++) {
                // console.log(i);
                var e = i+1;
                total1 += response['data'][i]['estimated_price'];
 
                $('.tbody').append(
                 ' <tr >\
                   <td style=" font-size: 11px" class="text-dark text-bold-600 ">'+e+'</td>\
                   <td style=" font-size: 12px;font-weight:900" class="text-dark text-bold-600 item">'+ response['data'][i]['item_name']+'</td>\
                   <td  class="quantity " style="width: 40px;">'+ response['data'][i]['quantity'] +'</td>\
                   <td  class="unit" >'+ response['data'][i]['unit_of_measurement'] +'</td>\
                   <td  class="budget" >'+ response['data'][i]['estimated_price'] +'</td>\
                   <td  class="mode align" >'+  response['data'][i]['mode_of_procurement'] +'</td>\
                   <td  class="jan-'+i+' months" ></td>\
                   <td  class="feb-'+i+' months"></td>\
                   <td  class="mar-'+i+' months"></td>\
                   <td  class="apr-'+i+' months"></td>\
                   <td  class="may-'+i+' months" ></td>\
                   <td  class="jun-'+i+' months"></td>\
                   <td  class="jul-'+i+' months"></td>\
                   <td  class="aug-'+i+' months"></td>\
                   <td  class="sep-'+i+' months"></td>\
                   <td  class="oct-'+i+' months"></td>\
                   <td  class="nov-'+i+' months"></td>\
                   <td  class="dec-'+i+' months"></td>\
                </tr>\
                <tr >\
                   <td style=" font-size: 11px" class="text-dark text-bold-600 "></td>\
                   <td class="description align">'+ response['data'][i]['item_description'] +'</td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                   <td ></td>\
                </tr></table></div>'
                );

                if(response['data'][i]['expected_month'] == '1') {
                    $('.jan-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '2') {
                    $('.feb-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '3') {
                    $('.mar-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '4') {
                    $('.apr-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '5') {
                    $('.may-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '6') {
                    $('.jun-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '7') {
                    $('.jul-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '8') {
                    $('.aug-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '9') {
                    $('.sep-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '10') {
                    $('.oct-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '11') {
                    $('.nov-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 
                 if(response['data'][i]['expected_month'] == '12') {
                    $('.dec-'+i).html('<i class="fa-solid fa-check"></i>');
                 } 

             }
             var totals = format(total1,2,".",",")
             // console.log(totals);
                $('.total1').text('Php '+totals);
            //  console.log(response);
            // var d=0;
             for(var a = 0; a < response['data2'].length; a++) {
                // console.log(a);
                var e = a+1;
               //  d += a;
                total2 += response['data2'][a]['estimated_price'];
 
                $('.tbody2').append(
                    '<tr >\
                       <td style=" font-size: 11px" class="text-dark text-bold-600 ">'+e+'</td>\
                       <td style=" font-size: 12px;font-weight:900" class="text-dark text-bold-600 item">'+ response['data2'][a]['item_name']+'</td>\
                       <td  class="quantity " style="width: 40px;">'+ response['data2'][a]['quantity'] +'</td>\
                       <td  class="unit" >'+ response['data2'][a]['unit_of_measurement'] +'</td>\
                       <td  class="budget" >'+ response['data2'][a]['estimated_price'] +'</td>\
                       <td  class="mode" >'+  response['data2'][a]['mode_of_procurement'] +'</td>\
                       <td  class="jan1-'+a+' months" ></td>\
                       <td  class="feb1-'+a+' months"></td>\
                       <td  class="mar1-'+a+' months"></td>\
                       <td  class="apr1-'+a+' months"></td>\
                       <td  class="may1-'+a+' months" ></td>\
                       <td  class="jun1-'+a+' months"></td>\
                       <td  class="jul1-'+a+' months"></td>\
                       <td  class="aug1-'+a+' months"></td>\
                       <td  class="sep1-'+a+' months"></td>\
                       <td  class="oct1-'+a+' months"></td>\
                       <td  class="nov1-'+a+' months"></td>\
                       <td  class="dec1-'+a+' months"></td>\
                    </tr>\
                    <tr >\
                       <td style=" font-size: 11px" class="text-dark text-bold-600 "></td>\
                       <td class="description">'+ response['data2'][a]['item_description'] +'</td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                       <td ></td>\
                    </tr>'
                    );
 
                if(response['data2'][a]['expected_month'] == '1') {
                   $('.jan1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '2') {
                   $('.feb1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '3') {
                   $('.mar1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '4') {
                   $('.apr1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '5') {
                   $('.may1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '6') {
                   $('.jun1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '7') {
                   $('.jul1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '8') {
                   $('.aug1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '9') {
                   $('.sep1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '10') {
                   $('.oct1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '11') {
                   $('.nov1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
                if(response['data2'][a]['expected_month'] == '12') {
                   $('.dec1-'+a).html('<i class="fa-solid fa-check"></i>');
                } 
             }
             var sum = format(total2,2,".",",")
             // console.log(totals);
                $('.total2').text('Php '+sum);

            var compute = total1+total2;
            var OverallTotal = format(compute,2,".",",")
            $('.overall').text('Php '+ OverallTotal);
            var data1 = 2;
            //  pageButtons(d)
            // $('.modal-footer').html();

          }
       }
    });
 });

 $(function () {
   $('[data-toggle="tooltip"]').tooltip()
 }) 
//  $(document).on('click', '.description', function (e) {
//    e.preventDefault();
//    var id = $(this).attr("href");
//          // console.log(id);
//    $.ajaxSetup({
//    headers: {
//          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//    }
//    });

//    $.ajax({
//       type: "post",
//       url: "show-supplemental-description",
//       data:{'id' :id},
//       beforeSend : function(html){
//       },
//       success: function (response) {
//          if(response['status'] == 200) {
//          $("#viewSupplementalmodal").modal('show');
//          }
//       }
//    });
// });
 
// var state = {
//    // 'querySet': tableData,

//    'page': 2,
//    'rows': 5,
//    'window': 5,
// }

// // buildTable()

// function pagination(querySet, page, rows) {

//    var trimStart = (page - 1) * rows
//    var trimEnd = trimStart + rows

//    var trimmedData = querySet.slice(trimStart, trimEnd)

//    var pages = Math.round(querySet.length / rows);

//    return {
//        'querySet': trimmedData,
//        'pages': pages,
//    }
// }

// function pageButtons(pages) {
//    var wrapper = document.getElementById('pagination-wrapper')

//    wrapper.innerHTML = ``
//   console.log('Pages:', pages)

//    var maxLeft = (state.page - Math.floor(state.window / 2))
//    var maxRight = (state.page + Math.floor(state.window / 2))

//    if (maxLeft < 1) {
//        maxLeft = 1
//        maxRight = state.window
//    }

//    if (maxRight > pages) {
//        maxLeft = pages - (state.window - 1)
       
//        if (maxLeft < 1){
//           maxLeft = 1
//        }
//        maxRight = pages
//    }
   
   

//    for (var page = maxLeft; page <= maxRight; page++) {
//       wrapper.innerHTML += `<button value=${page} class="page btn btn-sm btn-info">${page}</button>`
//    }

//    if (state.page != 1) {
//        wrapper.innerHTML = `<button value=${1} class="page btn btn-sm btn-info">&#171; First</button>` + wrapper.innerHTML
//    }

//    if (state.page != pages) {
//        wrapper.innerHTML += `<button value=${pages} class="page btn btn-sm btn-info">Last &#187;</button>`
//    }

//    $('.page').on('click', function() {
//        $('#table-body').empty()

//        state.page = Number($(this).val())

//       //  buildTable()
//    })

// }


 function format( number_input, decimals, dec_point, thousands_sep ) {
    var number       = ( number_input + '' ).replace( /[^0-9+\-Ee.]/g, '' );
    var finite_number   = !isFinite( +number ) ? 0 : +number;
    var finite_decimals = !isFinite( +decimals ) ? 0 : Math.abs( decimals );
    var seperater     = ( typeof thousands_sep === 'undefined' ) ? ',' : thousands_sep;
    var decimal_pont   = ( typeof dec_point === 'undefined' ) ? '.' : dec_point;
    var number_output   = '';
    var toFixedFix = function ( n, prec ) {
      if( ( '' + n ).indexOf( 'e' ) === -1 ) {
        return +( Math.round( n + 'e+' + prec ) + 'e-' + prec );
        } else {
        var arr = ( '' + n ).split( 'e' );
        let sig = '';
        if ( +arr[1] + prec > 0 ) {
          sig = '+';
        }
        return ( +(Math.round( +arr[0] + 'e' + sig + ( +arr[1] + prec ) ) + 'e-' + prec ) ).toFixed( prec );
      }
    }
    number_output = ( finite_decimals ? toFixedFix( finite_number, finite_decimals ).toString() : '' + Math.round( finite_number ) ).split( '.' );
    if( number_output[0].length > 3 ) {
      number_output[0] = number_output[0].replace( /\B(?=(?:\d{3})+(?!\d))/g, seperater );
    }
    if( ( number_output[1] || '' ).length < finite_decimals ) {
      number_output[1] = number_output[1] || '';
      number_output[1] += new Array( finite_decimals - number_output[1].length + 1 ).join( '0' );
    }
    return number_output.join( decimal_pont );
 }
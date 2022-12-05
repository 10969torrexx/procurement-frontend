$(document).on('click', '.view', function (e) {
   e.preventDefault();
   var data = {
      'project_code': $(this).attr("href"),
      // 'employee_id': $(this).attr("data-toggle"),
      // 'department_id': $(this).attr("data-id"),
  }
         // console.log(data);
   $.ajaxSetup({
   headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
   });

   $.ajax({
      type: "post",
      url: "show-approved",
      data:data,
      beforeSend : function(html){
      },
      success: function (response) {
         if(response['status'] == 200) {
         $("#viewmodal").modal('show');
            $('.code').text(response['data'][0]['ProjectCode']);
            $('.project_title').text(response['data'][0]['project_title']);
            
            $('.tbody').html('')
            var total = 0;
            for(var i = 0; i < response['data'].length; i++) {
               // console.log(i);
               var e = i+1;
               total += response['data'][i]['estimated_price'];
               var price = response['data'][i]['estimated_price']
               var priceformat = format(price,2,".",",")
               $('.tbody').append(
               '<tr >\
                  <td style=" font-size: 11px" class="text-dark text-bold-600 ">'+e+'</td>\
                  <td style="; text-align:left;" class="item"><br></bvr>'+ response['data'][i]['item_name']+'<br><br>Description:<br> '+ response['data'][i]['item_description'] +' </td>\
                  <td  class="quantity " style="width: 40px;">'+ response['data'][i]['quantity'] +'</td>\
                  <td  class="unit" >'+ response['data'][i]['unit_of_measurement'] +'</td>\
                  <td  class="budget" >Php '+ priceformat +'</td>\
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
               </tr>'
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
            
            var totals = format(total,2,".",",")
            // console.log(totals);
               $('.total').text('Php '+totals);
         }
      }
   });
});

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
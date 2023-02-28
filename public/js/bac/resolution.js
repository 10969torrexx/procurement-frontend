$(document).on('click', '.addSign', function (e) {
    e.preventDefault();
    var a = $(this).attr('value');
    var b = $(this).attr('data-id');
    $('.idNum').val(b+""+a);
});

$(document).on('click', '.submit', function (e) {
  
  $('#ResSignatories').modal('toggle');
  // $('#ResSignatories').reload();
  var id = $('.idNum').val();
  
  var btn = document.getElementById("a"+id);
  if (btn.style.display === 'none'){
     btn.style.display = 'block';
  }else{
     btn.style.display = 'none'
  }

  var btn1 = document.getElementById("b"+id);
  if (btn1.style.display === 'none'){
     btn1.style.display = 'block';
  }else{
     btn1.style.display = 'none'
  }
  var d=document.getElementById("Name");
  var nametext=d.options[d.selectedIndex].text;

  var nameid = $('.Name').val();
  var suffix = $('.edutitle').val();
  var designation = $('.inputProfession').val();
  // var divId = $('.b'+id);

  // console.log(divId);

  $('#b'+id).append(
    '<div style="font-transform:uppercase" value="'+nameid+'" class="Name'+id+'">'+ nametext +', <span class="suffix'+id+' value="'+suffix+'">'+ suffix +'</span></div>\
    <div class="designation'+id+'" value="'+designation+'">'+designation+'</div>'
  );

  // $("#edit_item_modal").modal('hide');
  
});

$('#ResSignatories').on('hidden.bs.modal', function (e) {
  $(this)
    .find("input,textarea")
       .val('')
       .end()
       document.getElementById('Name').getElementsByTagName('option')[1].selected = 'selected'
    // .find("select")
    //    .prop("checked", "")
    //    .end();
})
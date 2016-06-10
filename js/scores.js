$(document).ready(function() {
  $('.home, .away').bind('keypress', function (event) {
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
  }).bind("input", function(){
    if( $(this).text() != "" ) {
      $(this).addClass("correct")
    } else {
      $(this).removeClass("correct")
    }
  });

  var results = [[[1,1],[]],[[],[]],[[],[]]]
  $("span").bind("input", function(){
    var row = [];
    var empty = false
    $(this).parent().find("span").each(function(){
      row.push( $(this).text()*1 );
      if ( $(this).text() == "") {
        empty = true
      }
    })

    if(!empty){
      $.ajax({
        method: "POST",
        url: "save.php",
        data: {index: $(this).parents(".round").index()+","+($(this).parents(".row").index()-1), value: [row[0]*1, row[1]*1] }
      })
      .done(function(data){
        console.log( data );
      })
    } else {
      $.ajax({
        method: "POST",
        url: "save.php",
        data: {index: $(this).parents(".round").index()+","+($(this).parents(".row").index()-1), value: [] }
      })
      .done(function(data){
        console.log( data );
      })
    }

  })

});

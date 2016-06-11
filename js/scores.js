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

    var href = window.location.href
    var hrefId = href.substring(href.search(/[0-9]/), href.search(/[0-9]/)+href.match(/[0-9]/).length);

    if(!empty){
      $.ajax({
        method: "POST",
        type: "html",
        url: "/FIFA-Generator/save.php",
        data: {index: $(this).parents(".round").index()-1+","+($(this).parents(".row").index()-1), value: [row[0]*1, row[1]*1], id: hrefId }
      })
      .done(function(data){
        console.log( data );
      })
    } else {
      $.ajax({
        method: "POST",
        type: "html",
        url: "/FIFA-Generator/save.php",
        data: {index: $(this).parents(".round").index()-1+","+($(this).parents(".row").index()-1), value: [], id: hrefId }
      })
      .done(function(data){
        console.log( data );
      })
    }

  })

});

$(".disabled").hide().removeClass("disabled")

var teams = [
  {
    "player": "Bartek",
    "club": "FC Bayern",
    "wins": 3,
    "draws": 1,
    "losses": 2,
    "gf": 4,
    "ga": 2
  },
  {
    "player": "Kuba",
    "club": "FC Barcelona",
    "wins": 2,
    "draws": 1,
    "losses": 3,
    "gf": 3,
    "ga": 4
  },
  {
    "player": "Michał",
    "club": "Leicester City",
    "wins": 4,
    "draws": 0,
    "losses": 2,
    "gf": 7,
    "ga": 2
  }
]

var data = eval(teams);
var results = data;

results.sort(function(a,b){
    var aPts = a.wins*3+a.draws
    var aGD = a.gf-a.ga
    var bPts = b.wins*3+b.draws
    var bGD = b.gf-b.ga
    if(aPts == bPts){

      if(aGD == bGD){

        if(a.gf == b.gf){
          return 0;
        }

        if(a.gf > b.gf){
          return 1;
        }

        if(a.gf < b.gf){
          return -1;
        }

      }

      if(aGD > bGD){
        return 1;
      }

      if(aGD < bGD){
        return 0;
      }

    }

    if(aPts < bPts){
        return -1;
    }

    if(aPts > bPts){
        return 1;
    }
});

results.reverse();

for(x=0; x<results.length; x++){
  var r = results[x]
  var tr = $("<tr></tr>")
  var td = tr
    .append( $("<td></td>").text(x+1) )
    .append( $("<td></td>").text(r.player) )
    .append( $("<td></td>").text(r.club) )
    .append( $("<td></td>").text(r.wins+r.draws+r.losses) )
    .append( $("<td></td>").text(r.wins) )
    .append( $("<td></td>").text(r.draws) )
    .append( $("<td></td>").text(r.losses) )
    .append( $("<td></td>").text(r.gf) )
    .append( $("<td></td>").text(r.ga) )
    .append( $("<td></td>").text(r.gf-r.ga) )
    .append( $("<td></td>").text(r.wins*3+r.draws) )
  $("tbody").append( tr )
}


$(".spinner input").bind("input", function(){
  var str = $(this).val();
  var res = str.replace(/[^0-9]/g, "");
  $(this).val( res )
})

$(".spinner .plus").bind("click", function(){
  var $p = $(this).siblings("input");
  $p.val( $p.val()*1+1 );
  $(this).blur();
})

$(".spinner .minus").bind("click", function(){
  var $p = $(this).siblings("input");
  if( $p.val() > 1 ){
    $p.val( $p.val()*1-1 );
  }
  $(this).blur();
})

$("#teams").bind("change", function(){
  if( $(this).prop("checked") ){
    $("#teamsNo").parents(".row").stop().slideDown(200)
  } else {
    $("#teamsNo").parents(".row").stop().slideUp(200)
  }
})

$("#type").bind("change", function(){
  var val = $(this).find(":selected").val();
  if( val == "League" ){
    $("#matchesvs").parents(".row").stop().slideDown(200)
  } else {
    $("#matchesvs").parents(".row").stop().slideUp(200)
  }
})

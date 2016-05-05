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

// TABLE END

$(".disabled").hide().removeClass("disabled")

$(".spinner input").bind("input", function(){
  var str = $(this).val();
  var res = str.replace(/[^0-9]/g, "");
  if(res == ""){
    res = "0"
  }
  $(this).val( res*1 )
})

$(".spinner .plus").bind("click", function(){
  var $p = $(this).siblings("input");
  $p.val( $p.val()*1+1 );
  $p.trigger("input")
  $(this).blur();
})

$(".spinner .minus").bind("click", function(){
  var $p = $(this).siblings("input");
  if( $p.val() > 1 ){
    $p.val( $p.val()*1-1 );
  }
  $p.trigger("input")
  $(this).blur();
})

$("#teams").bind("change", function(){
  if( $(this).prop("checked") ){
    $("#teamsNo").parents(".row").stop().slideDown(200)
    $("#teamsNo").val( Math.round( $("#players").val()/2 ) ).trigger("input")
  } else {
    $("#teamsNo").parents(".row").stop().slideUp(200)
    $("#teamsNo").trigger("input")
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

$("#players").bind("input", function(){
  if( $("#players").val() < 2 ){
    $("#players").val( 2 )
  }
  if( $("#teams").prop("checked") ){
    if( $("#players").val()/$("#teamsNo").val() < 1 ){
      $("#teamsNo").val( $("#players").val() )
    }
    if( $("#players").val()/$("#teamsNo").val() > 2 ){
      $("#teamsNo").val( Math.round( $("#players").val()/2 ) )
    }
  }
})

$("#teamsNo").bind("input", function(){
  if( $("#teamsNo").val() < 2 ){
    $("#teamsNo").val( 2 )
  }
  if( $("#teams").prop("checked") ){
    if( $("#players").val() > $("#teamsNo").val()*2 ){
      $("#players").val( $("#teamsNo").val()*2 )
    }
    if( $("#players").val() / $("#teamsNo").val() < 1 ){
      $("#players").val( $("#teamsNo").val() )
    }
  }
})

$("#players, #teamsNo").bind("input", function(){
  printTeams();
})

$("#preclubs").bind("change", function(){
  printTeams();
})

printTeams();

function printTeams(){
  $(".teams .team").remove();
  var playersNo = $("#players").val()*1+1
  var teamsNo = $("#teamsNo").val()*1+1;
  var players = distribute();
  if( $("#teams").prop("checked") ){
    y = teamsNo
  } else {
    y = playersNo
  }
  for(x=1; x<y; x++){
    var $team = $("<div></div>").attr("id", x).addClass("team")
    var temp = 1
    if( !$("#teams").prop("checked") ){
      $team.append( $("<input>").attr({"type": "text", 'name': "name1", "placeholder": "Player"}) )
    } else {
      for(i=0; i<players[x-1]; i++){
        $team.append( $("<input>").attr({"type": "text", 'name': "name"+(i+1), "placeholder": "Player "+(i+1)}) )
      }
    }
    if( $("#preclubs").prop("checked") ){
      $team.append( $("<input>").attr({"type": "text", 'name': "club", "placeholder": "Club name"}) )
    }
    $(".teams").append( $team )
  }
}

function distribute(){
  var items = [];
  var columns = [];
  var lengths = [];
  var its = $("#players").val()*1;
  var cols = $("#teamsNo").val()*1;
  for(x=0; x<its; x++){
  	items.push([]);
  }
  for(x=0; x<cols; x++){
  	columns.push([]);
  }

  for (x=0; x<items.length; x++) {
    columns[Math.floor(x * columns.length / items.length)].push(items[x]);
  }

  for(x=0; x<columns.length; x++){
  	lengths[x] = columns[x].length;
  }

  lengths.sort(function(a,b){return b-a});

  return lengths;
}

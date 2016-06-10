// var teams = [
//   {
//     "player": "Bartek",
//     "club": "FC Bayern",
//     "wins": 3,
//     "draws": 1,
//     "losses": 2,
//     "gf": 4,
//     "ga": 2
//   },
//   {
//     "player": "Kuba",
//     "club": "FC Barcelona",
//     "wins": 2,
//     "draws": 1,
//     "losses": 3,
//     "gf": 3,
//     "ga": 4
//   },
//   {
//     "player": "Michał",
//     "club": "Leicester City",
//     "wins": 4,
//     "draws": 0,
//     "losses": 2,
//     "gf": 7,
//     "ga": 2
//   }
// ]
//
// var data = eval(teams);
// var results = data;
//
// results.sort(function(a,b){
//   var aPts = a.wins*3+a.draws
//   var aGD = a.gf-a.ga
//   var bPts = b.wins*3+b.draws
//   var bGD = b.gf-b.ga
//   if(aPts == bPts){
//
//     if(aGD == bGD){
//
//       if(a.gf == b.gf){
//         return 0;
//       }
//
//       if(a.gf > b.gf){
//         return 1;
//       }
//
//       if(a.gf < b.gf){
//         return -1;
//       }
//
//     }
//
//     if(aGD > bGD){
//       return 1;
//     }
//
//     if(aGD < bGD){
//       return 0;
//     }
//
//   }
//
//   if(aPts < bPts){
//     return -1;
//   }
//
//   if(aPts > bPts){
//     return 1;
//   }
// });
// results.reverse();
//
// for(x=0; x<results.length; x++){
//   var r = results[x]
//   var tr = $("<tr></tr>")
//   var td = tr
//     .append( $("<td></td>").text(x+1) )
//     .append( $("<td></td>").text(r.player) )
//     .append( $("<td></td>").text(r.club) )
//     .append( $("<td></td>").text(r.wins+r.draws+r.losses) )
//     .append( $("<td></td>").text(r.wins) )
//     .append( $("<td></td>").text(r.draws) )
//     .append( $("<td></td>").text(r.losses) )
//     .append( $("<td></td>").text(r.gf) )
//     .append( $("<td></td>").text(r.ga) )
//     .append( $("<td></td>").text(r.gf-r.ga) )
//     .append( $("<td></td>").text(r.wins*3+r.draws) )
//   $("tbody").append( tr )
// }

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
  var $p = $(this).parents(".spinner").find("input");
  $p.val( $p.val()*1+1 );
  $p.trigger("input")
  $(this).blur();
})

$(".spinner .minus").bind("click", function(){
  var $p = $(this).parents(".spinner").find("input");
  if( $p.val() > 1 ){
    $p.val( $p.val()*1-1 );
  }
  $p.trigger("input")
  $(this).blur();
})

$("#teams").bind("change", function(){
  if( $(this).prop("checked") ){
    $("#teamsNo").parents(".form-horizontal").stop().slideDown(200)
    $("#teamsNo").val( Math.round( $("#players").val()/2 ) ).trigger("input")
  } else {
    $("#teamsNo").parents(".form-horizontal").stop().slideUp(200)
    $("#teamsNo").trigger("input")
  }
})

$("#type").bind("change", function(){
  var val = $(this).find(":selected").val();
  if( val == "League" ){
    $("#matchesvs").parents(".form-horizontal").stop().slideDown(200)
  } else {
    $("#matchesvs").parents(".form-horizontal").stop().slideUp(200)
  }
})

$("#matchesvs").bind("input", function(){
  if( $(this).val() > 2 ){
    $(this).val(2);
  }
});

$("#players").bind("input", function(){
  var playersVal = $("#players").val()
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

$("#players, #teamsNo, #matchesvs").bind("input", function(){
  printTeams();
})

$("#length").bind("change", function(){
  printTeams();
})

printTeams();

function printTeams(){
  var tempPlayers = [];
  var tempClubs = [];
  var t = 0;
  var c = 0;
  $(".team").each(function(){
    tempPlayers.push( $(this).find('[name="name"]').eq(0).val() )
    if( typeof( $(this).find('[name="name"]').eq(1).val() ) != "undefined" ){
      tempPlayers.push( $(this).find('[name="name"]').eq(1).val() )
    }
    tempClubs.push( $(this).find('[name="club"]').eq(0).val() )
  })
  $(".teams .center-inline-block .team").remove();
  var playersNo = $("#players").val()*1+1
  var teamsNo = $("#teamsNo").val()*1+1;
  var players = distribute();
  if( $("#teams").prop("checked") ){
    y = teamsNo
    $(".summary #fix").text( $("#teamsNo").val()*($("#teamsNo").val()-1)/2*$("#matchesvs").val() );
  } else {
    y = playersNo
    $(".summary #fix").text( $("#players").val()*($("#players").val()-1)/2*$("#matchesvs").val() );
  }

  var time = $(".summary #fix").text()*$("#length").find(":selected").val()*2/60;

  $(".summary #time").text( (time<1)? Math.floor(time*60*10)/10+" minutes": (time<2)? Math.floor(time*10)/10+" hour": Math.floor(time*10)/10+" hours");
  $(".summary #number").text( $("#players").val() );
  var vs = $("#matchesvs").val()*1;
  $(".summary #against").text( (vs==1)? "once" : "twice" )

  for(x=1; x<y; x++){
    var $team = $("<div></div>").attr("id", x).addClass("team").addClass("panel").addClass("panel-primary");
    var $head = $("<div></div>").addClass("panel-heading");
    var $body = $("<div></div>").addClass("panel-body");
    var temp = 1;
    if( !$("#teams").prop("checked") ){
      $head.append( $("<input>").attr({"type": "text", 'name': "name"+t, "placeholder": "Player"}).addClass("form-control").val( tempPlayers[t] ) );
      t++;
    } else {
      for(i=0; i<players[x-1]; i++){
        $head.append( $("<input>").attr({"type": "text", 'name': "name"+t, "placeholder": "Player "+(i+1)}).addClass("form-control").val( tempPlayers[t] ) );
        t++;
      }
    }
    $body.append( $("<input>").attr({"type": "text", 'name': "club"+c, "placeholder": "Club name"}).addClass("form-control").val( tempClubs[c] ) );
    c++;

    $team.append( $head ).append( $body );
    $(".center-inline-block").append( $team );
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


$("form").bind("submit", function(e){
  var temp = true
  $(".teams input").each(function(){
    if( $(this).val() == "" ){
      temp = false
    }
  })

  if(!temp){
    // alert( "Please fill in all of the inputs" )
    // e.preventDefault();
  }
})

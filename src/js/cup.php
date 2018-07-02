<?php header('Content-Type: application/javascript'); ?>

$(document).ready(function() {

  checkWidth();

  $(window).on("resize", function() {
    checkWidth();
  });

  $('.home, .away').each(function(index) {
    var $t = $(this);

    inputResize(this, 0);

    $t.on("focus", function() {
      setIndex($t);
    });

    $t.on("focus input", function() {
      if ($t.val() * 1 > 999) {
        $t.val(999);
      }

      inputResize(this, 15);

      if ($t.val() !== "") {
        $t.addClass("correct");
      } else {
        $t.removeClass("correct");
      }
    });

    $t.on("blur", function() {
      inputResize(this, 0);
    });
  });

  <?php
  if( isset($_GET["admin"]) && isset($_GET["id"]) ) {
?>
  $("input").on("input", function() {
    var row = [];
    var empty = false;
    var completed = true;
    var $m1s1, $m2s1;

    if ($(this).parents(".game").index(".game") % 2 === 0) {
      $m1s1 = $(this).parent().parent("div").find(".score1 input");
      $m2s1 = $(this).parents(".game").next().next().find(".score1 input");
      $m1s2 = $(this).parent().parent("div").find(".score2 input");
      $m2s2 = $(this).parents(".game").next().next().find(".score2 input");
    } else {
      $m1s1 = $(this).parents(".game").prev().prev().find(".score1 input");
      $m2s1 = $(this).parent().parent("div").find(".score1 input");
      $m1s2 = $(this).parents(".game").prev().prev().find(".score2 input");
      $m2s2 = $(this).parent().parent("div").find(".score2 input");
    }

    if ($m1s1.val() === "" || $m2s1.val() === "") {
      empty = true;
    }

    // if( $m1s2.val() === "" && $m2s2.val() === "" ){
    //   empty = true;
    // }

    empty = false;

    var token = "<?= $_GET["
    admin "]; ?>";
    var tournamentId = "<?= $_GET["
    id "]; ?>";

    var dummy = (($(".col").eq(0).children(".game:not(.game-hidden)").length / $(".col").eq(1).children(".game:not(.game-hidden)").length) != 2) ? 0 : 1;

    var index = ($(this).parents(".col").index() + dummy) + ":" + Math.floor($(this).parent().parent().parent(".game").prevAll(".game").length / 2);
    var v1 = ($m1s1.val() !== "") ? $m1s1.val() : -1;
    var v2 = ($m2s1.val() !== "") ? $m2s1.val() : -1;
    var v3 = ($m1s2.val() !== "") ? $m1s2.val() : -1;
    var v4 = ($m2s2.val() !== "") ? $m2s2.val() : -1;

    if (!empty && completed) {
      $.ajax({
          method: "POST",
          type: "json",
          url: "/save.php",
          data: {
            type: "Cup",
            "index": index,
            value: [v1 * 1, v2 * 1, v3 * 1, v4 * 1],
            id: tournamentId,
            admin: token
          }
        })
        .success(function(data) {
          var penalties = data.penalties;
          delete data.penalties;

          data = $.map(data, function(value, index) {
            return [value];
          });

          console.log(data);

          if (data !== "") {
            for (var x = 0; x < data.length; x++) {
              $col = $(".bracket > .col").eq(x);

              for (var y = 0; y < data[x].length; y++) {
                console.log(data[x][y]);
                var player1 = "",
                  player2 = "",
                  class1 = "",
                  class2 = "",
                  club1 = (typeof(playersList[data[x][y][0]]) !== "undefined") ? playersList[data[x][y][0]].club : "",
                  club2 = (typeof(playersList[data[x][y][1]]) !== "undefined") ? playersList[data[x][y][1]].club : "",
                  crest1 = '/logos/16/' + club1.replace(" ", "-").replace("/[^a-zA-Z0-9\-]+/", "").toLowerCase() + ".png",
                  crest2 = '/logos/16/' + club2.replace(" ", "-").replace("/[^a-zA-Z0-9\-]+/", "").toLowerCase() + ".png";

                if (data[x][y][0] >= 0) {
                  if (playersList[data[x][y][0]].players.length == 1) {
                    player1 = playersList[data[x][y][0]].players[0];
                  } else {
                    player1 = playersList[data[x][y][0]].players[0] + " & " + playersList[data[x][y][0]].players[1];
                    class1 = "small";
                  }
                }

                if (data[x][y][1] >= 0) {
                  if (playersList[data[x][y][1]].players.length == 1) {
                    player2 = playersList[data[x][y][1]].players[0];
                  } else {
                    player2 = playersList[data[x][y][1]].players[0] + " & " + playersList[data[x][y][1]].players[1];
                    class2 = "small";
                  }
                }

                $gameTop = $col.find(".game-top").eq(y);
                $gameBottom = $col.find(".game-bottom").eq(y);
                $gameTopSpan = $gameTop.find("span").first();
                $gameBottomSpan = $gameBottom.find("span").first();

                if ($gameTopSpan.text() !== player1 || $gameTopSpan.text() === "") {

                  if (player1 === "") {

                    $gameTop.find("input").attr("disabled", true);

                    $gameTop.find("img").first().stop().fadeOut(400, function() {
                      $(this).remove();
                    });

                    $gameTopSpan.stop().fadeOut(400, function() {
                      $(this).text("").show();
                    });

                  } else {

                    if (club1 !== "") {
                      var $imgTop = $("<img>").attr("src", crest1).css("display", "none");
                      $gameTopSpan.before($imgTop);
                      $imgTop.stop().fadeIn(400);
                    }

                    $gameTopSpan.stop().fadeOut(0).fadeIn(400).text(player1).removeClass("small").addClass(class1);
                    $gameTop.find("input").attr("disabled", false);
                  }

                }

                if ($gameBottomSpan.text() !== player2 || $gameBottomSpan.text() === "") {

                  if (player1 === "") {

                    $gameBottom.find("input").attr("disabled", true);

                    $gameBottom.find("img").stop().fadeOut(400, function() {
                      $(this).remove();
                    });
                    $gameBottomSpan.stop().fadeOut(400, function() {
                      $(this).text("").show();
                    });

                  } else {

                    if (club2 !== "") {
                      var $imgBottom = $("<img>").attr("src", crest2).css("display", "none");
                      $gameBottomSpan.before($imgBottom);
                      $imgBottom.stop().fadeIn(400);
                    }

                    $gameBottomSpan.stop().fadeOut(0).fadeIn(400).text(player2).removeClass("small").addClass(class2);
                    $gameBottom.find("input").attr("disabled", false);

                  }
                }
              }
            }
          }
        })
        .fail(function(data) {
          console.log(data);
        });

    } else {
      $.ajax({
          method: "POST",
          type: "json",
          url: "/save.php",
          data: {
            type: "Cup",
            "index": index,
            value: [],
            id: tournamentId,
            admin: token
          }
        })
        .success(function(data) {
          console.log(data);
        })
        .fail(function(data) {
          console.log(data);
        });

    }

  });

  <?php
  }
?>

  function setIndex($t) {
    if ($t.attr("tabindex") === undefined) {
      $("[tabindex]").removeAttr("tabindex");
      $t.attr("tabindex", 0);
      setIndex($t);
    } else {
      $t.parents(".col").find(".score1").each(function() {
        $(this).find("input").attr("tabindex", 1);
      });

      $t.parents(".col").find(".score2").each(function() {
        $(this).find("input").attr("tabindex", 2);
      });
    }
  }

  function inputResize(id, pad) {
    var valueCur = $(id).val();
    var $ff = $('<div></div>').text(valueCur).css({
      "font-family": $(id).css("font-family"),
      "font-size": $(id).css("font-size"),
      "display": "inline-block",
      "visibility": "collapse"
    });
    $('body').append($ff);
    var valueInitW = $ff.width() + 2 + pad;
    $ff.remove();
    if (valueInitW >= 20) {
      $(id).css('width', valueInitW);
    } else {
      $(id).width(20);
    }
  }

  function checkWidth() {
    if ($(window).width() < $(".col").first().width() * $(".col").length) {
      $(".bracket").addClass("scroll");
    } else {
      $(".bracket").removeClass("scroll");
    }
  }

});

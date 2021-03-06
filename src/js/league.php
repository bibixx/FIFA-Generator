<?php header('Content-Type: application/javascript'); ?>


$(document).ready(function() {
  $('.home, .away').each(function(index) {
    var $t = $(this);

    inputResize(this, 0);

    $t.on("focus input", function() {
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
    $(this).parent().find("input").each(function() {
      row.push($(this).val() * 1);
      if ($(this).val() === "") {
        empty = true;
      }
    });

    var token = "<?= $_GET["admin"]; ?>";
    var tournamentId = "<?= $_GET["id"]; ?>";

    if (!empty) {

      $.ajax({
          method: "POST",
          type: "html",
          url: "/save.php",
          data: {
            type: "Knockout",
            index: $(this).parents(".round").index() + "," + ($(this).parents(".row").index() - 1),
            value: [row[0] * 1, row[1] * 1],
            id: tournamentId,
            admin: token
          }
        })
        .fail(function(data) {
          console.log(data);
        });

    } else {
      $.ajax({
          method: "POST",
          type: "html",
          url: "/save.php",
          data: {
            type: "Knockout",
            index: $(this).parents(".round").index() + "," + ($(this).parents(".row").index() - 1),
            value: [],
            id: tournamentId,
            admin: token
          }
        })
        .fail(function(data) {
          console.log(data);
        });

    }

  });

  <?php
  }
?>

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

});

$(document).ready(function() {
  $(".disabled").hide().removeClass("disabled");

  $(".spinner input").on("change", function() {
    var str = $(this).val();
    var res = str.replace(/[^0-9]/g, "");
    if (res === "") {
      res = "0";
    }
    $(this).val(res * 1);
  });

  $(".spinner .plus").on("click", function() {
    var $p = $(this).parents(".spinner").find("input");
    $p.val($p.val() * 1 + 1);
    $p.trigger("change");
    $(this).blur();
  });

  $(".spinner .minus").on("click", function() {
    var $p = $(this).parents(".spinner").find("input");
    if ($p.val() > 1) {
      $p.val($p.val() * 1 - 1);
    }
    $p.trigger("change");
    $(this).blur();
  });

  $("#teamsEnabled").on("change", function() {
    var $tNo = $("#teamsNo");
    if ($(this).prop("checked")) {
      $tNo.parents(".form-horizontal").stop().slideDown(200);
      $tNo.val(Math.round($("#players").val() / 2)).trigger("change");
    } else {
      $tNo.parents(".form-horizontal").stop().slideUp(200);
      $tNo.trigger("change");
    }
  });

  $("#type").on("change", function() {
    var val = $(this).find(":selected").val();
    var $matchVsParents = $("#matchesvs").parents(".form-horizontal");
    var $legsParents = $("#legsKnockout").parents(".form-horizontal");
    if (val == "League") {
      $matchVsParents.stop().slideDown(200);
    } else {
      $matchVsParents.stop().slideUp(200);
    }

    if (val == "Knockout") {
      $legsParents.stop().slideDown(200);
    } else {
      $legsParents.stop().slideUp(200);
    }

  });

  $("#matchesvs").on("change", function() {
    if ($(this).val() > 2) {
      $(this).val(2);
    }
  });

  $("#players").on("change", function() {
    var $players = $(this);
    var $teamsNo = $("#teamsNo");
    var playersVal = $players.val();

    if ($players.val() < 2) {
      $players.val(2);
    }

    if ($players.val() > 32) {
      $players.val(32);
    }

    if ($("#teamsEnabled").prop("checked")) {
      if ($players.val() / $teamsNo.val() < 1) {
        $teamsNo.val($players.val());
      }

      if ($players.val() / $teamsNo.val() > 2) {
        $teamsNo.val(Math.round($players.val() / 2));
      }
    }
  });

  $("#teamsNo").on("change", function() {
    var $teamsNo = $(this);
    var $players = $("#players");

    if ($teamsNo.val() < 2) {
      $teamsNo.val(2);
    }

    if ($teamsNo.val() > 32) {
      $teamsNo.val(32);
    }

    if ($("#teamsEnabled").prop("checked")) {
      if ($players.val() > $teamsNo.val() * 2) {
        $players.val($teamsNo.val() * 2);
      }

      if ($players.val() / $teamsNo.val() < 1) {
        $players.val($teamsNo.val());
      }
    }
  });

  $("#players, #teamsNo, #matchesvs").on("change", function() {
    printTeams();
  });

  $("#length").on("change", function() {
    printTeams();
  });

  printTeams();

  function normalize(term) {
    var ret = "";
    for (var i = 0; i < term.length; i++) {
      ret += accentMap[term.charAt(i)] || term.charAt(i);
    }
    return ret;
  }

  function printTeams() {
    var tempPlayers = [];
    var tempClubs = [];
    var teamIndex = 0;
    var clubIndex = 0;

    $(".team").each(function() {
      var $name = $(this).find('[name*="name"]');
      var $club = $(this).find('[name*="club"]').val();
      var val0 = $name.eq(0).val();
      var val1 = $name.eq(1).val();

      tempPlayers.push(val0);
      if (typeof(val1) != "undefined") {
        tempPlayers.push(val1);
      }
      tempClubs.push($club);
    });


    var $players = $("#players");
    var $playersVal = $players.val();
    var $teamsNo = $("#teamsNo");
    var $teamsNoVal = $teamsNo.val();
    var $matchesvs = $("#matchesvs");
    var $matchesvsVal = $matchesvs.val();
    var $summary = $(".summary");
    var $summaryFix = $summary.find("#fix");
    var $teams = $(".teams");
    var $teamsEnabled = $("#teamsEnabled");
    var $cib = $(".center-inline-block");
    var players = distribute();

    $teams.find(".team").remove();

    if ($teamsEnabled.prop("checked")) {
      y = $teamsNoVal * 1 + 1;
      fixturesNo = $teamsNoVal * ($teamsNoVal - 1) / 2 * $matchesvsVal;
      $summaryFix.text(fixturesNo + " fixture" + ((fixturesNo > 1) ? "s" : ""));
    } else {
      y = $playersVal * 1 + 1;
      fixturesNo = $playersVal * ($playersVal - 1) / 2 * $matchesvsVal;
      $summaryFix.text(fixturesNo + " fixture" + ((fixturesNo > 1) ? "s" : ""));
    }

    var tournamentTime = $summaryFix.text().replace(/[^0-9]/g, "") * $("#length").find(":selected").val() * 2 / 60;

    $summary.find("#time").text((tournamentTime < 1) ? Math.floor(tournamentTime * 60 * 10) / 10 + " minutes" : (tournamentTime < 2) ? Math.floor(tournamentTime * 10) / 10 + " hour" : Math.floor(tournamentTime * 10) / 10 + " hours");
    $summary.find("#number").text($("#players").val());

    var noAgainst = $matchesvsVal * 1;
    $summary.find("#against").text((noAgainst == 1) ? "once" : "twice");

    for (x = 1; x < y; x++) {
      var $team = $("<div></div>").attr("id", x).addClass("team").addClass("panel").addClass("panel-primary");
      var $head = $("<div></div>").addClass("panel-heading");
      var $body = $("<div></div>").addClass("panel-body");
      var temp = 1;
      if (!$teamsEnabled.prop("checked") || players[x - 1] == 1) {
        input = $("<input>").attr({
          "type": "text",
          'name': "name" + teamIndex,
          "placeholder": "Player",
          "required": true,
          "autocomplete": "off"
        }).addClass("form-control").val(tempPlayers[teamIndex]);
        $head.append(input);
        teamIndex++;
      } else {
        for (i = 0; i < players[x - 1]; i++) {
          input = $("<input>").attr({
            "type": "text",
            'name': "name" + teamIndex,
            "placeholder": "Player " + (i + 1),
            "required": true,
            "autocomplete": "off"
          }).addClass("form-control").val(tempPlayers[teamIndex]);
          $head.append(input);
          teamIndex++;
        }
      }

      const $clubInput = $("<input>").attr({
        "type": "text",
        'name': "club" + clubIndex,
        "placeholder": "Club name",
        "autocomplete": "off"
      }).addClass("form-control").val(tempClubs[clubIndex]);

      $clubInput.autocomplete({
        source: function(request, response) {
          var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
          response($.grep(clubNames, function(value) {
            value = value.label || value.value || value;
            return matcher.test(value) || matcher.test(normalize(value));
          }));
        }
      })
      .each(function() {
        $(this).data("ui-autocomplete")._renderItem = function(ul, item) {
          return $("<li>")
            .append($("<a></a>").css({
              "display": "flex",
              "align-items": "center"
            }).append($("<div></div>").css({
              "width": "1em",
              "height": "1em",
              "background": "url(logos/16/" + item.value.replace("&#39;", "").toLowerCase().replace(/[^A-Za-z\s0-9\-]/g, "").replace(/\s/g, "-") + ".png) center / contain no-repeat",
              "display": "inline-block",
              "margin-right": "5px"
            })).append(item.value))
            .appendTo(ul);
        };
      });

      $body.append($clubInput);
      clubIndex++;

      $team.append($head).append($body);
      $cib.append($team);
    }
  }

  function distribute() {
    var items = [];
    var columns = [];
    var lengths = [];
    var its = $("#players").val() * 1;
    var cols = $("#teamsNo").val() * 1;
    for (x = 0; x < its; x++) {
      items.push([]);
    }
    for (x = 0; x < cols; x++) {
      columns.push([]);
    }

    for (x = 0; x < items.length; x++) {
      columns[Math.floor(x * columns.length / items.length)].push(items[x]);
    }

    for (x = 0; x < columns.length; x++) {
      lengths[x] = columns[x].length;
    }

    lengths.sort(function(a, b) {
      return b - a;
    });

    return lengths;
  }


  $("form").on("submit", function(e) {
    var temp = true;
    $(".teams input").each(function() {
      if ($(this).val() === "") {
        temp = false;
      }
    });
  });
});

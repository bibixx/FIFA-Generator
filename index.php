<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>FIFA Tournament generator</title>
  <link rel="stylesheet" href="css/style.css" media="screen" charset="utf-8">
</head>
<body>
  <div id="settings">
    <div class="row">
      <p>
        <label for="type">Tournament type:</label>
      </p>
      <select id="type">
        <option>------</option>
        <option>League</option>
        <option>Knockout</option>
        <option>League + Knockout</option>
      </select>
    </div>
    <div class="row">
      <p>
        <label for="players">Number of players:</label>
      </p><span class="spinner">
        <button class="minus">-</button>
        <input id="players" type="text" value="4">
        <button class="plus">+</button></span>
    </div>
    <div class="row">
      <p>
        <label for="preclubs">Play with prechosen clubs?</label>
      </p>
      <input type="checkbox" id="preclubs" checked>
    </div>
    <div class="row">
      <p>
        <label for="teams">Play in teams?</label>
      </p>
      <input type="checkbox" id="teams">
    </div>
    <div class="row disabled">
      <p>
        <label for="teamsNo">Number of teams:</label>
      </p><span class="spinner">
        <button class="minus">-</button>
        <input id="teamsNo" type="text" value="1">
        <button class="plus">+</button></span>
    </div>
    <div class="row disabled">
      <p>
        <label for="matchesvs">Number of matches against each team:</label>
      </p><span class="spinner">
        <button class="minus">-</button>
        <input id="matchesvs" type="text" value="1">
        <button class="plus">+</button></span>
    </div>
  </div>
  <div class="teams">
    <div id="1" class="team">
      <input type="text" name="name1" value="" placeholder="Player">
      <input type="text" name="club" value="" placeholder="Club name">
      <div class="bg"></div>
    </div>
    <div id="2" class="team">
      <input type="text" name="name1" value="" placeholder="Player">
      <input type="text" name="club" value="" placeholder="Club name">
      <div class="bg"></div>
    </div>
  </div>
  <button id="generate">Generate</button>
  <script src="js/jquery-1.12.3.min.js" charset="utf-8">   </script>
  <script src="js/script.js" charset="utf-8"></script>
</body>

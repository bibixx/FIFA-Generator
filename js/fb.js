
  window.fbAsyncInit = function() {
    FB.init({
        appId      : '1754723434769694',
        oauth      : true,
        cookie     : true,
        status     : true,
        xfbml      : true,
        version    : 'v2.5'
    });

    FB.getLoginStatus(function(response) {
      fallback(response);
    });

  };

function fb_login(){
  FB.login(function(response) {
    fallback(response);
  }, {
    scope: 'public_profile'
  });
}

function fb_logout(){
  FB.logout(function(response) {
    location.reload();
  });
}

function fallback(response){
  if (response.authResponse) {
      console.log('Welcome!  Fetching your information.... ');
      access_token = response.authResponse.accessToken;
      user_id = response.authResponse.userID;

      FB.api('/me?fields=id,first_name,picture', function(response) {
        console.log( response );

        $(".navbar-right > li > a").html('Logged in as '+response.first_name+'&nbsp;<span class="caret"></span>');
        $(".navbar-right > li > ul li a").eq(0).text("Your profile");
        $(".navbar-right > li > ul li a").eq(1).text("Log out").on("click", fb_logout);

        $.ajax({
          method: "POST",
          type: "html",
          url: "/FIFA-Generator/create-profile-fb.php",
          data: {fb_id: response.id }
        })
        .fail(function(data){
          console.log( data );
        });

      });

  } else {
    console.log('User cancelled login or did not fully authorize.');
  }
}

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

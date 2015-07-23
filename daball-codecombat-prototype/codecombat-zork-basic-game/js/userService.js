angular.module('UserServiceModule', ['GameConfigurationModule'])

  //this is the user service, where user data is obtained for the controller
  //from a remote web service
  //injects $http, and GameConfigurationModule->userServiceAPIUrl
  .service("UserService", function($http, userServiceAPIUrl){
    var svc = this;

    //requests login for user
    //calls onFinished(loggedIn (Boolean), err),
    //when finished, where loggedIn indicates login status and err indicates
    //error status
    svc.loginUser = function loginUser(userName, password, onFinished) {
      var requestData = {
        action: "login",
        userName: userName,
        password: password
      };
      $http.post(userServiceAPIUrl, requestData)
        .success(function (data, status, headers, config) {
          if (data.ok)
            onFinished(true, false); //server returned { ok: true }
          else
            onFinished(false, data.err); //server returned { err: "message" }
        })
        .error(function (data, status, headers, config) {
          onFinished(false, status); //server returned status code
        });
    };

    return svc;
  });

nightNeverEnd.controller('HomeCtrl', ['$scope', '$http', '$route', '$routeParams', '$location',
  function($scope, $http, $route, $routeParams, $location) {
    $scope.message = {};

    $scope.message.greeting = 'Hi!';
    $scope.message.name = 'Klas';

    $scope.findLocation = function() {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          // success
          console.log(position);
<<<<<<< HEAD:js/controllers/home.js
          userLocation = position;
          loginWithSpotify();
=======
          // send user to map path with coords
          $location.url('/map/' + position.coords.latitude + ',' + position.coords.longitude);
>>>>>>> a8825c53133b513144ff8ee477f5ceb4563a66f7:js/controllers/HomeCtrl.js
        },
        function () {
          // no location given
          console.log('No location!');
        });
    };

    $scope.loginWithSpotify = function() {

    };
  }]);

nightNeverEnd.controller('HomeCtrl', ['$scope', '$http', '$routeParams',
  function($scope, $http, $routeParams) {
    $scope.message = {};

    $scope.message.greeting = 'Hi!';
    $scope.message.name = 'Klas';

    $scope.findLocation = function() {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          // success
          console.log(position);
          userLocation = position;
          loginWithSpotify();
        },
        function () {
          console.log('No location!');
        });

    };

    $scope.loginWithSpotify = function() {

    };
  }]);

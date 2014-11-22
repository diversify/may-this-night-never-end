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
          // send user to map path with coords
          $location.url('/map/' + position.coords.latitude + ',' + position.coords.longitude);
        },
        function () {
          // no location given
          console.log('No location!');
        });
    };
  }]);
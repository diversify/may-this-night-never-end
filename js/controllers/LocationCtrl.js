nightNeverEnd.controller('LocationCtrl', ['$scope', '$route', '$routeParams', '$location',
  function($scope, $route, $routeParams, $location) {
    console.log($routeParams);

    $scope.findLocation = function() {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          // success
          console.log(position);
          // send user to map path with coords
          $location.path('/map/' + position.coords.latitude + ',' + position.coords.longitude + '/' + $routeParams.moods, true);
        },
        function () {
          // no location given
          console.log('No location!');
        });
    };
    $scope.findLocation();
  }]);

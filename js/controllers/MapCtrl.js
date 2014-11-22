nightNeverEnd.controller('MapCtrl', ['$scope', '$http', '$route', '$routeParams',
  function($scope, $http, $route, $routeParams) {
    $scope.location = $routeParams.coords.split(',');
    console.log($routeParams);

    $scope.map = {
      center: {
        latitude: $scope.location[0],
        longitude: $scope.location[1]
      },
      zoom: 12
    };
    $scope.options = {
      scrollwheel: true
    }
  }]);

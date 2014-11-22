nightNeverEnd.controller('HomeCtrl', ['$scope', '$http', '$routeParams',
  function($scope, $http, $routeParams) {
    $scope.message = {};

    $scope.message.greeting = 'Hi!';
    $scope.message.name = 'Klas';
  }]);

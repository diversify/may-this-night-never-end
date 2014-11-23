nightNeverEnd.controller('MapCtrl', ['$scope', '$http', '$route', '$routeParams', '$location',
  function($scope, $http, $route, $routeParams, $location) {
    var location = $routeParams.coords.split(',');
    var mood = $routeParams.mood;
    $scope.markers = [];

    var postData = {
      latitude: location[0],
      longitude: location[1],
      interest: mood
    };

    $http.jsonp(dataApi + 'v1/getVenues?latitude='+location[0]+'&longitude='+location[1]+'&interest='+mood+'&callback=JSON_CALLBACK')
      .success(function(data, status, headers, config) {
        if (data.status === 'success') {
          var index = 0;
          _(data.data).map(function(entry) {
            entry.location.latitude = entry.location.lat;
            entry.location.longitude = entry.location.lng;

            return entry;
          });

          $scope.markers = data.data;
        }
      })
      .error(function(data, status, headers, config) {
        console.log(data);
        console.log(headers);
        console.log(status);
        console.log(config);
        alert('Could not fetch venue data. Sorry!');
      });

    $scope.mapevents = {
      center_changed: function(handler) {
        $location.path('/map/' + handler.center.k + ',' + handler.center.B + ',' + handler.data.map.zoom + '/' + mood);
      }
    };

    $scope.map = {
      center: {
        latitude: location[0],
        longitude: location[1]
      },
      zoom: parseInt(location[2]) || 13
    };
    $scope.options = {
      scrollwheel: true
    }
  }]);

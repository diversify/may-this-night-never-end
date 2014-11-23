nightNeverEnd.controller('MapCtrl', ['$scope', '$http', '$route', '$routeParams',
  function($scope, $http, $route, $routeParams) {
    console.log($routeParams);
    var location = $routeParams.coords.split(',');
    var moods = $routeParams.mood.split(',');

    $scope.markers = [];

    var postData = {
      latitude: location[0],
      longitude: location[1],
      interest: moods[0]
    };

    $http.jsonp(dataApi + 'v1/getVenues?latitude='+location[0]+'&longitude='+location[1]+'&interest='+moods[0]+'&callback=JSON_CALLBACK')
      .success(function(data, status, headers, config) {
        if (data.status === 'success') {
          var index = 0;
          _(data.data).map(function(entry) {
            // console.log(entry);
            entry.location.latitude = entry.location.lat;
            entry.location.longitude = entry.location.lng;
            // entry.keyid = index++;

            return entry;
          });

          console.log(data);
          $scope.markers = data.data;
        }
      })
      .error(function(data, status, headers, config) {
        console.log(data);
        console.log(headers);
        console.log(status);
        console.log(config);
        alert('Could not fetch venue data. Sorry!');
      })

    $scope.map = {
      center: {
        latitude: location[0],
        longitude: location[1]
      },
      zoom: 12
    };
    $scope.options = {
      scrollwheel: true
    }
  }]);

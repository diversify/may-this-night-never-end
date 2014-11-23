/**
*  Module
*
* Description
*/
var nightNeverEnd = angular.module('nightNeverEnd', ['ngRoute', 'uiGmapgoogle-maps']);
var dataApi = 'http://104.131.83.96/';

// setup routing
nightNeverEnd.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/home', {
        templateUrl: 'views/home.html',
        controller: 'HomeCtrl'
      }).
      when('/map/:coords/:mood', {
        templateUrl: 'views/map.html',
        controller: 'MapCtrl'
      }).
      when('/moods/:moods', {
        templateUrl: 'views/location.html',
        controller: 'LocationCtrl'
      }).
      otherwise({
        redirectTo: '/home'
      });
  }]);

nightNeverEnd.config(function(uiGmapGoogleMapApiProvider) {
  uiGmapGoogleMapApiProvider.configure({
    key: 'AIzaSyAU281H5asrFXLAhm-Ma7nwoVRCRxICrf4',
    v: '3.17'
  });
})

// make sure all urls are not reloaded
nightNeverEnd.run(['$route', '$rootScope', '$location',
  function($route, $rootScope, $location) {
    var original = $location.path;
    $location.path = function(path, reload) {
      if (reload === false) {
        var lastRoute = $route.current;
        var un = $rootScope.$on('$locationChangeSuccess', function () {
          $route.current = lastRoute;
          un();
        });
      };
      return original.apply($location, [path]);
    };
  }])

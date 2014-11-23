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

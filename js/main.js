/**
*  Module
*
* Description
*/
var nightNeverEnd = angular.module('nightNeverEnd', ['ngRoute']);
var userLocation = {};

nightNeverEnd.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/home', {
        templateUrl: 'views/home.html',
        controller: 'HomeCtrl'
      }).
      otherwise({
        redirectTo: '/home'
      });
  }]);

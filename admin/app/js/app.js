'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', [
  'ngRoute',
  'myApp.filters',
  'myApp.services',
  'myApp.directives',
  'myApp.controllers',
  'ui.bootstrap'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/kurs', {templateUrl: 'partials/kurs.html', controller: 'Kurs'});
  $routeProvider.when('/kursdetaljer', {templateUrl: 'partials/kurs.html', controller: 'Kursdetaljer'});
  $routeProvider.when('/registreringer', {templateUrl: 'partials/kurs.html', controller: 'Registreringer'});
  $routeProvider.when('/view2', {templateUrl: 'partials/partial2.html', controller: 'MyCtrl2'});
  $routeProvider.otherwise({redirectTo: '/kurs'});
}]);

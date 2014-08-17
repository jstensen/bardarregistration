'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
	service('', function() {
	}).
	
  value('version', '0.1');

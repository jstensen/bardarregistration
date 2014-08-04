'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
	service('TestService', function($http) {
		//something here
		this.registrerParticipant = function(participant) {

			var test = {name: "4",
						description: "Testkurs fra Julianne",
						capacity: 6, 
						maxUnbalance: 2, 
						status: "Open"};

			$http.post("http://192.168.2.3/bardarregistration/addcourse.php", test);


			console.log("Hepp");
		};



	}).
	
  value('version', '0.1');

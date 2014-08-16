'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
	service('TestService', function($http) {
		//TODO: ta bort
		this.registrerParticipant = function(participant) {
			var url = "../../backend/modify/register.php";

			$rootScope.showfeedback = true;

			$http.post(url, participant).
				success(function(data){
					console.log("Registrering OK");




				}).
				error(function(){ 
				});


		};

	}).
	
  value('version', '0.1');

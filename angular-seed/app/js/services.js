'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
	service('TestService', function($http) {
		//something here
		this.registrerParticipant = function(participant) {
			var url = "../../backend/modify/register.php";

			$http.post(url, participant).
				success(function(data){
					console.log("Registrering OK");
				});


		};


		this.getCourses = function() {
			
			var getCoursesUrl = '../../backend/get/getcourses.php';

			$http.get(getCoursesUrl).success(function(data){
				console.log("testdata: "+data);
				return data;
			}).error();


			var testData = [{
					id: 1,
					name: "Lindy 1",
					description: "Dette er et kurs for deg som...."
				}, {
					id: 2,
					name: "Lindy 2",
					description: "Dette er et kurs for deg som...."
				}];
			return testData;			
		};

	}).
	
  value('version', '0.1');

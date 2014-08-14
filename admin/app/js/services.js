'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
	service('TestService', function($http) {
		//something here
		this.addCourse = function(course) {	
			var url = "../../backend/modify/addcourse.php";
			$http.post(url, course).
				success(function(data){
					console.log("Registrering OK");
				});


		};


		this.getCourses = function() {
			
			var getCoursesUrl = '../../backend/get/getcourses.php';

			$http.get(getCoursesUrl).
				success(function(data){
					console.log("Data: "+data);
					console.log(data.length);
					return data;
				})
				.error(function(data){console.log("Fy og fy, noko gjekk gale!");});			
		};

	}).
	
  value('version', '0.1');

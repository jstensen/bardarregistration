'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
	service('TestService', function($http) {
		//something here
		this.registrerParticipant = function(participant) {

			var test = {courseId: 1};


			//var participant = {name:"", address:"",+++ courses: [{courseId:"", hasPartner:true, partnerId:"", priority:"", role:""},{},{}];

			//var url = "http://192.168.2.5/bardarregistration/addcourse.php";	
			var url = "http://localhost/get/getregistrations.php";

			$http.post(url, participant).
				success(function(data){
					console.log("Registrering OK");
				});


		};


		this.getCourses = function() {
			
			var courses = [];

			var getCoursesUrl = '../../backend/get/getcourses.php';

			$http.get(getCoursesUrl).success(function(data){
				console.log("testdata: "+data);
				return data;
			}).error();


			var testData = [{
					courseId: 1,
					name: "Lindy 1",
					description: "Dette er et kurs for deg som...."
				}, {
					courseId: 2,
					name: "Lindy 2",
					description: "Dette er et kurs for deg som...."
				}];

			//return testData;

			//return courses;
		
			
		};

	}).
	
  value('version', '0.1');

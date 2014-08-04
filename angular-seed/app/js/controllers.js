'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', function($scope, TestService) {

$scope.test = "TesterString";

$scope.participant = {}; 

$scope.registrerParticipant = function(participant) {
	console.log(participant.name);
	console.log(participant.address);
	console.log(participant.email);
	console.log(participant.phonenumber);
	console.log(participant.gender);
	console.log(participant.dateofbirth);

	TestService.registrerParticipant();
};

  	

  }])
  .controller('MyCtrl2', ['$scope', function($scope) {

  }]);

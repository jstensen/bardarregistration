'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', function($scope, TestService) {

init();

$scope.numberOfCourses = 1;
$scope.hasPartner = {firstChoice: false, secondChoice: false, thirdChoice: false};
$scope.fillInPartnerId = {firstChoice: false, secondChoice: false, thirdChoice: false};

function init() {
	$scope.courses = TestService.getCourses();
	$scope.myFirstChoice = $scope.courses[0];
	console.log("$scope.myFirstChoice: "+$scope.myFirstChoice.name);


}



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

$scope.updateHasPartnerFirstChoice = function() {
	$scope.fillInPartnerId.firstChoice = $scope.hasPartner.firstChoice;
};

$scope.updateHasPartnerSecondChoice = function() {
	$scope.fillInPartnerId.secondChoice = $scope.hasPartner.secondChoice;
};

$scope.updateHasPartnerThirdChoice = function() {
	$scope.fillInPartnerId.thirdChoice = $scope.hasPartner.thirdChoice;
};

$scope.addOneMoreCourse = function() {
	console.log("inne her: "+$scope.numberOfCourses);
	$scope.numberOfCourses++;
	console.log("inne her: "+$scope.numberOfCourses);
};

  	

  }])
  .controller('MyCtrl2', ['$scope', function($scope) {

  }]);

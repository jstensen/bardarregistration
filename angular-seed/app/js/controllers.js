'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', function($scope, TestService) {

$scope.participant = {}; 
$scope.participant.gender = "Kvinne";

$scope.numberOfCourses = 1;
$scope.fillInPartnerId = {firstChoice: false, secondChoice: false, thirdChoice: false};

init();

function init() {
	$scope.courses = TestService.getCourses();
}


$scope.registrerParticipant = function(participant) {
	console.log(participant.name);
	console.log(participant.address);
	console.log(participant.email);
	console.log(participant.phonenumber);
	console.log(participant.gender);
	console.log(participant.dateofbirth);
	console.log($scope.firstChoiceCourse.role);


	console.log("$scope.firstChoiceCourse.courseId: "+$scope.firstChoiceCourse.courseId);
	console.log("$scope.firstChoiceCourse.hasPartner: "+$scope.firstChoiceCourse.hasPartner);
	console.log("$scope.firstChoiceCourse.partnerName: "+$scope.firstChoiceCourse.partnerName);


	$scope.participant.courses = [];
	//var participantChoiceOfCourses = [{courseId: XXX, priority: XXX, hasPartner: XXX, partnerName: XXX, role: XXX, },{},{}];

	$scope.participant.courses[0] = $scope.firstChoiceCourse;
//	if($scope.secondChoiceCourse.courseId !=== null) {
//		$scope.participant.courses[1] = $scope.secondChoiceCourse;
//	}
//	if($scope.thirdChoiceCourse.courseId !=== null) {
//		$scope.participant.courses[2] = $scope.thirdChoiceCourse;
//	}

	TestService.registrerParticipant($scope.participant);
};

$scope.updateHasPartnerFirstChoice = function() {
	$scope.fillInPartnerId.firstChoice = $scope.firstChoiceCourse.hasPartner;
};

$scope.updateHasPartnerSecondChoice = function() {
	$scope.fillInPartnerId.secondChoice = $scope.secondChoiceCourse.hasPartner;
};

$scope.updateHasPartnerThirdChoice = function() {
	$scope.fillInPartnerId.thirdChoice = $scope.thirdChoiceCourse.hasPartner;
};

$scope.addOneMoreCourse = function() {
	$scope.numberOfCourses++;
};

  	

  }])
  .controller('MyCtrl2', ['$scope', function($scope) {

  }]);

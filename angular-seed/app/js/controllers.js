'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', function($scope, TestService) {

$scope.participant = {}; 



$scope.numberOfCourses = 1;
$scope.hasPartner = {firstChoice: false, secondChoice: false, thirdChoice: false};
$scope.fillInPartnerId = {firstChoice: false, secondChoice: false, thirdChoice: false};


init();




function init() {
	$scope.courses = TestService.getCourses();
	$scope.myFirstChoice = $scope.courses[0];
	$scope.mySecondChoice = $scope.courses[0];
	$scope.myThirdChoice = $scope.courses[0];



	$scope.firstChoiceCourse = {courseId: $scope.courses[0].courseId, priority: 1, hasPartner: false};
//	$scope.participant.firstChoiceCourse = $scope.courses[0];



	$scope.secondChoiceCourse = {courseId: null, priority: 2, role:"", hasPartner: "", partnerName:""};
}


$scope.registrerParticipant = function(participant) {
	console.log(participant.name);
	console.log(participant.address);
	console.log(participant.email);
	console.log(participant.phonenumber);
	console.log(participant.gender);
	console.log(participant.dateofbirth);


	console.log("$scope.firstChoiceCourse.courseId: "+$scope.firstChoiceCourse.courseId);
	console.log("$scope.firstChoiceCourse.hasPartner: "+$scope.firstChoiceCourse.hasPartner);
	console.log("$scope.firstChoiceCourse.partnerName: "+$scope.firstChoiceCourse.partnerName);


	$scope.participant.courses = {};
	//var participantChoiceOfCourses = [{courseId: XXX, priority: XXX, hasPartner: XXX, partnerName: XXX, role: XXX, },{},{}];

	$scope.participant.courses[0] = $scope.firstChoiceCourse;
//	if($scope.secondChoiceCourse.courseId !=== null) {
//		$scope.participant.courses[1] = $scope.secondChoiceCourse;
//	}
//	if($scope.thirdChoiceCourse.courseId !=== null) {
//		$scope.participant.courses[2] = $scope.thirdChoiceCourse;
//	}

	TestService.registrerParticipant();
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
	console.log("inne her: "+$scope.numberOfCourses);
	$scope.numberOfCourses++;
	console.log("inne her: "+$scope.numberOfCourses);
};

  	

  }])
  .controller('MyCtrl2', ['$scope', function($scope) {

  }]);

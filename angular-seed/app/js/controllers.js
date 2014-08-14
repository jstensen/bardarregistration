'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', '$http', function($scope, TestService, $http) {

$scope.participant = {}; 
$scope.participant.gender = "Kvinne";

$scope.numberOfCourses = 1;
$scope.fillInPartnerId = {firstChoice: false, secondChoice: false, thirdChoice: false};


//var testData = [{
//					id: 1,
//					name: "Lindy 1",
//					description: "Dette er et kurs for deg som...."
//				}, {
//					id: 2,
//					name: "Lindy 2",
//					description: "Dette er et kurs for deg som...."
//				}];

//$scope.courses = testData;

var getCoursesUrl = '../../backend/get/getcourses.php';

$http.get(getCoursesUrl).success(function(data){
	$scope.courses = data;

	$scope.firstChoiceCourse = {courseId: $scope.courses[0].id, priority:"1", role:"Follow", partnerName:"", hasPartner: false};
	$scope.secondChoiceCourse = {courseId: $scope.courses[0].id, priority:"2", role:"Follow", partnerName:"", hasPartner: false};
	$scope.thirdChoiceCourse = {courseId: $scope.courses[0].id, priority:"3", role:"Follow", partnerName:"", hasPartner: false};
}).error();


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

	$scope.participant.courses[0] = $scope.firstChoiceCourse;
	$scope.participant.courses[1] = $scope.secondChoiceCourse;
	$scope.participant.courses[2] = $scope.thirdChoiceCourse;

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

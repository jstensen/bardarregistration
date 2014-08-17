'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', '$http', function($scope, TestService, $http) {

$scope.showfeedback = false;
$scope.participant = {}; 
$scope.participant.gender = "Kvinne";

$scope.numberOfCourses = 1;
$scope.fillInPartnerId = [false, false, false];

$scope.maxNumberOfCourses = 3;

var getCoursesUrl = '../backend/get/getcourses.php';

$http.get(getCoursesUrl).success(function(data){
	$scope.courses = data;
	
	$scope.Course = [];
	for (var $j = 0; $j < $scope.maxNumberOfCourses; $j++) { 
		$scope.Course[$j] = {courseId: $scope.courses[0].id, priority:$j+1, role:"Follow", partnerName:"", hasPartner: false};
	}

}).error();


$scope.registrerParticipant = function(participant) {

	$scope.participant.courses = [];
	for(var i=0; i<$scope.numberOfCourses; i++){
		$scope.participant.courses[i] = $scope.Course[i];
	}

	var url = "../backend/modify/register.php";


	$http.post(url, participant).
		success(function(data){
			$scope.showfeedback = true;
			$scope.feedback = data;
			$scope.enFeilHarSkjedd = false;

		}).
		error(function(data){
			$scope.showfeedback = true;
			$scope.feedback = data;
			$scope.enFeilHarSkjedd = true;
	});


	//TestService.registrerParticipant($scope.participant);
};

$scope.updateHasPartner = function(i) {
	$scope.fillInPartnerId[i] = $scope.Course[i].hasPartner;
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
$scope.removeOneCourse = function() {
	$scope.numberOfCourses--;
};

  	

  }])
  .controller('MyCtrl2', ['$scope', function($scope) {

  }]);

'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Registrer', ['$scope', 'TestService', '$http', function($scope, TestService, $http) {

$scope.participant = {}; 
$scope.participant.gender = "Kvinne";

$scope.numberOfCourses = 1;
$scope.fillInPartnerId = [false, false, false];

$scope.maxNumberOfCourses = 3;


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
	
	$scope.Course = [];
	for (var $j = 0; $j < $scope.maxNumberOfCourses; $j++) { 
		$scope.Course[$j] = {courseId: $scope.courses[0].id, priority:$j+1, role:"Follow", partnerName:"", hasPartner: false};
	}
	//$scope.Course[0] = {courseId: $scope.courses[0].id, priority:"1", role:"Follow", partnerName:"", hasPartner: false};
	//$scope.Course[1] = {courseId: $scope.courses[0].id, priority:"2", role:"Follow", partnerName:"", hasPartner: false};
	//$scope.Course[2] = {courseId: $scope.courses[0].id, priority:"3", role:"Follow", partnerName:"", hasPartner: false};
}).error();


//init();

//function init() {
//	$scope.courses = TestService.getCourses();
//}


$scope.registrerParticipant = function(participant) {
	console.log(participant.name);
	console.log(participant.address);
	console.log(participant.email);
	console.log(participant.phonenumber);
	console.log(participant.gender);
	console.log(participant.dateofbirth);

	$scope.participant.courses = [];
	for(var i=0; i<$scope.numberOfCourses; i++){
		console.log("$scope.Course[i].courseId: "+$scope.Course[i].courseId);
		$scope.participant.courses[i] = $scope.Course[i];
	}

	TestService.registrerParticipant($scope.participant);
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

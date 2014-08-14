'use strict';

/* Controllers */

angular.module('myApp.controllers', [])
  .controller('Kurs', ['$scope', 'TestService', '$http', function($scope, TestService, $http) {

var getCoursesUrl = '../../backend/get/getcourses.php';

$http.get(getCoursesUrl).
	success(function(data){
		console.log("testdata: "+data);
		console.log(data.length);
		$scope.courses=data;
	})
	.error(function(data){console.log("Fy og fy, noko gjekk gale!");});

$scope.manage = function(id){
	console.log(id);

			$http.get('../../backend/get/getcourses.php').
				success(function(data){
					console.log("Data: "+data);
					console.log(data.length);
					return data;
				})
				.error(function(data){console.log("Fy og fy, noko gjekk gale!");});		
}
$scope.edit = function(id){
	console.log(id);
}
$scope.deletecourse = function(id){
	console.log(id);
}

$scope.addcourse = function(course) {
	console.log(course.name);
	console.log(course.description);
	console.log(course.capacity);
	console.log(course.maxunbalance);
	console.log(course.status);
	console.log(course.solo);


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

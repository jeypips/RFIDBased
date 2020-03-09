var app = angular.module('students',['account-module','app-module']);

app.controller('studentsCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

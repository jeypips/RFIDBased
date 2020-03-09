var app = angular.module('courses',['account-module','app-module']);

app.controller('coursesCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

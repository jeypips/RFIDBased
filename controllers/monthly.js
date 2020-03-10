var app = angular.module('monthly',['account-module','app-module']);

app.controller('monthlyCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

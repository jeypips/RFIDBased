var app = angular.module('dashboard',['account-module','app-module']);

app.controller('dashboardCtrl',function($scope,app) {
	
	$scope.app = app;
	
	app.data($scope);
	app.list($scope);
	
});
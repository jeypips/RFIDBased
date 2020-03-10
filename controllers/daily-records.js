var app = angular.module('daily',['account-module','app-module']);

app.controller('dailyCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

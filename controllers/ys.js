var app = angular.module('ys',['account-module','app-module']);

app.controller('ysCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

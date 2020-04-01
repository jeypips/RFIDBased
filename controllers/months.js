var app = angular.module('reports',['account-module','app-module']);

app.controller('reportsCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

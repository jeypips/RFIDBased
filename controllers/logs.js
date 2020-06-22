var app = angular.module('logs',['account-module','app-module']);

app.controller('logsCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	
});

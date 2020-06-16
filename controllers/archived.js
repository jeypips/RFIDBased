var app = angular.module('archived',['account-module','app-module']);

app.controller('archivedCtrl',function($scope,app) {
	
	$scope.app = app;

	app.data($scope);
	app.list($scope);
	
});

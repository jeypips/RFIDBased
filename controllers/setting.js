var app = angular.module('setting',['account-module','app-module']);

app.controller('settingCtrl',function($scope,app) {
	
	$scope.views = {};
	$scope.app = app;

	app.data($scope);
	app.view($scope);

});

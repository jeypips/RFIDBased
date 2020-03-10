angular.module('app-module',['form-validator','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,validate) {
	
	function app() {
	
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			$http({
				method: 'POST',
				url: 'handlers/daily/data.php'
			}).then(function mySucces(response) {
				
				scope.logs = response.data;
				
				console.log(scope.logs);
				
			},function myError(response) {
					
			});
			
			console.log(scope);
			
		};
		
		self.list = function (scope){
			
			$http({
				method: 'POST',
				url: 'handlers/daily/list.php'
			}).then(function mySucces(response) {
				
				scope.courses = response.data;
				
			},function myError(response) {
					
			});	
			
		};
		

	};
	
	return new app();
	
});
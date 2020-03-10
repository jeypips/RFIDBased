angular.module('app-module',['my-pagination','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,myPagination) {
	
	function app() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			$http({
			  method: 'POST',
			  url: 'handlers/dashboard/dashboard.php'		  
			}).then(function mySucces(response) {
			
				scope.dashboard = angular.copy(response.data);
				
			}, function myError(response) {

			});
		};
		
	};
	
	return new app();
	
});
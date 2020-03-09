angular.module('app-module',['my-pagination','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,myPagination) {
	
	function app() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			bui.show();
			bui.hide();
			
		};
		
	};
	
	return new app();
	
});
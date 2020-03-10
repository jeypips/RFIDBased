angular.module('app-module',['form-validator','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,validate) {
	
	function app() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			scope.formHolder = {};
			
			scope.views = {};

			scope.controls = {
				ok: {btn: true, label: 'Save'},
				cancel: {btn: false, label: 'Cancel'},
				add: {btn: false, label: 'New'},
				icon: {label: 'fa-eye'}
			};

			scope.user = {};
			scope.user.user_id = 0;
			
			scope.users = []; // list
		
		};
		
		
		self.view = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/setting/view.php'
			}).then(function mySuccess(response) {
				
				angular.copy(response.data, scope.user);
				
			}, function myError(response) {
				
				// error
				
			});	
			
		};
		
		function mode(scope,row) {
			
			if (row == null) {
				scope.controls.ok.label = 'Save';
				scope.controls.ok.btn = false;
				scope.controls.cancel.label = 'Cancel';
				scope.controls.cancel.btn = false;
				scope.controls.add.btn = true;
			} else {
				scope.controls.ok.label = 'Update';
				scope.controls.ok.btn = true;
				scope.controls.cancel.label = 'Close';
				scope.controls.cancel.btn = false;				
				scope.controls.add.label = 'Edit';				
			}
			
		};
		
		self.save = function(scope) {
			
			if (validate.form(scope,'user')){ 
				growl.show('alert alert-danger',{from: 'top', amount: 55},'Some fields are required.');
				return;
			}
			
			$http({
			  method: 'POST',
			  url: 'handlers/setting/save.php',
			  data: scope.user
			}).then(function mySuccess(response) {

				growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully updated.');
				
				scope.controls.ok.btn = true;
				
			}, function myError(response) {
				
				// error
				
			});	
			
		};
		
		self.edit = function(scope) {
			
			scope.controls.ok.btn = !scope.controls.ok.btn;
			
		};
		
		// additional //

		// show password
		self.inputType = 'password';
	  
		self.hideShowPassword = function(scope){
			if (self.inputType == 'password'){
				
				self.inputType = 'text';
				scope.controls.icon.label = 'fa-eye-slash';
				
			}else{
				
			  self.inputType = 'password';
			  scope.controls.icon.label = 'fa-eye';
			  
			};	
		
		};
		

	};
	
	return new app();
	
});
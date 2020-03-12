angular.module('app-module',['form-validator','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,validate) {
	
	function app() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			scope.formHolder = {};

			scope.controls = {
				ok: {btn: false, label: 'Save'},
				cancel: {btn: false, label: 'Cancel'},
				add: {btn: false, label: 'New'},
				edit: {btn: false, label: 'Edit'},
				icon: {label: 'fa-eye'}
			};

			scope.user = {};
			scope.user.user_id = 0;
			
			scope.users = []; // list
			
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
		
		self.list = function(scope) {
			
			bui.show();
			
			scope.controls.add.btn = false;
			scope.controls.edit.label = "Edit";
			
			if (scope.$id > 2) scope = scope.$parent;

			$http({
			  method: 'POST',
			  url: 'handlers/users/list.php',
			  data: scope.users
			}).then(function mySucces(response) {
				
				scope.users = angular.copy(response.data);
				
				bui.hide();
				
			}, function myError(response) {
				 
				bui.hide();
				
			});
			
			$('#content').load('lists/users.html', function() {
				$timeout(function() { $compile($('#content')[0])(scope); },100);								
				// instantiate datable
				$timeout(function() {
					$('#usersTable').DataTable({
					  "paging": true,
					  "lengthChange": true,
					  "searching": true,
					  "ordering": true,
					  "info": true,
					  "autoWidth": true,
					});
				},200);

			});	
			
		};
		
		// addEdit
		self.user = function(scope,row) {
			
			scope.user = {};
			scope.user.user_id = 0;
			
			mode(scope,row);
			
			$('#content').load('forms/user.html',function() {
				$timeout(function() { $compile($('#content')[0])(scope); },200);
			});
			
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;				
				$http({
				  method: 'POST',
				  url: 'handlers/users/view.php',
				  data: {user_id: row.user_id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.user);
					
					mode(scope,row);
					
				}, function myError(response) {
					
				  // error
				  
				});
				
			}; 
			
		};
		
		self.save = function(scope) {
			
			if (validate.form(scope,'user')){ 
				growl.show('alert alert-danger',{from: 'top', amount: 55},'Some fields are required.');
				return;
			}
			
			$http({
			  method: 'POST',
			  url: 'handlers/users/save.php',
			  data: scope.user
			}).then(function mySuccess(response) {
				
				if(scope.user.user_id==0){
					scope.user.user_id = response.data;
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully added.');
				} else{
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully updated.');
				};
				
				mode(scope,scope.user);
				
			}, function myError(response) {
				
				// error
				
			});	
			
		};
		
		self.edit = function(scope) {
	
			scope.controls.ok.btn = !scope.controls.ok.btn;
			
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/users/delete.php',
				  data: {user_id: [row.user_id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
					growl.show('alert alert-success',{from: 'top', amount: 55},'Successfully deleted.');
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

		bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});
			
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
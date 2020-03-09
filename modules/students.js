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
			};

			scope.student = {};
			scope.student.id = 0;
			
			scope.students = []; // list
			
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
			}
			
		};

		self.list = function(scope) {
			
			bui.show();
			
			scope.controls.add.btn = false;
			scope.controls.edit.label = "Edit";

			if (scope.$id > 2) scope = scope.$parent;

			$http({
			  method: 'POST',
			  url: 'handlers/students/list.php',
			  data: scope.students
			}).then(function mySucces(response) {
				
				scope.students = angular.copy(response.data);
				
				bui.hide();
				
			}, function myError(response) {
				 
				bui.hide();
				
			});
			
			$('#content').load('lists/students.html', function() {
				$timeout(function() { $compile($('#content')[0])(scope); },100);								
				// instantiate datable
				$timeout(function() {
					$('#studentsTable').DataTable({
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
		
		self.student = function(scope,row) {
			
			scope.student = {};
			scope.student.id = 0;
			
			mode(scope,row);

			$('#content').load('forms/student.html',function() {
				$timeout(function() { $compile($('#content')[0])(scope); },200);
			});
			
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;				
				$http({
				  method: 'POST',
				  url: 'handlers/students/view.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.student);
					
					mode(scope,row);

				}, function myError(response) {
					
				  // error
				  
				});
					
			};
			
		};
		
		self.save = function(scope) {
			
			if (validate.form(scope,'student')){ 
				growl.show('alert alert-danger',{from: 'top', amount: 55},'Some fields are required.');
				return;
			}
			
			$http({
			  method: 'POST',
			  url: 'handlers/students/save.php',
			  data: scope.student
			}).then(function mySuccess(response) {

				if(scope.student.id==0){
					scope.student.id = response.data;
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully added.');
				} else{
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully updated.');
				};
				scope.controls.ok.btn = true;
				
			}, function myError(response) {
				
				// error
				
			});	
			
		};
		
		self.edit = function(scope) {
			
			scope.controls.ok.btn = !scope.controls.ok.btn;
			
			if(scope.controls.edit.label=="Edit") {
				
				scope.controls.edit.label="Disable";
				
			} else{
				
				scope.controls.edit.label="Edit";
				
			};
			
		};
		
		self.delete = function(scope,row) {

			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/students/delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
					growl.show('alert alert-success',{from: 'top', amount: 55},'Successfully deleted.');
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});
			
		};

	};
	
	return new app();
	
});
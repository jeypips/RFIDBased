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

			scope.course = {};
			scope.course.cour_id = 0;
			
			scope.courses = []; // list
			
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
			  url: 'handlers/courses/list.php',
			  data: scope.courses
			}).then(function mySucces(response) {
				
				scope.courses = angular.copy(response.data);
				
				bui.hide();
				
			}, function myError(response) {
				 
				bui.hide();
				
			});
			
			$('#content').load('lists/courses.html', function() {
				$timeout(function() { $compile($('#content')[0])(scope); },100);								
				// instantiate datable
				$timeout(function() {
					$('#coursesTable').DataTable({
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
		self.course = function(scope,row) {
		
			var title = "New";
			
			scope.course = {};
			scope.course.cour_id = 0;
			
			mode(scope,row);
			
			if (row != null) {
				
				var title = "Edit"
				
				if (scope.$id > 2) scope = scope.$parent;				
				
				$http({
				  method: 'POST',
				  url: 'handlers/courses/view.php',
				  data: {cour_id: row.cour_id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.course);

				}, function myError(response) {
					 
				  // error
					
				});
				
			};
			
			var onOk = function() {
				
				self.save(scope);
			
			};
			
			bootstrapModal.box(scope,title,'dialogs/course.html',onOk);
			
		};
		
		self.save = function(scope) {
			
			if (validate.form(scope,'course')){ 
				growl.show('alert alert-danger',{from: 'top', amount: 55},'Some fields are required.');
				return;
			}
			
			$http({
			  method: 'POST',
			  url: 'handlers/courses/save.php',
			  data: scope.course
			}).then(function mySuccess(response) {
				
				if(scope.course.cour_id==0){
					scope.course.cour_id = response.data;
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully added.');
				} else{
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully updated.');
				};
				
				self.list(scope);
				
				scope.controls.ok.btn = true;
				
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
				  url: 'handlers/courses/delete.php',
				  data: {cour_id: [row.cour_id]}
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
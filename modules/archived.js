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

			scope.student = {};
			scope.student.stud_id = 0;
			
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
			  url: 'handlers/archived/list.php',
			  data: scope.students
			}).then(function mySucces(response) {
				
				scope.students = angular.copy(response.data);
				
				bui.hide();
				
			}, function myError(response) {
				 
				bui.hide();
				
			});
			
			$('#content').load('lists/archived.html', function() {
				$timeout(function() { $compile($('#content')[0])(scope); },100);								
				// instantiate datable
				$timeout(function() {
					$('#archivedTable').DataTable({
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
		self.student = function(scope,row) {
			
			scope.student = {};
			scope.student.stud_id = 0;
			
			scope.load = {};
			scope.load.photo = "pictures/avatar.png";
			
			mode(scope,row);
			
			console.log(scope);

			$('#content').load('forms/archived.html',function() {
				$timeout(function() { $compile($('#content')[0])(scope); },200);
			});
			
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;				
				$http({
				  method: 'POST',
				  url: 'handlers/archived/view.php',
				  data: {stud_id: row.stud_id}
				}).then(function mySucces(response) {
					
					scope.student = angular.copy(response.data);
					
					scope.sections = response.data.stud_year_id.sections;

					load(scope);
					
					mode(scope,row);

				}, function myError(response) {
					
				  // error
				  
				});
					
			};
			
			// suggestions
			courses(scope);
			
		};
		
		function courses(scope) {
			
			$http({
				method: 'POST',
				url: 'api/suggestions/courses.php'
			}).then(function mySucces(response) {
				
				scope.courses = response.data;
				
			},function myError(response) {
				//error
			});
			
		};
		
		function load(scope){			

			$http({
			  method: 'POST',
			  url: 'handlers/students/load.php',
			  data: {stud_id: scope.student.stud_id}
			}).then(function mySucces(response) {

				scope.load = angular.copy(response.data);

			}, function myError(response) {

			});
			
		};
		
	};
	
	return new app();
	
});
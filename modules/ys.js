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

			scope.year = {};
			scope.year.year_id = 0;
			
			scope.year.section = [];
			scope.year.dels = [];
			
			scope.years = []; // list
			
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
			  url: 'handlers/ys/list.php',
			  data: scope.years
			}).then(function mySucces(response) {
				
				scope.years = angular.copy(response.data);
				
				bui.hide();
				
			}, function myError(response) {
				 
				bui.hide();
				
			});
			
			$('#content').load('lists/years.html', function() {
				$timeout(function() { $compile($('#content')[0])(scope); },100);								
				// instantiate datable
				$timeout(function() {
					$('#yearsTable').DataTable({
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
		self.year = function(scope,row) {
			
			scope.year = {};
			scope.year.year_id = 0;
			
			scope.year.section = [];
			scope.year.dels = [];
			
			mode(scope,row);
			
			$('#content').load('forms/year.html',function() {
				$timeout(function() { $compile($('#content')[0])(scope); },200);
			});
			
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;				
				$http({
				  method: 'POST',
				  url: 'handlers/ys/view.php',
				  data: {year_id: row.year_id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.year);
					
					mode(scope,row);
					
				}, function myError(response) {
					
				  // error
				  
				});
				
			}; 
			
		};
		
		self.save = function(scope) {
			
			if (validate.form(scope,'year')){ 
				growl.show('alert alert-danger',{from: 'top', amount: 55},'Some fields are required.');
				return;
			}
			
			$http({
			  method: 'POST',
			  url: 'handlers/ys/save.php',
			  data: scope.year
			}).then(function mySuccess(response) {
				
				if(scope.year.year_id==0){
					scope.year.year_id = response.data;
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
			
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/ys/delete.php',
				  data: {year_id: [row.year_id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
					growl.show('alert alert-success',{from: 'top', amount: 55},'Successfully deleted.');
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});
			
		};
		
		self.section = {
			
			add: function(scope) {

				scope.year.section.push({
					sect_id: 0,
					f_year_id: 0,
					sect_description: ""
				});

			},
			
			edit: function(scope,row) {
				
				row.disabled = !row.disabled;				
				
			},			
			
			delete: function(scope,row) {
				
				if (row.sect_id > 0) {
					scope.year.dels.push(row.sect_id);
				};
				
				var sections = scope.year.section;
				var index = scope.year.section.indexOf(row);
				scope.year.section = [];			
				
				angular.forEach(sections, function(d,i) {
					
					if (index != i) {
						
						delete d['$$hashKey'];
						scope.year.section.push(d);
						
					};
					
				});

			}			
			
		};
		
	};
	
	return new app();
	
});
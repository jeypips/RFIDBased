angular.module('app-module',['form-validator','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,validate,imageLoad,fileUpload) {
	
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
			scope.student.stud_id = 0;
			
			scope.students = []; // list
			
			$http({
				method: 'POST',
				url: 'api/suggestions/ys.php'
			}).then(function mySucces(response) {
				
				scope.years = response.data;
				scope.sections = [];	
				
			},function myError(response) {
					
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
		
		function courses(scope) {
			
			$http({
			  method: 'POST',
			  url: 'api/suggestions/courses.php'
			}).then(function mySuccess(response) {
				
				scope.courses = angular.copy(response.data);
				
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
			scope.student.stud_id = 0;
			
			scope.load = {};
			scope.load.photo = "pictures/avatar.png";
			
			mode(scope,row);

			$('#content').load('forms/student.html',function() {
				$timeout(function() { $compile($('#content')[0])(scope); },200);
			});
			
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;				
				$http({
				  method: 'POST',
				  url: 'handlers/students/view.php',
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
		
		self.checkYear = function(scope,stud_year_id) {
			
			scope.sections = scope.student.stud_year_id.sections;
			
			$http({
			  method: 'POST',
			  url: 'handlers/students/check-year.php',
			  data: stud_year_id
			}).then(function mySucces(response) {
				
			}, function myError(response) {
				
			});
			
		};
		
		self.change = function(scope,row) {
		
			var title = "Edit"
			
			if (scope.$id > 2) scope = scope.$parent;				
			
			$http({
			  method: 'POST',
			  url: 'handlers/students/view.php',
			  data: {stud_id: row.stud_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.student);

			}, function myError(response) {
				 
			  // error
				
			});
				
			
			var onOk = function() {

				$http({
				  method: 'POST',
				  url: 'handlers/students/save.php',
				  data: scope.student
				}).then(function mySuccess(response) {

					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully updated.');
					
					self.list(scope);
					
				}, function myError(response) {
					
					// error
					
				});	
			
			};
			
			bootstrapModal.box(scope,title,'dialogs/student_change_id.html',onOk);
			
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

				if(scope.student.stud_id==0){
					scope.student.stud_id = response.data;
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully added.');
				} else{
					growl.show('alert alert-success no-border mb-2',{from: 'top', amount: 55},'Successfully updated.');
				};
				
				$timeout(function() {
					self.uploadProfilePicture(scope);
				},200);
				
				$timeout(function() {
					load(scope);
				},500);
				
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
				  data: {stud_id: [row.stud_id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
					growl.show('alert alert-success',{from: 'top', amount: 55},'Successfully deleted.');
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});
			
		};
		
		self.uploadProfilePicture = function(scope) {

		   // scope.proPic = null;
		   var file = scope.views.proPic;
		   
		   if (file == undefined) return;
		   
		   var pp = file['name'];
		   var en = pp.substring(pp.indexOf("."),pp.length);

		   var uploadUrl = "handlers/students/files.php?r=upload_profile_picture&id="+scope.student.stud_id+"&en="+en;
		   fileUpload.uploadFileToUrl(file, uploadUrl, scope);
		   
		};

	};
	
	return new app();
	
}).directive('fileModel', ['$parse', function ($parse) {
	return {
	   restrict: 'A',
	   link: function(scope, element, attrs) {
		  var model = $parse(attrs.fileModel);
		  var modelSetter = model.assign;
		  
		  element.bind('change', function() {
			 scope.$apply(function(){
				modelSetter(scope, element[0].files[0]);
			 });
		  });

	   }
	};
}]).service('fileUpload', ['$http', function ($http,imageLoad) {
	
	this.uploadFileToUrl = function(file, uploadUrl, scope) {
		
	   var fd = new FormData();
	   fd.append('file', file);

        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", uploadProgress, false);
        xhr.addEventListener("load", uploadComplete, false);
        xhr.open("POST", uploadUrl);
        scope.progressVisible = true;
        xhr.send(fd);
	   
		// upload progress
		function uploadProgress(evt) {
			scope.views.showProPicUploadProgress = true;
			scope.$apply(function(){
				scope.views.progress = 0;			
				if (evt.lengthComputable) {
					scope.views.progress = Math.round(evt.loaded * 100 / evt.total);
				} else {
					scope.views.progress = 'unable to compute';
					scope.views.profilePicture = "pictures/avatar.png";					
				}
			});
		}

		function uploadComplete(evt) {
			/* This event is raised when the server send back a response */
			scope.$apply(function() {
				
				scope.views.profilePicture = 'pictures/'+scope.student.stud_id+'.jpg';
				scope.views.showProPicUploadProgress = false;

			});			

			$('#proPic').val(null);
		
		}

	}
	
}]).service('imageLoad', function() {
	
	this.go = function(scope,stud_id) {
		
		$.ajax({
			type: 'GET',
			url: 'pictures/'+stud_id+'.jpg',
			success: function (data) {

				scope.views.profilePicture = 'pictures/'+stud_id+'.jpg';
				console.log('Image is jpg');
				console.log(scope.views.profilePicture);

			},
			error: function (data) {

				scope.views.profilePicture = 'pictures/'+stud_id+'.png';
				console.log('Image is png');

			}
		});				
		
	};	
	
});
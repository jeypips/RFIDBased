angular.module('app-module',['form-validator','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,validate) {
	
	function app() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			scope.formHolder = {};

			scope.controls = {
				ok: {btn: false, label: 'Save'},
				cancel: {btn: false, label: 'Cancel'}
			};

			scope.data = []; // list
			
			var d = new Date();

			scope.months = [
				{month:"-",description:"All"},
				{month:"01",description:"January"},
				{month:"02",description:"February"},
				{month:"03",description:"March"},
				{month:"04",description:"April"},
				{month:"05",description:"May"},
				{month:"06",description:"June"},
				{month:"07",description:"July"},
				{month:"08",description:"August"},
				{month:"09",description:"September"},
				{month:"10",description:"October"},
				{month:"11",description:"November"},
				{month:"12",description:"December"}
			];

			scope.filter = {
				year: d.getFullYear(),
				month: scope.months[0]
			};
			
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
			
			if (scope.$id > 2) scope = scope.$parent;

			$http({
			  method: 'POST',
			  url: 'handlers/monthly/list.php',
			  data: scope.filter
			}).then(function mySucces(response) {
				
				scope.data = angular.copy(response.data);
				
				bui.hide();
				
			}, function myError(response) {
				 
				bui.hide();
				
			});
			
			$('#content').load('lists/monthly.html', function() {
				$timeout(function() { $compile($('#content')[0])(scope); },100);								
				// instantiate datable
				$timeout(function() {
					$('#monthlyTable').DataTable({
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
		
		self.printOverall = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/monthly/printOverall.php',
			  data: scope.filter
			}).then(function mySucces(response) {
				
				scope.overall = response.data;
				
				printTotal(scope,response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});
					
			
		}; 
		
		function printTotal(scope,overall) {
			
			var d = new Date();
			var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
			var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
			
			function formatAMPM(date) {
			  var hours = date.getHours();
			  var minutes = date.getMinutes();
			  var ampm = hours >= 12 ? 'pm' : 'am';
			  hours = hours % 12;
			  hours = hours ? hours : 12; // the hour '0' should be '12'
			  minutes = minutes < 10 ? '0'+minutes : minutes;
			  var strTime = hours + ':' + minutes + ' ' + ampm;
			  return strTime;
			}
			
			var doc = new jsPDF({
				orientation: 'portrait',
				unit: 'pt',
				format: [612, 792]
			});
			
			var doc = new jsPDF('p','mm','letter');
			
			function convert(str) {
				
				var month = new Array(7);
				month[0] = "January";
				month[1] = "February";
				month[2] = "March";
				month[3] = "April";
				month[4] = "May";
				month[5] = "June";
				month[6] = "July";
				month[7] = "August";
				month[8] = "September";
				month[9] = "October";
				month[10] = "November";
				month[11] = "December";
				
				var date = new Date(str),
				mnth = ("0" + (date.getMonth() + 1)).slice(-2),
				day = ("0" + date.getDate()).slice(-2);
					
				var value = month[date.getMonth()];
				
				return [value, day, date.getFullYear()].join(" ");
				
			};
			
			doc.setFontSize(14)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(20, 15, 'RFID-Based Learners Attendance Monitoring System with SMS Notification');
			doc.text(20, 27, 'RFID-Based Learners Attendance Monitoring System with SMS Notification');

			doc.setFontSize(17)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(60, 34, 'Monthly Attendance Report of '+scope.filter.year);
			
			doc.setFontSize(13)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(55, 21, 'Sinapangan National High School Balaoan La Union');
			// doc.text(305, 44, 'Total: '+scope.students.length);
			
			doc.setFontSize(10)
			doc.setFont('Arial');
			doc.setFontType('normal');
			
			doc.text(3, 44, 'Date & Time: '+ months[d.getMonth()]+' '+d.getDate()+', '+d.getFullYear()+' | '+formatAMPM(new Date));
			
			var header_ = ['MONTH',"NUMBER OF STUDENTS","REMARKS"];
			
			var rows_ = [
				['January',overall.total.january.length],
				['February',overall.total.february.length],
				['March',overall.total.march.length],
				['April',overall.total.april.length],
				['May',overall.total.may.length],
				['June',overall.total.june.length],
				['July',overall.total.july.length],
				['August',overall.total.august.length],
				['September',overall.total.september.length],
				['October',overall.total.october.length],
				['November',overall.total.november.length],
				['December',overall.total.december.length],
				['Total',overall.total.overall.count],
			];
			
			doc.autoTable(header_, rows_,{
				theme: 'striped',
				margin: {
					top: 45, 
					left: 3 
				},
				tableWidth: 500,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.30,
					cellPadding: 3,
					overflow: 'linebreak',
					columnWidth: 70,
					
				},
				headerStyles: {
					halign: 'center',
					fillColor: [217, 217, 217],
					textColor: 50,
					fontSize: 9
				},
				bodyStyles: {
					halign: 'center',
					fillColor: [255, 255, 255],
					textColor: 50,
					fontSize: 9
				},
				alternateRowStyles: {
					fillColor: [255, 255, 255]
				}
				
			});
			
			doc.setFontSize(10)
			doc.setFont('helvetica');
			doc.setFontType('bolditalic');
			var pageCount = doc.internal.getNumberOfPages();
			for(i = 0; i < pageCount; i++) { 
				doc.setPage(i); 
				doc.text(100,270, 'Page '+doc.internal.getCurrentPageInfo().pageNumber);
			}
			// pageCount maximum of page
			
			// doc.text(170, 210, 'Page '+doc.internal.getNumberOfPages());
			
			var blob = doc.output('blob');
			window.open(URL.createObjectURL(blob));
		
		};
		
		self.print = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/monthly/print.php',
			  data: scope.filter
			}).then(function mySucces(response) {
				
				scope.students = response.data;
				
				print(scope,response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});
					
			
		}; 
		
		function print(scope,students) {
			
			var d = new Date();
			var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
			var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
			
			var logo = 'data:image/png;base64,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';
			
			function formatAMPM(date) {
			  var hours = date.getHours();
			  var minutes = date.getMinutes();
			  var ampm = hours >= 12 ? 'pm' : 'am';
			  hours = hours % 12;
			  hours = hours ? hours : 12; // the hour '0' should be '12'
			  minutes = minutes < 10 ? '0'+minutes : minutes;
			  var strTime = hours + ':' + minutes + ' ' + ampm;
			  return strTime;
			}
			
			var doc = new jsPDF({
				orientation: 'portrait',
				unit: 'pt',
				format: [612, 792]
			});	
			
			var doc = new jsPDF('l','mm','legal');

			function convert(str) {
				
				var month = new Array(7);
				month[0] = "January";
				month[1] = "February";
				month[2] = "March";
				month[3] = "April";
				month[4] = "May";
				month[5] = "June";
				month[6] = "July";
				month[7] = "August";
				month[8] = "September";
				month[9] = "October";
				month[10] = "November";
				month[11] = "December";
				
				var date = new Date(str),
				mnth = ("0" + (date.getMonth() + 1)).slice(-2),
				day = ("0" + date.getDate()).slice(-2);
					
				var value = month[date.getMonth()];
				
				return [value, day, date.getFullYear()].join(" ");
				
			};
			
			doc.setFontSize(15)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(90, 15, 'RFID-Based Learners Attendance Monitoring System with SMS Notification');
			doc.text(90, 27, 'RFID-Based Learners Attendance Monitoring System with SMS Notification');
			console.log(scope);
			
			doc.setFontSize(18)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(126, 34, 'Monthly Attendance Report of '+scope.filter.year);
			
			doc.setFontSize(15)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(165, 40, ' '+scope.filter.month.description);
			
			console.log(scope);
			doc.setFontSize(13)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(123, 21, 'Sinapangan National High School Balaoan La Union');
			doc.text(305, 44, 'Total: '+scope.students.length);
			
			doc.setFontSize(10)
			doc.setFont('Arial');
			doc.setFontType('normal');
			
			doc.text(3, 44, 'Date & Time: '+ months[d.getMonth()]+' '+d.getDate()+', '+d.getFullYear()+' | '+formatAMPM(new Date));
			
			var header = ["No","ID Number","RFID No.","Name","Address","Year & Sections","Date Added"];
			
			var rows = [];
			
			angular.forEach(students, function(data,key) {
			
				var row = [];
				row.push(key+1);
				row.push(data.stud_studentID);
				row.push(data.stud_RFID);
				row.push(data.fullname);
				row.push(data.stud_address);
				row.push(data.stud_year_id.year_description+' - '+data.stud_sect_id.sect_description);
				row.push(data.date_added);

				rows.push(row);

			});

			doc.autoTable(header, rows,{
				theme: 'striped',
				margin: {
					top: 45, 
					left: 3 
				},
				tableWidth: 500,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.30,
					cellPadding: 3,
					overflow: 'linebreak',
					columnWidth: 50,
					
				},
				headerStyles: {
					halign: 'center',
					fillColor: [217, 217, 217],
					textColor: 50,
					fontSize: 9
				},
				bodyStyles: {
					halign: 'center',
					fillColor: [255, 255, 255],
					textColor: 50,
					fontSize: 9
				},
				alternateRowStyles: {
					fillColor: [255, 255, 255]
				}
				
			});
			
			doc.setFontSize(10)
			doc.setFont('helvetica');
			doc.setFontType('bolditalic');
			var pageCount = doc.internal.getNumberOfPages();
			for(i = 0; i < pageCount; i++) { 
				doc.setPage(i); 
				doc.text(170,210, 'Page '+doc.internal.getCurrentPageInfo().pageNumber);
			}
			// pageCount maximum of page
			
			// doc.text(170, 210, 'Page '+doc.internal.getNumberOfPages());
			
			var blob = doc.output('blob');
			window.open(URL.createObjectURL(blob));
		
		};
		
	};
	
	return new app();
	
});
angular.module('app-module',['form-validator','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,validate) {
	
	function app() {
	
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			$http({
				method: 'POST',
				url: 'handlers/daily/data.php'
			}).then(function mySucces(response) {
				
				scope.logs = response.data;
				
			},function myError(response) {
					
			});
						
		};
		
		self.print = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/daily/print.php'
			}).then(function mySucces(response) {
			
				print(scope,response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});

		};
		
		function print(scope,logs) {
			
			console.log(logs);
			
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
			
			doc.setFontSize(18)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(123, 34, 'Daily Attendance of '+months[d.getMonth()]+' '+d.getDate()+', '+d.getFullYear());
			
			doc.setFontSize(13)
			doc.setFont('Arial');
			doc.setFontType('normal');
			doc.text(123, 21, 'Sinapangan National High School Balaoan La Union');
			
			doc.setFontSize(10)
			doc.setFont('Arial');
			doc.setFontType('normal');
			
			var header_total = ["Total:",logs.total[0].countPerHour,logs.total[1].countPerHour,logs.total[2].countPerHour,logs.total[3].countPerHour,logs.total[4].countPerHour,logs.total[5].countPerHour,logs.total[6].countPerHour,logs.total[7].countPerHour,logs.total[8].countPerHour,logs.total[9].countPerHour,logs.total[10].countPerHour,logs.total[11].countPerHour,logs.total[12].countPerHour,logs.total[13].countPerHour]
			var rows_total = [];
			doc.autoTable(header_total, rows_total,{
				theme: 'striped',
				margin: {
					top: 40, 
					left: 3 
				},
				tableWidth: 500,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.20,
					cellPadding: 3,
					overflow: 'linebreak',
					// columnWidth: 21,
				},
				columnStyles: {
					0: {columnWidth: 100}, // 
					1: {columnWidth: 15}, //
					2: {columnWidth: 15}, // 
					3: {columnWidth: 15}, //
					4: {columnWidth: 15}, //
					5: {columnWidth: 15}, // 
					6: {columnWidth: 15}, // 
					7: {columnWidth: 15}, //
					8: {columnWidth: 15}, //
					9: {columnWidth: 15}, //
					10: {columnWidth: 15}, //
					11: {columnWidth: 15}, // 
					12: {columnWidth: 15}, // 
					13: {columnWidth: 15}, // 
					14: {columnWidth: 15}, //
				},
				headerStyles: {
					halign: 'center',
					fillColor: [91, 215, 120],
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
		
			var header = ["Category","8:00","9:00","10:00","11:00","12:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00"];
			
			var rows = [];
			
			angular.forEach(logs.courses, function(data,key) {
			
				var row = [];
				row.push(data[0].d);
				row.push(data[1].d);
				row.push(data[2].d);
				row.push(data[3].d);
				row.push(data[4].d);
				row.push(data[5].d);
				row.push(data[6].d);
				row.push(data[7].d);
				row.push(data[8].d);
				row.push(data[9].d);
				row.push(data[10].d);
				row.push(data[11].d);
				row.push(data[12].d);
				row.push(data[13].d);
				row.push(data[14].d);
				
				rows.push(row);

			});

			doc.autoTable(header, rows,{
				theme: 'striped',
				margin: {
					top: 50, 
					left: 3 
				},
				tableWidth: 500,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.20,
					cellPadding: 3,
					overflow: 'linebreak',
					// columnWidth: 21,
				},
				columnStyles: {
					0: {columnWidth: 100}, // 
					1: {columnWidth: 15}, //
					2: {columnWidth: 15}, // 
					3: {columnWidth: 15}, //
					4: {columnWidth: 15}, //
					5: {columnWidth: 15}, // 
					6: {columnWidth: 15}, // 
					7: {columnWidth: 15}, //
					8: {columnWidth: 15}, //
					9: {columnWidth: 15}, //
					10: {columnWidth: 15}, //
					11: {columnWidth: 15}, // 
					12: {columnWidth: 15}, // 
					13: {columnWidth: 15}, // 
					14: {columnWidth: 15}, //
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
			
			var header_sub = ["Subtotal"];
			
			var rows_sub = [];
			
			angular.forEach(logs.subtotal, function(data,key) {
			
				var row = [];
				row.push(data.total);
				
				rows_sub.push(row);

			});

			doc.autoTable(header_sub, rows_sub,{
				theme: 'striped',
				margin: {
					top: 50, 
					left: 322 
				},
				tableWidth: 500,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.20,
					cellPadding: 3,
					overflow: 'linebreak',
					columnWidth: 28,
					
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
			
			var header_overall = [logs.overall];
			
			var rows_overall = [];
			
			doc.autoTable(header_overall, rows_overall,{
				theme: 'striped',
				margin: {
					top: 40, 
					left: 322 
				},
				tableWidth: 500,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.20,
					cellPadding: 3,
					overflow: 'linebreak',
					columnWidth: 28,
				},
				headerStyles: {
					halign: 'center',
					fillColor: [91, 215, 120],
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
		
		self.list = function (scope){
			
			$http({
				method: 'POST',
				url: 'handlers/daily/list.php'
			}).then(function mySucces(response) {
				
				scope.courses = response.data;
				
			},function myError(response) {
					
			});	
			
		};
		

	};
	
	return new app();
	
});
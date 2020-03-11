angular.module('app-module',['my-pagination','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']).factory('app', function($compile,$timeout,$http,bootstrapModal,growl,bui,myPagination) {
	
	function app() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data			
			
			var d = new Date();
			
			scope.filter = {
				year: d.getFullYear()
			};
			
			console.log(scope);
			
			monthly(scope);
				
		};
		
		self.list = function(scope){
			
			$http({
			  method: 'POST',
			  url: 'handlers/dashboard/dashboard.php'		  
			}).then(function mySucces(response) {
			
				scope.dashboard = angular.copy(response.data);
				
				$(function () {
					var donutData = {
					  labels: [
						  'Super Admin', 
						  'Admin', 
						  'Teacher', 
					  ],
					  datasets: [
						{
						  data: [scope.dashboard.accounts.sa,scope.dashboard.accounts.admin,scope.dashboard.accounts.teacher],
						  backgroundColor : ['#00a65a','#f56954',"#17a2b8"],
						}
					  ]
					}
					
					var donutOptions     = {
					  maintainAspectRatio : false,
					  responsive : true,
					}
				
					// Get context with jQuery - using jQuery's .get() method.
					var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
					var pieData        = donutData;
					var pieOptions     = {
					  maintainAspectRatio : false,
					  responsive : true,
					}
					//Create pie or douhnut chart
					// You can switch between pie and douhnut using the method below.
					var pieChart = new Chart(pieChartCanvas, {
					  type: 'pie',
					  data: pieData,
					  options: pieOptions      
					})
				});
				
			}, function myError(response) {

			});
			
		};
		
		function monthly(scope){
			
			$http({
			  method: 'POST',
			  url: 'handlers/dashboard/monthly.php',
			  data: scope.filter
			}).then(function mySucces(response) {

				scope.data = angular.copy(response.data);
				
				$(function () {
				//-------------
				//- Datas -
				//-------------
				var areaChartData = {
				  labels  : ['January','February','March','April','May','June','July','August','September','October','November','December'],
				  datasets: [
					{
					  label               : 'Year '+scope.filter.year,
					  backgroundColor     : 'rgba(23,162,184,0.9)',
					  borderColor         : 'rgba(23,162,184,0.8)',
					  pointRadius          : false,
					  pointColor          : '#3b8bba',
					  pointStrokeColor    : 'rgba(23,162,184,1)',
					  pointHighlightFill  : '#fff',
					  pointHighlightStroke: 'rgba(23,162,184,1)',
					  data                : [scope.data.total.january.length,scope.data.total.february.length,scope.data.total.march.length,scope.data.total.april.length,scope.data.total.may.length,scope.data.total.june.length,scope.data.total.july.length,scope.data.total.august.length,scope.data.total.september.length,scope.data.total.october.length,scope.data.total.november.length,scope.data.total.december.length]
					},
				  ]
				}
				//-------------
				//- BAR CHART -
				//-------------
				var barChartData = jQuery.extend(true, {}, areaChartData)
				var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
				var stackedBarChartData = jQuery.extend(true, {}, barChartData)

				var stackedBarChartOptions = {
				  responsive              : true,
				  maintainAspectRatio     : false,
				  scales: {
					xAxes: [{
					  stacked: true,
					}],
					yAxes: [{
					  stacked: true
					}]
				  }
				}

				var stackedBarChart = new Chart(stackedBarChartCanvas, {
				  type: 'bar', 
				  data: stackedBarChartData,
				  options: stackedBarChartOptions
				})

			});

			}, function myError(response) {

			});
			
		};
		
	};
	
	return new app();
	
});
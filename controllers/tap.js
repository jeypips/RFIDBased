let app = angular.module('tap',['account-module','ui.bootstrap','bootstrap-modal','bootstrap-growl','block-ui']);

app.controller('tapCtrl', function($scope,$compile,$timeout,$http,bootstrapModal,growl,bui) {
	
	$scope.shows = {};
	$scope.shows.not_found = false;
	$scope.shows.found = false;
	$scope.shows.logout = false;
	
	$scope.student = {};
	
	// Parses raw scan into name and ID number
	let rfidParser = function(rawData) {
		// console.log(rawData, rawData.length);
		if (rawData.length != 11) return null;
		else return rawData;				
	};

	// Called on a bad scan (company card not recognized)
	let badScan = function() {
		console.log("Bad Scan.");
	};

	let login = function(rfid) {

		$http({
		  url: "handlers/rfid-login.php",
		  method: "POST",
		  data: {stud_RFID: rfid}		
		}).then(response => {
			
			if (response.data.exists) {
				
				if (response.data.logout) {
					
					$scope.shows.not_found = false;
					$scope.shows.found = false;					
					$scope.shows.logout = true;				
					
				} else {
				
					$scope.shows.not_found = false;
					$scope.shows.found = true;
					$scope.shows.logout = false;					
					
					$scope.student.photo = response.data.photo;
					$scope.student.name = response.data.name;
					$scope.student.year_section = response.data.year_section;
					$scope.student.course = response.data.course;
					$scope.student.time = response.data.time;
					
				};
				
				if (response.data.connected) {
				
					if (response.data.send) {
						sendSms(response.data.sms);
					} else {
						if (!response.data.logout) growl.show('danger',{from: 'top', amount: 150},response.data.error,5);								
					};
					
				} else {
					growl.show('danger',{from: 'top', amount: 150},'No internet connection detected, unable to send SMS message',5);
				};
					
			} else {
				$scope.shows.not_found = true;
				$scope.shows.found = false;
				$scope.shows.logout = false;				
			};			
			
		}, response => {
			
		});			
		
	};

	// Initialize the plugin.
	$.rfidscan({
		parser: rfidParser,
		success: login,
		error: badScan
	});			

	function sendSms(url) {
		
		$http({
			url: url,
			method: 'GET'
		}).then(response => {
			
			growl.show('success',{from: 'top', amount: 150},'SMS message sent',5);
			
		}, response => {
			
		});
		
		/* $.ajax({
			url: url,
			method: "GET",
			error: (jqXHR, textStatus, errorThrown) => {
				
			}
		}).done((response) => {
			
			growl.show('success',{from: 'top', amount: 150},'SMS message sent',5);
			
		}); */
		
	};	
	
});
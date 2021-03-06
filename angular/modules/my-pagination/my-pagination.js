angular.module('my-pagination',[]).service('myPagination',function($http) {
	
	var self = this;
	
	self.init = function(scope) {		
	
		scope.pageChanged = function() {
            self.getList(scope.pagination.url+scope.pagination.entryLimit+'/'+scope.pagination.currentPage, scope.pagination.filters).then((response)=> {
				// scope.documents = response.data;
          });
        };
		
	};
	
	self.count = function(url,filters) {

		return $http.post(url,filters);
		
	};
	
	self.getList = function(url, filters) {

		return $http.post(url,filters);

	};	
	
});
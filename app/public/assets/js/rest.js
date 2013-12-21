/**
 * rest.js
 */

RESTClient = ( function () {
	var C = function RESTClient() {
		this.createXHR = function () {
			return new XMLHttpRequest();
		};
		
		this.createResponse = function ( xhr ) {
			return {
				'status': xhr.status,
				'statusText': xhr.statusText,
				'body': xhr.responseText,
				'bodyJSON': eval( '(' + xhr.responseText + ')' ) // FIXME make it safe!
			};
		};
	};
	
	C.prototype.get = function( uri, async, callback ) {
		async = typeof async == 'boolean' ? async : true;
		
		var xhr = this.createXHR();
		
		if ( async ) {
			var self = this;
			xhr.onreadystatechange = function ( event ) {
				var xhr = event.target;

				if ( xhr.readyState == 4 && typeof callback == 'function' ) {
					callback.call( this, self.createResponse( xhr ) );
				}
			};
		}
		
		xhr.open( 'GET', uri, async );
		xhr.send();
		
		if ( !async ) {
			return this.createResponse( xhr );
		}
	};
	
	return C;
} )();
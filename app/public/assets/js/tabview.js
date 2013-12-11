/**
 * tabview.js
 */
TabView = ( function () {
	/**
	 * TabView
	 * @constructor
	 * 
	 * @param HTMLElement element
	 */
	var C = function TabView ( element ) {
		this.element = element;
		this.nav = null;
		this.sheets = null;
		this.sheetKeyMap = {};
		this.buttonKeyMap = {};
		
		/**
		 * Initializer.
		 */
		this.init = function () {
			this.sheets = this.element.getElementsByTagName( 'section' );
			this.nav = this.element.getElementsByTagName( 'a' );
			
			this.initNav.call( this );
		};
		
		/**
		 * Initializes the navigation.
		 */
		this.initNav = function () {
			var btn,
				tabClickHandler = this.onTabClicked,
				context = this;
			
			for ( var i = 0; i < this.nav.length; i++ ) {
				btn = this.nav[ i ];
				
				if ( btn.addEventListener ) {
					btn.addEventListener( 'click', function ( event ) {
						tabClickHandler.call( context, this, event );
					} );
				}
				else if ( btn.attachEvent ) {
					btn.attachEvent( 'click', function ( event ) {
						tabClickHandler.call( context, this, event );
					} );
				}
				else {
					throw Error( 'Failed to register event.' );
				}
			}
		};
		
		/**
		 * Finds the tab sheet by given key.
		 * 
		 * @param {String} key
		 * 
		 * @return HTMLElement
		 */
		this.findSheetByKey = function ( key ) {
			if ( this.sheetKeyMap[ key ] ) {
				return this.sheetKeyMap[ key ];
			}
			
			for ( var i = 0; i < this.sheets.length; i++ ) {
				if ( this.sheets[ i ].getAttribute( 'id' ) == key ) {
					this.sheetKeyMap[ key ] = this.sheets[ i ];
					return this.sheets[ i ];
				}
			}
			
			throw Error( 'No sheet found with key: ' + key );
		};
		
		/**
		 * Finds the button by given key.
		 * 
		 * @param {String} key
		 * 
		 * @return HTMLElement
		 */
		this.findButtonByKey = function ( key ) {
			if ( this.buttonKeyMap[ key ] ) {
				return this.buttonKeyMap[ key ];
			}
			
			for ( var i = 0; i < this.nav.length; i++ ) {
				if ( this.nav[ i ].getAttribute( 'href' ).substring( 1 ) == key ) {
					this.buttonKeyMap[ key ] = this.nav[ i ];
					return this.nav[ i ];
				}
			}
			
			throw Error( 'No button found with key: ' + key );
		};
		
		/**
		 * Sets the given button active.
		 * 
		 * @param HTMLElement button
		 */
		this.setButtonActive = function ( button ) {
			for ( var i = 0; i < this.nav.length; i++ ) {
				if ( this.nav[ i ] == button ) {
					continue;
				}
				
				this.removeClass( this.nav[ i ], 'active' );
			}
			
			this.addClass( button, 'active' );
		};
		
		/**
		 * Sets the sheet active.
		 * 
		 * @param HTMLElement sheet
		 */
		this.setSheetActive = function ( sheet ) {
			for ( var i = 0; i < this.sheets.length; i++ ) {
				if ( this.sheets[ i ] == sheet ) {
					continue;
				}
				
				this.removeClass( this.sheets[ i ], 'active' );
			}
			
			this.addClass( sheet, 'active' );
		};
		
		/**
		 * Removes class from element.
		 * 
		 * @param HTMLElement element
		 * @param {String} c
		 */
		this.removeClass = function ( element, c ) {
			var classes = this.getClasses( element ),
				newClasses = [];
			
			for ( var i = 0; i < classes.length; i++ ) {
				if ( classes[ i ].trim() == c ) {
					continue;
				}
			
				newClasses.push( classes[ i ] );
			}
			
			this.setClasses( element, newClasses );
		};
		
		/**
		 * Adds class to element.
		 * 
		 * @param HTMLElement element
		 * @param {String} c
		 */
		this.addClass = function ( element, c ) {
			var classes = this.getClasses( element );
			classes.push( c );
			this.setClasses( element, classes );
		};
		
		/**
		 * Retrieves an array of classes from the element.
		 * 
		 * @param HTMLElement element
		 * 
		 * @return Array
		 */
		this.getClasses = function ( element ) {
			var classAttribute = element.getAttribute( 'class' );
			return classAttribute ? classAttribute.split( ' ' ) : [];
		};
		
		/**
		 * Sets classes to the element.
		 * 
		 * @param HTMLElement element
		 * @param Array classes
		 */
		this.setClasses = function ( element, classes ) {
			classes = classes || [];
			element.setAttribute( 'class', classes.join( ' ' ) );
		};
		
		this.init.call( this );
	};
	
	/**
	 * Tab click handler.
	 * 
	 * @param HTMLElement sender
	 * @param {Object} event
	 */
	C.prototype.onTabClicked = function ( sender, event ) {
		var key = sender.getAttribute( 'href' ).substring( 1 );
		this.activateTab( key );
	};
	
	/**
	 * Activates the tab with given key.
	 * 
	 * @param {String} key
	 */
	C.prototype.activateTab = function ( key ) {
		var sheet = this.findSheetByKey( key ),
			button = this.findButtonByKey( key );
		
		this.setButtonActive( button );
		this.setSheetActive( sheet );
	};
	
	return C;
} )();
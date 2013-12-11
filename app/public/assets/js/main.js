/**
 * main.js
 */

google.load( 'visualization', '1.0', { 'packages': ['corechart'] } );
google.setOnLoadCallback( function () {
	if ( !Report ) {
		throw Error( "No Report data!" );
	}
	
	var tabElement = document.getElementById( 'tabs' );
	var tabView = new TabView( tabElement );
	
	console.log( Report );
	
	drawComplexityChart( Report.complexity, 'chart-complexity' );
	drawUnitSizeChart( Report.unitSize, 'chart-unitsize' );
	drawDuplicationChart( Report.duplication, Report.volume, 'chart-duplication' );
	
	drawRanksChart( Report, 'chart-ranks' );
} );

function makeList( data, id ) {
	var list = document.createElement( 'ul' ),
	item,
	method;

	for ( var i = 0; i < data.methods.length; i++ ) {
		method = data.methods[ i ];
	
		item = document.createElement( 'li' );
		item.appendChild(
			document.createTextNode(
				method.location.fileName
				+ '::' + method.name
				+ ' (lines: ' + method.unitSize.size + ','
				+ 'complexity: ' + method.complexity.complexity
				+ ')'
			)
		);
		list.appendChild( item );
	}

	var container = document.getElementById( id );
	container.innerHTML = '';
	
	var p = document.createElement( 'p' );
	p.appendChild( document.createTextNode( data.methods.length + ' methods' ) );
	
	container.appendChild( p );
	container.appendChild( list );
};

function drawComplexityChart( complexity, elementId ) {
	var table = new google.visualization.DataTable();
	
	table.addColumn( 'string', 'Risk category' );
	table.addColumn( 'number', 'Absolute LOC' );
	
	table.addRows( [
		[ 'Low risk', complexity.low.absoluteLOC ],
		[ 'Moderate risk', complexity.moderate.absoluteLOC ],
		[ 'High risk', complexity.high.absoluteLOC ],
		[ 'Very high risk', complexity.veryHigh.absoluteLOC ]
	] );
	
	var options = {
		title: 'Method complexity partitions',
		colors: [ '#ffee00', '#ffaa00', '#ff6600', '#ff3300' ],
		is3D: true
	};
	
	var chart = new google.visualization.PieChart( document.getElementById( elementId ) );
	chart.draw( table, options );
	

	google.visualization.events.addListener( chart, 'click', function ( event ) {
		var data;
		switch ( event.targetID ) {
			case 'slice#0':
				data = complexity.low;
				break;
			case 'slice#1':
				data = complexity.moderate;
				break;
			case 'slice#2':
				data = complexity.high;
				break;
			case 'slice#3':
				data = complexity.veryHigh;
				break;
			default:
				return;
		}
		makeList( data, 'methods-complexity' );
	} );
};

function drawUnitSizeChart( unitSize, elementId ) {
	var table = new google.visualization.DataTable();
	
	table.addColumn( 'string', 'Size category' );
	table.addColumn( 'number', 'Absolute LOC' );
	
	table.addRows( [
		[ 'Small', unitSize.small.absoluteLOC ],
		[ 'Medium', unitSize.medium.absoluteLOC ],
		[ 'Large', unitSize.large.absoluteLOC ],
		[ 'Very large', unitSize.veryLarge.absoluteLOC ]
	] );
	
	var options = {
		title: 'Method size partitions',
		colors: [ '#ffee00', '#ffaa00', '#ff6600', '#ff3300' ],
		is3D: true
	};
	
	var chart = new google.visualization.PieChart( document.getElementById( elementId ) );
	chart.draw( table, options );
	
	google.visualization.events.addListener( chart, 'click', function ( event ) {
		var data;
		switch ( event.targetID ) {
			case 'slice#0':
				data = unitSize.small;
				break;
			case 'slice#1':
				data = unitSize.medium;
				break;
			case 'slice#2':
				data = unitSize.large;
				break;
			case 'slice#3':
				data = unitSize.veryLarge;
				break;
			default:
				return;
		}
		makeList( data, 'methods-unitsize' );
	} );
};

function drawDuplicationChart( duplication, volume, elementId ) {
	var table = new google.visualization.DataTable();
	
	table.addColumn( 'string', 'Duplication' );
	table.addColumn( 'number', 'Absolute LOC' );
	
	table.addRows( [
		[ 'Original', volume.totalLinesOfCode - duplication.absoluteLOC ],
		[ 'Cloned', duplication.absoluteLOC ]
	] );
	
	var options = {
		title: 'Duplicated lines of code',
		colors: [ '#ffee00', '#ff3300' ],
		is3D: true
	};
	
	var chart = new google.visualization.PieChart( document.getElementById( elementId ) );
	chart.draw( table, options );
	
	google.visualization.events.addListener( chart, 'click', function ( event ) {
		var blocks;
		
		switch ( event.targetID ) {
			case 'slice#0':
				blocks = [];
				break;
			case 'slice#1':
				blocks = duplication.duplications.duplications;
				break;
			default:
				return;
		}
		
		var list = document.createElement( 'ul' ),
			item,
			inner,
			span,
			innerItem,
			location;
		
		for ( var i = 0; i < blocks.length; i++ ) {
			item = document.createElement( 'li' );
			span = document.createElement( 'span' );
			span.appendChild( document.createTextNode( blocks[ i ].block ) );
			item.appendChild( span );
			
			inner = document.createElement( 'ul' );
			
			for ( var j = 0; j < blocks[ i ].locations.length; j++ ) {
				location = blocks[ i ].locations[ j ];
				innerItem = document.createElement( 'li' );
				innerItem.appendChild( document.createTextNode( location.fileName + '(' + location.startLine + ',' + location.endLine + ')' ) );
				inner.appendChild( innerItem );
			}
			
			item.appendChild( inner );
			list.appendChild( item );
		}
		
		var container = document.getElementById( 'methods-duplication' );
		container.innerHTML = '';
		
		var p = document.createElement( 'p' );
		p.appendChild( document.createTextNode( blocks.length + ' duplicated blocks' ) );
		container.appendChild( p );
		container.appendChild( list );
	} );
};

function drawRanksChart( report, elementId ) {
	var table = new google.visualization.DataTable();
	
	table.addColumn( 'string', 'Property' );
	table.addColumn( 'number', 'Rank' );
	
	table.addRows( [
		[ 'Volume', report.volume.rank ],
		[ 'Method size', report.unitSize.rank ],
		[ 'Duplication', report.duplication.rank ],
		[ 'Complexity', report.complexity.rank ],
		[ 'Analyzability', report.maintainability.analyzabilityRank ],
		[ 'Changeability', report.maintainability.changeabilityRank ],
		[ 'Testability', report.maintainability.testabilityRank ],
		[ 'Stability', report.maintainability.stabilityRank ],
		[ 'Maintainability', report.maintainability.rank ]
	] );
	
	var options = {
		title: 'Rankings'
	};
	
	var chart = new google.visualization.BarChart( document.getElementById( elementId ) );
	chart.draw( table, options );
};

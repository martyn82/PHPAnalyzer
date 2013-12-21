/**
 * main.js
 */

var serviceConsumer,
	serviceLocation = 'http://service.analyze.local';

google.load( 'visualization', '1.0', { 'packages': [ 'corechart' ] } );
google.setOnLoadCallback( function () {
	var tabView = new TabView( document.getElementById( 'tabs' ) );
	
	serviceConsumer = new RESTClient();

	serviceConsumer.get( serviceLocation + '/project/sample/', true, function ( response ) {
		var project = response.bodyJSON;
		
		setProjectDetails( project );
		
		var reports = [],
			reportResponse;

		for ( var i = 0; i < project.reports.length; i++ ) {
			reportResponse = serviceConsumer.get( serviceLocation + '/' + project.reports[ i ], false );
			reports.push( reportResponse.bodyJSON );
		}

		var volumeResponse;

		for ( var i = 0; i < reports.length; i++ ) {
			volumeResponse = serviceConsumer.get( serviceLocation + '/' + reports[ i ].volume, false );
			reports[ i ].volume = volumeResponse.bodyJSON;
		}

		drawVolumeTimeChart( reports );
		
		var complexityResponse;
		for ( var i = 0; i < reports.length; i++ ) {
			complexityResponse = serviceConsumer.get( serviceLocation + '/' + reports[ i ].complexity, false );
			reports[ i ].complexity = complexityResponse.bodyJSON;
		}
		
		drawComplexityTimeChart( reports );
		
		var unitSizeResponse;
		
		for ( var i = 0; i < reports.length; i++ ) {
			unitSizeResponse = serviceConsumer.get( serviceLocation + '/' + reports[ i ].unitSize, false );
			reports[ i ].unitSize = unitSizeResponse.bodyJSON;
		}
		
		drawUnitSizeTimeChart( reports );
		
		var duplicationResponse;
		
		for ( var i = 0; i < reports.length; i++ ) {
			duplicationResponse = serviceConsumer.get( serviceLocation + '/' + reports[ i ].duplication, false );
			reports[ i ].duplication = duplicationResponse.bodyJSON;
		}
		
		drawDuplicationTimeChart( reports );
	} );
} );

function timeChartSelectionHandler( chart, reports, drawChartCallback, reportName ) {
	var selection = chart.getSelection();

	if ( selection.length == 0 ) {
		return;
	}

	var report = reports[ selection[ 0 ].row ];
	drawChartCallback( report.dateTime, report[ reportName ] );
};

function setProjectDetails( project ) {
	document.getElementById( 'project-name' ).appendChild( document.createTextNode( project.name ) );
};

function drawVolumeTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Lines' );
	table.addColumn( 'number', 'Lines of code' );
	table.addColumn( 'number', 'Files' );
	table.addColumn( 'number', 'Packages' );
	table.addColumn( 'number', 'Classes' );
	table.addColumn( 'number', 'Methods' );

	var rows = [],
		report;

	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.volume.totalLines,
			report.volume.totalLinesOfCode,
			report.volume.fileCount,
			report.volume.packageCount,
			report.volume.classCount,
			report.volume.methodCount
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-volume-time' ) );
	chart.draw( table, {
		'title': 'Volume over time',
		'pointSize': 5
	} );
};

function drawComplexityTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Low risk' );
	table.addColumn( 'number', 'Moderate risk' );
	table.addColumn( 'number', 'High risk' );
	table.addColumn( 'number', 'Very high risk' );
	
	var rows = [],
		report;
	
	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.complexity.low.absoluteLOC,
			report.complexity.moderate.absoluteLOC,
			report.complexity.high.absoluteLOC,
			report.complexity.veryHigh.absoluteLOC
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-complexity-time' ) );
	chart.draw( table, {
		'title': 'Complexity over time',
		'pointSize': 5
	} );
	
	google.visualization.events.addListener( chart, 'select', function () {
		timeChartSelectionHandler( chart, reports, drawComplexityChart, 'complexity' );
	} );
};

function drawComplexityChart( dateTime, report ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'string', 'Risk category' );
	table.addColumn( 'number', 'Absolute LOC' );
	table.addColumn( 'number', 'Relative LOC' );
	
	table.addRows( [
		[ 'Low risk', report.low.absoluteLOC, report.low.relativeLOC ],
		[ 'Moderate risk', report.moderate.absoluteLOC, report.moderate.relativeLOC ],
		[ 'High risk', report.high.absoluteLOC, report.high.relativeLOC ],
		[ 'Very high risk', report.veryHigh.absoluteLOC, report.veryHigh.relativeLOC ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-complexity' ) );
	chart.draw( table, {
		'title': 'Complexity risk partitions for ' + dateTime.toString()
	} );
};

function drawUnitSizeTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Small' );
	table.addColumn( 'number', 'Medium' );
	table.addColumn( 'number', 'Large' );
	table.addColumn( 'number', 'Very large' );
	
	var rows = [],
		report;

	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.unitSize.small.absoluteLOC,
			report.unitSize.medium.absoluteLOC,
			report.unitSize.large.absoluteLOC,
			report.unitSize.veryLarge.absoluteLOC
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-unitsize-time' ) );
	chart.draw( table, {
		'title': 'Unit size over time',
		'pointSize': 5
	} );
	
	google.visualization.events.addListener( chart, 'select', function () {
		timeChartSelectionHandler( chart, reports, drawUnitSizeChart, 'unitSize' );
	} );
};

function drawUnitSizeChart( dateTime, report ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'string', 'Size category' );
	table.addColumn( 'number', 'Absolute LOC' );
	table.addColumn( 'number', 'Relative LOC' );
	
	table.addRows( [
		[ 'Small', report.small.absoluteLOC, report.small.relativeLOC ],
		[ 'Medium', report.medium.absoluteLOC, report.medium.relativeLOC ],
		[ 'Large', report.large.absoluteLOC, report.large.relativeLOC ],
		[ 'Very large', report.veryLarge.absoluteLOC, report.veryLarge.relativeLOC ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-unitsize' ) );
	chart.draw( table, {
		'title': 'Unit size partitions for ' + dateTime.toString()
	} );
};

function drawDuplicationTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Duplicated LOC' );
	table.addColumn( 'number', 'Total LOC' );
	
	var rows = [],
		report;

	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.duplication.absoluteLOC,
			report.volume.totalLinesOfCode
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-duplication-time' ) );
	chart.draw( table, {
		'title': 'Duplication over time',
		'pointSize': 5
	} );
	
	google.visualization.events.addListener( chart, 'select', function () {
		timeChartSelectionHandler( chart, reports, drawDuplicationChart, 'duplication' );
	} );
};

function drawDuplicationChart( dateTime, report ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'string', 'Code block' );
	table.addColumn( 'number', 'Lines' );
	
	table.addRows( [
		[ 'Duplications', report.absoluteLOC ],
		[ 'Original', Math.round( report.absoluteLOC / report.relativeLOC * 100 ) ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-duplication' ) );
	chart.draw( table, {
		'title': 'Duplication for ' + dateTime.toString()
	} );
};
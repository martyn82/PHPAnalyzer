/**
 * main.js
 */

var serviceConsumer,
	serviceLocation = 'http://analyze.local/service/projects';

google.load( 'visualization', '1.0', { 'packages': [ 'corechart' ] } );
google.setOnLoadCallback( function () {
	var projectId = document.getElementById( 'project-id' ).getAttribute( 'data-value' );
	
	var tabView = new TabView( document.getElementById( 'tabs' ) );

	serviceConsumer = new RESTClient();

	serviceConsumer.get( serviceLocation + '?id=' + projectId, true, function ( response ) {
		var results = response.bodyJSON.results,
			project = results.project,
			reports = results.reports;

		setProjectDetails( project );
		
		var projectReports = [];
		
		for ( var i = 0; i < reports.length; i++ ) {
			projectReports.push( reports[ i ].report );
		}
		
		drawVolumeTimeChart( projectReports );
		drawEntityTimeChart( projectReports );
		drawComplexityTimeChart( projectReports );
		drawUnitSizeTimeChart( projectReports );
		drawDuplicationTimeChart( projectReports );
	} );
} );

function timeChartSelectionHandler( chart, reports, drawChartCallback, reportName ) {
	var selection = chart.getSelection();

	if ( selection.length == 0 ) {
		return;
	}

	var report = reports[ selection[ 0 ].row ],
		reportLevels = reportName.split( '.' ),
		finalReport = report;
	
	if ( !finalReport ) {
		return;
	}
	
	for ( var i = 0; i < reportLevels.length; i++ ) {
		finalReport = finalReport[ reportLevels[ i ] ];
	}
	
	if ( !finalReport ) {
		return;
	}
	
	drawChartCallback( report.dateTime, finalReport );
};

function setProjectDetails( project ) {
	document.getElementById( 'project-name' ).appendChild( document.createTextNode( project.name ) );
};

function drawVolumeTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Total lines' );
	table.addColumn( 'number', 'Lines of code' );
	table.addColumn( 'number', 'Blank lines' );
	table.addColumn( 'number', 'Comments' );

	var rows = [],
		report;

	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.volume.lines.absolute,
			report.volume.linesOfCode.absolute,
			report.volume.linesBlank.absolute,
			report.volume.linesOfComments.absolute
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.AreaChart( document.getElementById( 'chart-volume-time' ) );
	chart.draw( table, {
		'title': 'Volume over time',
		'pointSize': 5,
		'colors': [ '#000000', '#0101df', '#ffff00', '#298a08' ]
	} );
	
	google.visualization.events.addListener( chart, 'select', function () {
		timeChartSelectionHandler( chart, reports, drawVolumeChart, 'volume' );
	} );
};

function drawVolumeChart( dateTime, report ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'string', 'Lines' );
	table.addColumn( 'number', 'Absolute' );
	table.addColumn( 'number', 'Relative' );
	
	table.addRows( [
		[ 'LOC', report.linesOfCode.absolute, report.linesOfCode.relative ],
		[ 'Blanks', report.linesBlank.absolute, report.linesBlank.relative ],
		[ 'Comments', report.linesOfComments.absolute, report.linesOfComments.relative ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-volume' ) );
	chart.draw( table, {
		'title': 'Volume facts for ' + dateTime.toString(),
		'colors': [ '#0101df', '#ffff00', '#298a08' ]
	} );
};

function drawEntityTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Methods' );
	table.addColumn( 'number', 'Classes' );
	table.addColumn( 'number', 'Files' );
	table.addColumn( 'number', 'Packages' );

	var rows = [],
		report;

	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.entity.methods.methods.length,
			report.entity.classes.classes.length,
			report.entity.files.files.length,
			report.entity.packages.packages.length
		] );
	}

	table.addRows( rows );

	var chart = new google.visualization.LineChart( document.getElementById( 'chart-density-time' ) );
	chart.draw( table, {
		'title': 'Density over time',
		'pointSize': 5,
		'colors': [ '#8a0808', '#886a08', '#4b8a08', '#08298a' ]
	} );

	google.visualization.events.addListener( chart, 'select', function () {
		timeChartSelectionHandler( chart, reports, drawEntityChart, 'entity' );
	} );
};

function drawEntityChart( dateTime, report ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'string', 'Entity' );
	table.addColumn( 'number', 'Count' );

	table.addRows( [
		[ 'Methods', report.methods.methods.length ],
		[ 'Classes', report.classes.classes.length ],
		[ 'Files', report.files.files.length ],
		[ 'Packages', report.packages.packages.length ]
	] );

	var chart = new google.visualization.PieChart( document.getElementById( 'chart-density' ) );
	chart.draw( table, {
		'title': 'Density facts for ' + dateTime.toString(),
		'colors': [ '#8a0808', '#886a08', '#4b8a08', '#08298a' ]
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
			report.complexity[ 1 ].absolute,
			report.complexity[ 2 ].absolute,
			report.complexity[ 3 ].absolute,
			report.complexity[ 4 ].absolute
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-complexity-time' ) );
	chart.draw( table, {
		'title': 'Complexity over time',
		'pointSize': 5,
		'colors': [ '#80ff00', '#ffff00', '#ff8000', '#df0101' ]
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
		[ 'Low risk', report[ 1 ].absolute, report[ 1 ].relative ],
		[ 'Moderate risk', report[ 2 ].absolute, report[ 2 ].relative ],
		[ 'High risk', report[ 3 ].absolute, report[ 3 ].relative ],
		[ 'Very high risk', report[ 4 ].absolute, report[ 4 ].relative ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-complexity' ) );
	chart.draw( table, {
		'title': 'Complexity risk partitions for ' + dateTime.toString(),
		'colors': [ '#80ff00', '#ffff00', '#ff8000', '#df0101' ]
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
			report.unitSize[ 1 ].absolute,
			report.unitSize[ 2 ].absolute,
			report.unitSize[ 3 ].absolute,
			report.unitSize[ 4 ].absolute
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-unitsize-time' ) );
	chart.draw( table, {
		'title': 'Unit size over time',
		'pointSize': 5,
		'colors': [ '#80ff00', '#ffff00', '#ff8000', '#df0101' ]
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
		[ 'Small', report[ 1 ].absolute, report[ 1 ].relative ],
		[ 'Medium', report[ 2 ].absolute, report[ 2 ].relative ],
		[ 'Large', report[ 3 ].absolute, report[ 3 ].relative ],
		[ 'Very large', report[ 4 ].absolute, report[ 4 ].relative ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-unitsize' ) );
	chart.draw( table, {
		'title': 'Unit size partitions for ' + dateTime.toString(),
		'colors': [ '#80ff00', '#ffff00', '#ff8000', '#df0101' ]
	} );
};

function drawDuplicationTimeChart( reports ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'datetime', 'Time' );
	table.addColumn( 'number', 'Total LOC' );
	table.addColumn( 'number', 'Duplicated LOC' );
	
	var rows = [],
		report;

	for ( var i = 0; i < reports.length; i++ ) {
		report = reports[ i ];
		rows.push( [
			new Date( report.dateTime ),
			report.volume.lines.absolute,
			report.duplication.duplications.absolute
		] );
	}
	
	table.addRows( rows );
	
	var chart = new google.visualization.LineChart( document.getElementById( 'chart-duplication-time' ) );
	chart.draw( table, {
		'title': 'Duplication over time',
		'pointSize': 5,
		'colors': [ '#80ff00', '#df0101' ]
	} );
	
	google.visualization.events.addListener( chart, 'select', function () {
		timeChartSelectionHandler( chart, reports, drawDuplicationChart, 'duplication.duplications' );
	} );
};

function drawDuplicationChart( dateTime, report ) {
	var table = new google.visualization.DataTable();
	table.addColumn( 'string', 'Slice' );
	table.addColumn( 'number', 'Lines' );
	
	table.addRows( [
		[ 'Duplications', report.absolute ],
		[ 'Original', Math.round( report.absolute / report.relative * 100 ) ]
	] );
	
	var chart = new google.visualization.PieChart( document.getElementById( 'chart-duplication' ) );
	chart.draw( table, {
		'title': 'Duplication for ' + dateTime.toString(),
		'colors': [ '#df0101', '#80ff00' ]
	} );
};

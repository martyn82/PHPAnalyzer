/**
 * main.js
 */

google.load( 'visualization', '1.0', { 'packages': ['corechart'] } );
google.setOnLoadCallback( function () {
	if ( !Report ) {
		throw Error( "No Report data!" );
	}
	
	console.log( Report );
	
	drawComplexityChart( Report.complexity, 'chart-complexity' );
	drawUnitSizeChart( Report.unitSize, 'chart-unitsize' );
	drawDuplicationChart( Report.duplication, Report.volume, 'chart-duplication' );
	
	drawRanksChart( Report, 'chart-ranks' );
} );

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

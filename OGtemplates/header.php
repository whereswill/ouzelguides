<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?> | OuzelGuides</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Ouzel Outfitters - Guide Portal System">
		<meta name="author" content="Will Sharp">

		<link type="text/css" href="/OGassets/css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="/OGassets/css/bootstrap3-glyphicons/bootstrap-glyphicons.css" rel="stylesheet">
		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.css">
		<!-- Custom style sheets -->
		<link rel='stylesheet' href='/ASLibrary/css/style3.css' type='text/css' media='all' />
		<link rel="stylesheet" type="text/css" media="screen" href="/OGassets/css/custom-styles.css">
		<link rel="stylesheet" type="text/css" media="print" href="/OGassets/css/print.css">
		<!-- JQuery -->
		<script type="text/javascript" charset="utf8" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<!-- DataTables -->
		<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
		<!-- Bootstrap 3 JS -->
		<script type="text/javascript" charset="utf8" src="/OGassets/js/bootstrap.min3.js"></script>
		<!-- add lines from Advanced Security -->
		<link href="/assets/css/bootstrap3-glyphicons/bootstrap-glyphicons.css" rel="stylesheet">
	  <script type="text/javascript" src="/assets/js/respond.min.js"></script>
    <script type="text/javascript" charset="utf-8">
        var $_lang = <?php echo ASLang::all(); ?>;
    </script>
		
		<script type="text/javascript">
		$( document ).ready(function() {
		
			window.setTimeout(function() {
			    $("#Alert").fadeTo(500, 0).slideUp(500, function(){
			        $(this).remove(); 
			    });
			}, 3000); //end alert
			
			$('.note_popup').each(function() {
			    var $this = $(this);
			    $this.popover({
			      trigger: 'hover'
			    });
			}); //end popup
		
			$('#sorted_table').dataTable( {
			  "columnDefs": [
			    { "orderable": false, "targets": -1 }
			  ],
			  "order": [ 1, 'asc' ]
			} ); 
		
			$('#sorted_table_1').dataTable( {
			  "columnDefs": [
			    { "orderable": false, "targets": -1 }
			  ],
			  "order": [ 0, 'asc' ]
			} ); 

			$('#sorted_table_2').dataTable( {
			  "columnDefs": [
			    { "orderable": false, "targets": -1 }
			  ],
			  "order": [ 3, 'desc' ]
			} );  

			$('#sorted_table_3').dataTable( {
			  "columnDefs": [
			    { "orderable": false, "targets": -1 }
			  ],
			  "order": [[ 5, 'desc' ], [ 2, 'asc' ]]
			} ); 

			$('#sorted_table_4').dataTable( {
			  "columnDefs": [
			    { "orderable": false, "targets": -1 }
			  ],
			  "order": [[ 6, 'desc' ], [ 2, 'asc' ]]
			} ); 

			$('#sorted_table_5').dataTable( {
				searching: false,
			  paging: false,
			  "columnDefs": [
			    { "orderable": false, "targets": 1 }
			  ],
			  "order": [[ 4, 'desc' ], [ 0, 'asc' ]]
			} ); 
			//end dataTable
			
			$('#piDate').valueAsDate = new Date()
			
			$("form").submit(function() {
			    $("input").removeAttr("disabled");
				$("select").removeAttr("disabled");
				$("textarea").removeAttr("disabled");
			});
		}); //end ready
		</script>
		
	</head>
	<body>
		<div id="wrap">
<?php
session_start();
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

if(isset($_SESSION['username'])){
	$email = $_SESSION['username'];
	$name = substr($_SESSION['fname'], 0, 1) . substr($_SESSION['lname'], 0, 1);
	$name = "<div class='login-icon'>$name</div>";
	$profile = "<a href='profile.php?email=$email'>Profile</a>";
	//$adminPage = "<a href='admin_board.php'>Add New Restaurant</a>";
	$logout = "<a href='logout.php' class='text-danger'>Logout</a>";
}
else{
	header('Location: index.php');
}
?>
<?php

session_start();

require 'db_connection.php';



// check if user is lloged in
$saved_items = [];
$liked_items = [];
$liked_items_array = [];
if(isset($_SESSION['username'])) {
	// get already saved items

	$result = mysqli_query($db, "SELECT item_id FROM saves WHERE user_email = '{$_SESSION['username']}'");
	while($row = mysqli_fetch_array($result))
	    $saved_items[] = $row['item_id'];

	$result = mysqli_query($db, "SELECT item_id FROM likes WHERE user_email = '{$_SESSION['username']}'");
	while($row = mysqli_fetch_array($result))
	    $liked_items[] = $row['item_id'];

	$result = mysqli_query($db, "SELECT item_id FROM likes");
	while($row = mysqli_fetch_array($result)){
	    $liked_items_array[] = $row['item_id'];
	}

}








?>
<html>
	<head>
	
	

		<title>Search Engine</title>
		  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>VisImageNavigator</title>

    <!-- Bootstrap core CSS -->
    <link href="public/stylesheets/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="public/stylesheets/bio_style.css" rel="stylesheet">
    <link href="public/stylesheets/myPagination.css" rel="stylesheet">
    <link rel="stylesheet" href="public/stylesheets/ion.rangeSlider.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
	</head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="js/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<style type="text/css">
		.login-icon {
			background-color: #0275d8;
			color: white;
			font-weight: bold;
			font-size: 20px;
			padding: 5px;
			text-align: center;
			border-radius: 20px;

		}
		.highlighted {
			background-color: yellow;
		}
		.item-card em {
			background-color: yellow;

		}
		
	</style>
	<script type="text/javascript">
	var recognition = new webkitSpeechRecognition();

	recognition.onresult = function(event) { 
	  var saidText = "";
	  for (var i = event.resultIndex; i < event.results.length; i++) {
	    if (event.results[i].isFinal) {
	      saidText = event.results[i][0].transcript;
	    } else {
	      saidText += event.results[i][0].transcript;
	    }
	  }
	  // Update Textbox value
	  document.getElementById('search').value = saidText;
	 
	  // Search Posts
	  // searchPosts(saidText);
	}

	function startRecording(){
	  recognition.start();
	}
	</script>

	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <a class="navbar-brand" href="add.php">add</a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav">
			  <li class="nav-item active">
				<a class="nav-link" href="index.php">Home</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="profile.php">Profile</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="search.php">Search</a>
			  </li>
			</ul>
		  </div>
		</nav>
	
		<div class="container">
		<div class="row py-5">
			<div class="col-10 mx-auto">
			<form action="search.php" method="get">
				<div class="row">
						<div class="col-10">
							<div class="input-group mb-3">
								<input type="text" name="q" value="<?php echo isset($_REQUEST['q']) ? $_REQUEST['q']: '' ?>" id="search" placeholder="search at josndata" class="form-control" required> 
								<button type='button' class="btn btn-sm " id='start' value='Start' onclick='startRecording();'>
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-mic" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
										<path fill-rule="evenodd" d="M10 8V3a2 2 0 1 0-4 0v5a2 2 0 1 0 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3z"/>
									</svg>
								</button>
							</div>
						</div>
						<div class="col-2">
							<button type="submit" class="btn btn-primary" name="searchButton">Search</button><br>
						</div>

			
					</div>
		
				<div  style="background: #F8F9F9; border-radius: 5px;">
					
				
						<div class="row py-2">
							<!-- <div class="col-6">
							
							
							
								
							
								<label>Search in datajosn by:</label>
								<select name="s" style=" height: 25px;cursor: pointer; " id="s">
								
								<option value="1" <?php echo isset($_REQUEST) && $_REQUEST['s'] == 1 ? 'selected': '' ?> >Description</option>
								<option value="2" <?php echo isset($_REQUEST) && $_REQUEST['s'] == 2 ? 'selected': '' ?> >Figure ID</option>
								
								
								</select>


		 
						
							</div> -->

							<div class="col-12">
								<div class="row">
									<div class="col-10">
										<div class="collapse" id="collapseFilter">
											<div class="card card-body">
												<label>Search catalogue by:</label>
												
												<label><input type="checkbox" name="columns[]" value="patentID" checked > Patent ID</label>
												<label><input type="checkbox" name="columns[]" value="pid" checked > P-ID</label>
												<label><input type="checkbox" name="columns[]" value="figid" checked > Fig ID</label>
												<label><input type="checkbox" name="columns[]" value="description" checked > Description</label>
												<label><input type="checkbox" name="columns[]" value="aspect" checked > Aspect</label>
											</div>
										</div>
										
									</div>
									<div class="col-2">
										<button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
											<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-funnel" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/></svg>
										</button>
									</div>

								</div>
							</div>
							
						</div>

						
					</form>
					<?php if($_REQUEST['q']) { ?>
						<h5>Showing results for <strong><?php echo htmlspecialchars($_REQUEST['q']) ?></strong></h5>
					<?php } ?>
				</div>
			</div>



		
<div class='col-10 mx-auto'>
<div class="row">
<?php
require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create() // (2)
->build(); // (3)

// pagination variables
$count = isset($_REQUEST['count']) ? $_REQUEST['count']: 6;
$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 1;
$from = $count * ($page-1);


if(isset($_REQUEST['searchButton'])) { // (4)

$q = $_REQUEST['q'];

$searchby=$_REQUEST['s'];



// $q=preg_replace("/<|>/i", "",$q);
// $q=preg_replace("/<script>/i", "",$q);
// $q=preg_replace("/<\/script>/i", "",$q);


//-- Change Here -->
$query = $client->search([
	'body' => [
		'track_total_hits' => true,
		'query' => [ // (5)
			// 'bool' => [
			// 	'should' => [
			// 		'match' => [
						
			// 			'description' => $q
			// 		],
			// 	]
			// ]
			'multi_match' => [
				'query' => $q,
				'fields' => $_REQUEST['columns'],
				'fuzziness' => 'AUTO',
			], 
			// 'match' => [
			// 	'description' => $q,
			// 	// 'fuzziness' => 'AUTO',
			// ]
			
		],
		'size' => $count,
		'from' => $from,
		'highlight' => [
			'fields' => [
				'description' => (object)[],
				'patentID' => (object)[],
				'pid' => (object)[],
				'aspect' => (object)[],
			]
		]
	]
]);


// $query = $client->search([
// 	'body' => [
// 		'query' => [ // (5)
// 			'terms' => [
// 				'_id' => ['2Mo0jHUBUz-b3eedNSkY', 'xMo0jHUBUz-b3eedOy69', 'Ico0jHUBUz-b3eedKwic']
// 			]
// 		],
// 		'size' => 2,
// 		'from' => 0
// 	]
// ]);

// $query = $client->search([
// 	'body' => [
// 		'query' => [ // (5)
// 			'bool' => [
// 				'should' => [
// 					'match' => ['patentID'  => 'USD0871742-20200107'],
// 					'match' => ['patentID'  => 'USD0873308-20200121']
// 				]
// 			]
// 		]
// 	]
// ]);






if($query['hits']['total'] >=1 ) { // (6)
$results = $query['hits']['hits'];

// pagiantion on results array
$results_total = count($results);
// echo '<pre>';
// var_dump($query['hits']['hits']);
// echo '</pre>';
// die();
$pages_count = ceil($query['hits']['total']['value'] / $count); // round up to get total pages

// $results = array_slice($results, $from, $count);

// highlight the items in search
$results = array_map(function($result) use ($q) {
	// $repalce = "<span class=\"highlighted\">{$q}</span>";
	// $result['_source']['description'] = ucfirst(str_replace(strtolower($q), $repalce, strtolower($result['_source']['description'])));
	// // var_dump($result['_source']['description']); 
	// // die();
	// $result['_source']['figid'] = ucfirst(str_replace(strtolower($q), $repalce, strtolower($result['_source']['figid'])));
	$result['_source']['description'] = isset($result['highlight']['description'][0]) ? $result['highlight']['description'][0]: $result['_source']['description'];
	return $result;
}, $results);


$x = 0;
 foreach ($results as $i)
 {
	 
	
	 


$qq=$i['_source']['patentID'];
$dd=$i['_source']['description'];

?>


	<?php include('inc/image.php'); ?>
	
	
	
	
<?php

	$x++;
 }
 





}
}


	if(isset($_RESQUEST['advanceSearchButton'])){
		$Rname = $_RESQUEST['Rname'];
		$Rtype = $_RESQUEST['Rtype'];
		$Rname=preg_replace("/<|>/i", "",$Rname);
		$Rtype=preg_replace("/<|>/i", "",$Rtype);



		//-- Change Here -->
		$query = $client->search([
		'body' => [
		'query' => [ // (5)
		'bool' => [
		'should' => [
		'match' => ['patentID'  => $Rname],
		'match' => ['aspect'  => $Rtype],


		]
		]
		]
		]
		]);
		if($query['hits']['total'] >=1 ) { // (6)
		$results = $query['hits']['hits'];
		$x = 0;
		 foreach ($results as $i)
		 {
			$qq=$i['_source']['patentID'];
		
		if($Rname !="" || $Rtype != ""){
		?>
			<div class="col-3"  style="border: 1px solid black">
				<a href='../jsonFiles/dataset/<?php echo "$qq"."-D0000".$x.".png"; ?>'>
					<img src='../jsonFiles/dataset/<?php echo "$qq"."-D0000".$x.".png"; ?>'  width="90%"  height="250px" />
				</a>
			</div>
			
		<?php
		}
		
			$x++;
		 }
		}
	}

?>
</div>
</div>
<?php

$query = $_GET;
// replace parameter(s)
$query['page'] = $page - 1;
// rebuild url
$query_result = http_build_query($query);
// new link
?>
<div class="col-10 mt-5 mx-auto">
	<nav aria-label="Page navigation example">
		<ul class="pagination">
			<li class="page-item <?php echo $page == 1 ? 'disabled': '' ?>">
				<!-- <a class="page-link" href="/webproject/search.php?q=<?php echo $q ?>&searchButton=&s=<?php echo $searchby ?>&count=<?php echo $count ?>&page=<?php echo $page - 1 ?>"  <?php echo $page == 1 ? 'tabindex="-1"': '' ?>> -->
				<a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $query_result; ?>"  <?php echo $page == 1 ? 'tabindex="-1"': '' ?>>
					Previous
				</a>
			</li>
			<?php for ($i=1; $i < $pages_count+1; $i++): ?>
				<li class="page-item <?php echo $i == $page ? 'active': '' ?>">
					<?php
					$query['page'] = $i;
					// rebuild url
					$query_result = http_build_query($query);
					?>
					<!-- <a class="page-link" href="/webproject/search.php?q=<?php echo $q ?>&searchButton=&s=<?php echo $searchby ?>&count=<?php echo $count ?>&page=<?php echo $i ?>"> -->
					<a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $query_result; ?>">
						<?php echo $i ?>
					</a>
				</li>
			<?php endfor; ?>
			<li class="page-item <?php echo $page == $pages_count ? 'disabled': '' ?>">
				<?php
				$query['page'] = $page + 1;
				// rebuild url
				$query_result = http_build_query($query);
				?>
				<a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $query_result; ?>" <?php echo $page == 1 ? 'tabindex="-1"': '' ?>>
					Next
				</a>
			</li>
		</ul>
	</nav>
	<p>Total results: <?php echo $results_total ?></p>
	<p>Page: <?php echo $page ?> of <?php echo $pages_count ?></p>
</div>
</div>

<?php include('inc/item-modal.php') ?>
<script src="js/app.js"></script>
  
</body>
</html>

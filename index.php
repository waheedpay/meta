<?php $q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));
$search=$_GET['search']; ?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="/image/favicon.ico" type="image/x-icon">
<link rel="icon" href="/image/favicon.ico" type="image/x-icon">
<title>Meta Search - Cragglist.uphero.com</title>
<meta name="author" content="vlul.co.uk,cragglist.uphero.com">
<meta name="description" content="free calculator/meta search engine salution for every one easy an da quick instulation">
<meta name="keywords" content="calculator,meta search engine, free salution, all-in one calculation, fast and easy instulation">
<link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="style.css?v=2" rel="stylesheet" type="text/css">
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style type="text/css">
.navigation {
	<? if(str_replace(" ","",$q)=="") {
echo 'margin-top: 15%;
text-align: center;';
	}
	else{
		echo 'background: #f2f2f2;
		border-bottom: 1px solid #e4e4e4;
    padding: 12px 10px;';
	}
	?>
}
<? if($q==""  or $q==" ") {
echo 'footer {
	position:absolute;
	left:0;
	right:0;
	bottom:0;
	} nav {
	position:absolute;
	left:0;
	right:0;
	top:0;
border:0
	}
.logo {
padding: 17px;
display: block;
}
input.input-text {
width: 40%;
}';
	}
if($search=="images") {
echo '.img_result_main {
max-height: 100%;
overflow-y: hidden;
	}';
	}
	?>

</style>
</head>
<body>
<div class="navigation">
<form>
<div class="logo"><a href="/"><img src="/image/logo_main.png" alt="logo"></a></div><input type="search" class="input-text" name="q" value="<? echo $q ?>" /><input type="hidden" name="search" value="<? echo $search; ?>"><input type="submit" class="g-button" value="Search" />
</form>
</div>
<nav>
    <li><a href="?q=<? echo $q ?>&search=web">Web</a></li>
    <li><a href="?q=<? echo $q ?>&search=images">Image</a></li>
    <li><a href="?q=<? echo $q ?>&search=videos">Video</a></li>
  </nav>
<? error_reporting(0);
	require_once("functions.php");
	// now have some fun with the results...
	if($q==""  or $q==" ") {
	} else {
		$page = preg_replace('/[^-0-9]/', '', $_GET['page']);
		
		if($page=="" or $page==" ") {
			$page="1";
		}
$previous=$page-1;
$next=$page+1;
$total_pages="100";
$qe=urlencode($q);
$limit=12;
$start = ($page-1) * $limit;
if($search =="images") {
$results = array_merge(image($qe,$start), image($qe,$start+4),image($qe,$start+8),image($qe,$start+12));
echo '<ol class="img_result_main">';
			foreach ($results as $result) {
echo '<li class="video_results">
		<a href="'.$result['content'].'"><img class="thumbnail" src="'.$result['url'].'"></a>
		</br>
        <a class="title" href="'.$result['content'].'">'.$result['alt'].'</a>
      </li>';
			}
			echo '</ol>
			<div class="pagination">';
			if ($page > 1) {
				echo '<a class="page-button" href="?q='.$qe.'&page='.$previous.'&search='.$search.'">Previous</a>';
			}
			for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
				echo '<a class="page-button" href="?q='.$qe.'&page='.$i.'&search='.$search.'">'.$i.'</a>';
			}

			echo ' <a class="page-button" href="?q='.$qe.'&page='.$next.'&search='.$search.'">Next</a>';
		echo'</div>';
}
else if($search =="videos") {
$vidlimit="21";
video($qe,intval(($page-1)*$vidlimit),$vidlimit);
			echo '<div class="pagination">';
			if ($page > 1) {
				echo '<a class="page-button" href="?q='.$qe.'&page='.$previous.'&search='.$search.'">Previous</a>';
			}
			for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
				echo '<a class="page-button" href="?q='.$qe.'&page='.$i.'&search='.$search.'">'.$i.'</a>';
			}
			echo ' <a class="page-button" href="?q='.$qe.'&page='.$next.'&search='.$search.'">Next</a>';
		echo'</div>';
}
else {
$limit=12;
$start = ($page-1) * $limit;
			$results = array_merge(image($qe,$start), image($qe,$start+4),image($qe,$start+8));
echo '<ol class="img_result_main">';
			foreach ($results as $result) {
echo '<li class="video_results">
		<a href="'.$result['content'].'"><img class="thumbnail" src="'.$result['url'].'"></a>
      </li>';
}
echo "</ol>";
echo'<div class="results_container">';
			$results = array_merge(web($qe,$start), web($qe,$start+4),web($qe,$start+8));
			foreach ($results as $result) {
				echo'<li class="results"><h3><a href="'.$result['url'].'">'.$result['title'].'</a></h3>
		<cite>'.$result['visibleUrl'].'</cite><br>
		<span class="st">'.$result['abstract'].'</span></li>';
			}
			
			if (count($results) < $pageslimit) {
				echo '<li class="results">Oops!, You\'ve reached the end of the results.</li>';
			}

			echo '</ol>';
$vidlimit="6";
video($qe,intval(($page-1)*$vidlimit),$vidlimit);
			echo '<div class="pagination">';
			if ($page > 1) {
				echo '<a class="page-button" href="?q='.$qe.'&page='.$previous.'&search='.$search.'">Previous</a>';
			}
			for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
				echo '<a class="page-button" href="?q='.$qe.'&page='.$i.'&search='.$search.'">'.$i.'</a>';
			}
			echo ' <a class="page-button" href="?q='.$qe.'&page='.$next.'&search='.$search.'">Next</a>';
		echo'</div>
</div>';
}
}

?>
<footer>Powered by <a href="http://cragglist.uphero.com/">Cragglist</a></footer>
</body>
</html>
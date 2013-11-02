<?php 
	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));
	$qw=preg_replace("/[^A-Za-z0-9 ]/", "", $q);

	$search=$_GET['search'];
	require_once("header.php");
	?>
<style type="text/css">
.navigation {
<? if(str_replace(" ","",$qw)=="") {
		echo 'margin-top: 15%;
text-align: center;';
	} else {
		echo 'background: #f2f2f2;
border-bottom: 1px solid #e4e4e4;
    padding: 12px 10px;
position:fixed;
top:0;
left:0;
right:0;';
	}
	?>
}
<? if(str_replace(" ","",$qw)=="") {
	echo 'footer {
	position:absolute;
	left:0;
	right:0;
	bottom:0;
	} nav {
margin:0;
	}
.logo {
padding: 17px;
display: block;
}
input.input-text {
width: 40%;
}';
	} 
	?>
</style>
<body>
<nav>
    <li><a href="?q=<? echo $q  ?>&search=web">Web</a></li>
    <li><a href="?q=<? echo $q  ?>&search=images">Image</a></li>
    <li><a href="?q=<? echo $q  ?>&search=videos">Video</a></li>
    <li><a href="?q=<? echo $q  ?>&search=news">News</a></li>
  </nav>
<div class="navigation">
<form>
<div class="logo"><a href="/"><img src="/image/logo_main.png" alt="logo"></a></div><input type="search" class="input-text" name="q" value="<? echo $q  ?>" id="search" /><input type="hidden" name="search" value="<? echo $search; ?>"><input type="submit" class="g-button" value="Search" />
</form>
</div>
<?	error_reporting(0);
	require_once("functions.php");
	// now have some fun with the results...
	
	if(str_replace(" ","",$qw)=="") {
	} else {
		$page = preg_replace('/[^-0-9]/', '', $_GET['page']);
		
		if($page=="" or $page==" ") {
			$page="1";
		}
		$qe=urlencode($q);
		$qs=urlencode($qw);
		$limit=12;
		$start = ($page-1) * $limit;
		
		if($search=="images" || $search=="videos" || $search=="news") {
			echo '<ol class="vid_result_main">';
			
			if($search =="images") {
				image_str(array_merge(image($qs,$start+1), image($qs,$start+5),image($qs,$start+9),image($qs,$start+13)));
			} else
			if($search =="videos") {
				video($qs,$page,21);
			} else
			if($search =="news") {
				news($qs,$page,21);
			}
			echo '</ol>';
			pagination('?q='.$qe.'&search='.$search);
			} else
			if($search =="weather") {
 			 weather();
			} else {
			$limit=12;
			$start = ($page-1) * $limit;
			echo '<ol class="img_result_main">';
			image_str(array_merge(image($qs,$start+1), image($qs,$start+5),image($qs,$start+9)));
			echo "</ol>";
			echo'<div class="results_container">';
?>
<script type="text/javascript"> 
  ( function() {
    if (window.CHITIKA === undefined) {
      window.CHITIKA = { 'units' : [] };
    };
    var unit = {
      'fluidH' : 1,
      'nump' : "2",
      'publisher' : "waheedpay",
      'width' : 528,
      'height' : "auto",
      'type' : "mpu",
      'sid' : "Chitika Default",
      'color_site_link' : "0000CC",
      'color_title' : "0000CC",
      'color_border' : "FFFFFF",
      'color_text' : "000000",
      'color_bg' : "FFFFFF"
    };
    var placement_id = window.CHITIKA.units.length;
    window.CHITIKA.units.push(unit);
    document.write('<div id="chitikaAdBlock-' + placement_id + '"></div>');
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = 'http://scripts.chitika.net/getads.js';
    try {
      document.getElementsByTagName('head')[0].appendChild(s);
    } catch(e) {
      document.write(s.outerHTML);
    }
}());
</script>
<?
web_str(array_merge(web($qs,$start), web($qs,$start+4),web($qs,$start+8)));
			if (count($results) < $pageslimit) {
				echo '<li class="results">Oops!, You\'ve reached the end of the results.</li>';
			}

			echo '</ol>';
			video($qs,$page,6);
		pagination('?q='.$qe.'&search='.$search);
			echo'</div>';
		}

	}

	require_once("footer.php");
	?>
</body>
</html>
<?php
	function getRealIpAddr() {
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	
	function gethtml($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, "http://cragglist.uphero.com Metasearch/1.0" );
		$body = curl_exec($ch);
		curl_close($ch);
		return $body ;
	}

	
	function web($q,$start) {
		$json = gethtml("https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".$q."&as_q=".$q."&userip=".getRealIpAddr()."&start=".$start);
		$data = json_decode($json);
		foreach ($data->responseData->results as $result) {
			$results[] = array('visibleUrl' => $result->visibleUrl, 'url' => $result->url, 'title' => $result->title, 'abstract' => $result->content);
		}

		return $results;
	}

	
	function image($q,$start){
		$json = gethtml("http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$q."&start=".$start);
		$data = json_decode($json);
		foreach ($data->responseData->results as $result) {
			$results[] = array('url' => $result->url, 'alt' => $result->title, 'content' => $result->originalContextUrl);
		}

		return $results;
	}

	
	function video($q,$page,$limit) {
		$start=intval(intval($page-1)*$limit);
		// set feed URL
		$feedURL = 'http://gdata.youtube.com/feeds/api/videos?q='.$q.'&orderby=published&start-index='.intval($start+1).'&max-results='.$limit;
		// read feed into SimpleXML object
		$sxml = simplexml_load_file($feedURL);
		// iterate over entries in feed
		foreach ($sxml->entry as $entry) {
			// get nodes in media: namespace for media information
			$media = $entry->children('http://search.yahoo.com/mrss/');
			// get video player URL
			$attrs = $media->group->player->attributes();
			$watch = $attrs['url'];
			// get video thumbnail
			$attrs = $media->group->thumbnail[0]->attributes();
			$thumbnail = $attrs['url'];
			// get <yt:stats> node for viewer statistics
			$yt = $entry->children('http://gdata.youtube.com/schemas/2007');
			
			if ($yt->statistics) {
				$attrs = $yt->statistics->attributes();
				$views = $attrs['viewCount'];
			} else {
				$views = 0;
			}

			// get <gd:rating> node for video ratings
			$gd = $entry->children('http://schemas.google.com/g/2005');
			
			if ($gd->rating) {
				$attrs = $gd->rating->attributes();
				$rating = $attrs['average'];
			} else {
				$rating = 0;
			}

			echo '<li class="video_results">
                <a href="'.$watch.'"><img class="thumbnail" src="'.$thumbnail.'" /></a>
                </br>
        <a class="title" href="'.$watch.'">'.$media->group->title.'</a>
</br>
        </br>
<span class="attr">by : </span>'.$entry->author->name.' '.$views.' <span class="attr">views</span>
      </li>';
		}

	}

	
	function news($q,$page,$limit) {
		$start=intval(($page-1)*$limit);
		$news = simplexml_load_file('https://news.google.com/news/feeds?q='.$q.'&pz=1&cf=all&ned=uk&hl=en&output=rss&start='.intval($start+1).'&num='.$limit);
		$i = 0;
		foreach ($news->channel->item as $item) {
			preg_match('@src="([^"]+)"@', $item->description, $match);
			$parts = explode('<font size="-1">', $item->description);
			echo '<li class="video_results">
                <a href="'.$item->link.'"><img class="thumbnail" src="'.$match[1].'" /></a>
                </br>
        <a class="title" href="'.$item->link.'">'.$item->title.'</a>
</br>
        </br>'.strip_tags($parts[1]).strip_tags($parts[2]).'
</li>';
			$i++;
		}

	}
function weather() {
//~ http://api.worldweatheronline.com/free/v1/weather.ashx?key=xxxxxxxxxxxxxxxxx&q=SW1&num_of_days=3&format=json
//This cURL example requires php_curl. To verify installion,  phpinfo();
//Failure to support cURL results in:   PHP Fatal error:  Call to undefined function curl_init() 

//Minimum request
//Can be city,state,country, zip/postal code, IP address, longtitude/latitude. If long/lat are 2 elements, they will be assembled. IP address is one element.
$loc_array= Array("New York","ny");		//data validated in foreach. 
$api_key="xkq544hkar4m69qujdgujn7w";		//should be embedded in your code, so no data validation necessary, otherwise if(strlen($api_key)!=24)
$num_of_days=2;					//data validated in sprintf

$loc_safe=Array();
foreach($loc_array as $loc){
	$loc_safe[]= urlencode($loc);
}
$loc_string=implode(",", $loc_safe);

//To add more conditions to the query, just lengthen the url string
$basicurl=sprintf('http://api.worldweatheronline.com/free/v1/weather.ashx?key=%s&q=%s&num_of_days=%s&format=json', 
	$api_key, $loc_string, intval($num_of_days));

print $basicurl . "<br />\n";

//Premium API
$premiumurl=sprintf('http://api.worldweatheronline.com/premium/v1/premium-weather-V2.ashx?key=%s&q=%s&num_of_days=%s&format=json', 
	$api_key, $loc_string, intval($num_of_days));


$json_reply=gethtml($basicurl);
$json=json_decode($json_reply);
printf("<p>Current wind speed is %s mph blowing to %s</p>", 
	//~ $json->{'data'}->{'current_condition'}->{'windspeedMiles'}, $json->{'data'}->{'current_condition'}->{'winddir16Point'} );
	$json->{'data'}->{'current_condition'}['0']->{'windspeedMiles'}, 
	$json->{'data'}->{'current_condition'}['0']->{'winddir16Point'} );
//~ print "<script>var weather= JSON.parse(";
//~ print $json_reply;
//~ print ");</script>";

print "<pre>";
print_r($json);
print "</pre>";
}
function pagination($url) {
$previous=$page-1;
		$next=$page+1;
		$total_pages="100";
	echo '<div class="pagination">';
			
			if ($page > 1) {
				echo '<a class="page-button" href="'.$url.'&page='.$previous.'">Previous</a>';
			}

			for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
				echo '<a class="page-button" href="'.$url.'&page='.$i.'">'.$i.'</a>';
			}

			echo ' <a class="page-button" href="'.$url.'&page='.$next.'">Next</a>';
			echo'</div>';
}
function web_str($results) {
foreach ($results as $result) {
				echo'<li class="results"><h3><a href="'.urldecode($result['url']).'">'.$result['title'].'</a></h3>
		<cite>'.$result['visibleUrl'].'</cite><br>
		<span class="st">'.$result['abstract'].'</span></li>';
			}
}
function image_str($results) {
foreach ($results as $result) {
					echo '<li class="video_results">
		<a href="'.$result['content'].'"><img class="thumbnail" src="'.$result['url'].'"></a>
		</br>
        <a class="title" href="'.$result['content'].'">'.$result['alt'].'</a>
      </li>';
}
}

	?>
<?php
function getRealIpAddr() {
		//check ip from share internet
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}

		//to check ip is pass from proxy else 
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
		$json = gethtml("https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".$q."&userip=".getRealIpAddr()."&start=".$start); 
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
function video($q,$start,$limit) {
// set feed URL
    $feedURL = 'http://gdata.youtube.com/feeds/api/videos?q='.$q.'&orderby=published&start-index='.intval($start+1).'&max-results='.$limit;
    // read feed into SimpleXML object
    $sxml = simplexml_load_file($feedURL);
echo '<ol class="vid_result_main">';
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
<span class="attr">by : </span>'.$entry->author->name.' '.$views.' <span class="attr">views</span>
      </li>';    
}
echo '</ol>';
}
?>
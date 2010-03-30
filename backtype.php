<?php
/**
 * A simple wrapper for connecting to the BackType API
 *
 * @author Jason Priem <jason@jasonpriem.com>
 * @version 1.0
 * 
 * Usage:
 * 1. Include the backtype.php file at the head of your script.
 * 2. Instantiate the backtype object, passing in your API key:
 *		$key = <your backtype api key>
 *		$responseFormat = <"json" or "xml"> //optional; defaults to json
 *		$itemsPerPage = <1-100> // optional; defaults to 25
 *		$bt = new backtype($key, $responseFormat, $itemsPerPage)
 * 3. Call the methods you want to get your data.  These are documented below,
 *		and on the backtweets api docs.  Here's an example of getting the number
 *		of comments per page:
 *		$url = "http://blog.backtype.com/2009/12/tweetcount-updated-supports-wordpress-2-9-bitly-pro/";
 *		echo $bt->comments_by_page($url);
 * 
 * This is the first wrapper like this I've written, so I'm sure it's not perfect.
 * If you have any trouble, let me know and I'll try to fix it.  Enjoy, and
 * thanks to the folks at BackTweet for the cool API!
 * 
 * 
 */
class backtype {
   private $apiKey;
   private $itemsPerPage;
   private $responseFormat;


   /**
    * Default constructor
    * See {@link http://www.backtype.com/developers} for additional documentation
    *
    * @param string $apiKey api key from backtype
    * @param string $responseFormat format for results; either "json" or "xml" (defaults to "json")
    * @param integer $itemsPerPage number of items returned on each page (10-100; default is 25)
    */
   public function __construct($apiKey, $responseFormat="json", $itemsPerPage=25) {
      $this->apiKey = $apiKey;
      $this->responseFormat = $responseFormat;
      $this->itemsPerPage = $itemsPerPage;
   }


   /**
    * Set number of items per page
    *
    * @param integer $itemsPerPage number of items returned on each page (10-100)
    */
   public function setItemsPerPage($itemsPerPage) {
      $this->itemsPerPage = $itemsPerPage;
   }


   /**
    * Set the response format (json or xml)
    *
    * @param string $responseFormat format for results; either "json" or "xml"
    */
   public function setResponseFormat($responseFormat) {
      $this->responseFormat = $responseFormat;
   }


   /**
    * Search all the comments on BackType for a given string.
    * See {@link http://www.backtype.com/developers/comments-search} for documentation
    *
    * @param string $q The query string you want to search comments for; supports AND OR NOT and advanced expressions
    * @param integer $page [optional] which page of results to retrieve; default is 1
    * @param string $start [optional] "YYYY/MM/DD"; search for comments made after this date
    * @param string $end, formatted as [optional] "YYYY/MM/DD"; search for comments made before this date. (To set $end but not $start, pass NULL as the second argument)
    */
   public function commentsSearch($q, $page=FALSE, $start=FALSE, $end=FALSE) {
      $base = "http://api.backtype.com/comments/search";
      $uri = $this->makeUri($base,
       array(
       "q" => $q,
       "page" => $page,
       "start" => $start,
       "end" => $end
      ));
      return $this->curlGet($uri);
   }


   /**
    * Retrieve all conversations related to a given URL.
    * See {@link http://www.backtype.com/developers/comments-connect} for documentation
    *
    * @param string $url The URL you want related conversations for
    * @param integer $page [optional] which page of results to retrieve; default is 1
    * @param string $sources  [optional] A comma-delimited list of source titles (native, blog, digg, reddit, yc, friendfeed, twitter) or blog IDs.  Default is all.
    * @param string $sort  [optional] If set to 1, results will be ordered by the date they were found by BackType.
    */
   public function commentsConnect($url, $page=FALSE, $sources=FALSE, $sort=FALSE) {
      $base = "http://api.backtype.com/comments/connect";
      $uri = $this->makeUri($base,
       array(
       "url" => $url,
       "page" => $page,
       "sources" => $sources,
       "sort" => $sort
      ));
      return $this->curlGet($uri);
   }


   /**
    * Get statistics for a URL.
    * See {@link http://www.backtype.com/developers/comments-connect-stats} for documentation
    * Note that, unlike with comments_connect, it seems only a fully-qualified url
    *	 will work here; no domains.
    * @param string $url  The URL you want related conversations for
    * @param integer $page  [optional] which page of results to retrieve
    */
   public function commentsConnectStats($url, $page=FALSE) {
      $base = "http://api.backtype.com/comments/connect/stats";
      $uri = $this->makeUri($base,
       array(
       "url" => $url,
       "page"=>$page
      ));
      echo $uri;
      return $this->curlGet($uri);
   }


   /**
    * Get comments by specific author.
    * See {@link http://www.backtype.com/developers/url-comments} for documentation.
    * I'm not sure where the url comes from; I'm guessing a comment-author's homepage,
    *	 but I haven't tested it.
    *
    * @todo Figure out how this works.  What does the url refer to?
    * @param string $url  The URL for the author?  
    *
    * @param integer $page  [optional] which page of results to retrieve
    */
   public function commentsByAuthor($url, $page=FALSE) {
      $base = 'http://api.backtype.com/url/' . $url . ' /comments';
      $uri = $this->makeUri($base,
       array(
       "page"=>$page
      ));
      echo $uri;
      return $this->curlGet($uri);
   }


   /**
    * Retrieve excerpts of comments published on a particular page.
    * See {@link http://www.backtype.com/developers/page-comments} for documentation
    *
    * @param string $url  The post url to return comments for
    * @param integer $page  [optional] which page of results to retrieve
    */
   public function commentsByPage($url, $page=FALSE) {
      $base = "http://api.backtype.com/post/comments";
      $uri = $this->makeUri($base,
       array(
       "url" => $url,
       "page"=>$page
      ));

      return $this->curlGet($uri);
   }


   /**
    * Retrieve statistics for the comments published on a particular page.
    * See {@link http://www.backtype.com/developers/page-comments-stats} for documentation
    *
    * @param string $url  The post url to return stats for
    *
    * @param integer $page  [optional] which page of results to retrieve
    */
   public function commentStats($url, $page=FALSE) {
      $base = "http://api.backtype.com/post/stats";
      $uri = $this->makeUri($base,
       array(
       "url" => $url,
       "page"=>$page
      ));

      return $this->curlGet($uri);
   }


   /**
    * Retrieve the number of tweets that link to a particular URL.
    * See {@link http://www.backtype.com/developers/tweet-count} for documentation
    * In testing batch mode, I'd get back results for a max of 14; 
    * I'm not sure if that's consistant
    *
    * @param string $q  The url to return number of tweets for.  In batch mode (see below), a comma-delimited string of multiple urls. 
    * @param integer $page [optional] which page of results to retrieve
    * @param bool $batch [optional] Whether or not to get counts for multiple URLs
    */
   public function tweetStats($q, $page=FALSE, $batch=FALSE) {
      $batch = ($batch) ? "batch" : FALSE;
      $base = "http://api.backtype.com/tweetcount";
      $uri = $this->makeUri($base,
       array(
       "q" => $q,
       "page" => $page,
       "mode" => $batch
      ));

      return $this->curlGet($uri);
   }

   /**
    * Retrieve tweets that link to a given URL, whether the links are shortened or unshortened.
    * See {@link http://www.backtype.com/developers/tweets-by-url} for documentation
    *
    * @param string $q  The url to return number of tweets for.
    * @param integer $page  [optional] which page of results to retrieve
    */
   public function tweetsByUrl($q, $page=FALSE) {
      $base = "http://api.backtype.com/tweets/search/links";
      $uri = $this->makeUri($base,
       array(
       "q" => $q,
       "page" => $page
      ));

      return $this->curlGet($uri);
   }


   /**
    * Retrieve filtered tweets that link to a given URL with both shortened and unshortened links. This returns a subset of tweets_by_url.
    * See {@link http://www.backtype.com/developers/tweets-by-url} for documentation
    *
    * @param string $q  The url to return number of tweets for.
    *
    * @param integer $page  [optional] which page of results to retrieve
    */
   public function goodtweets($q, $page=FALSE) {
      $base = "http://api.backtype.com/goodtweets";
      $uri = $this->makeUri($base,
       array(
       "q" => $q,
       "page" => $page
      ));

      return $this->curlGet($uri);
   }


   /**
    * Constructs a BackType api request string
    * Uses the $key and $items_per_page properties, as well as arguments for base url and options
    * In the $opts param, the array gets turned into string, like this:
    *  array[ k1=>v1, k2=>v2 ... ] becomes "k1=v1&k2=v2..."; FALSE values are dropped
    *
    * @param string $base   The base url the arguments are going to be sent to.
    * @param array $opts A list of arguments to send along with the base url.
    */
   private function makeUri($base, $opts) {
      $uri = $base
       . '.' . $this->responseFormat
       . '?key=' . $this->apiKey
       . '&itemsperpage=' . $this->itemsPerPage;

      foreach ($opts as $k => $v) {
	if ($v !== FALSE && $v !== NULL) {  // zero is a valid value
	   $uri .= '&' . $k . '=' . $v;
	}
      }
      return $uri;
   }
   
   /**
    * Sends a GET request to URL, returns response
    * Returns FALSE if an error or empty page is returned.
    *
    * @param string url
    * @return string|bool The response from the GET request; FALSE on error
    */
   private function curlGet($url){
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
      curl_setopt($ch, CURLOPT_FAILONERROR, 1);  
      $reply = curl_exec($ch);
      curl_close($ch);
       if ($reply){
         return $reply;	  
       }
       else {
	  return FALSE;
       }

   }












}

?>

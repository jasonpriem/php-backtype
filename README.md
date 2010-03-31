# Usage:

1. Include the backtype.php file at the head of your script.
2. Instantiate the backtype object, passing in your API key:
    $key = <your backtype api key>
    $responseFormat = <"json" or "xml"> //optional; defaults to json
    $itemsPerPage = <1-100> // optional; defaults to 25
    $bt = new backtype($key, $responseFormat, $itemsPerPage)
3. Call the methods you want to get your data.  These are documented below, and on the [backtweets api docs](http://www.backtype.com/developers).  Here's an example of getting the number of comments per page:
    $url = "http://blog.backtype.com/2009/12/tweetcount-updated-supports-wordpress-2-9-bitly-pro/";
    echo $bt->comments_by_page($url);

This is the first wrapper like this I've written, so I'm sure it's not perfect. If you have any trouble, let me know and I'll try to fix it.  Enjoy, and thanks to the folks at BackTweet for the cool API!

License: [MIT](http://www.opensource.org/licenses/mit-license.php)
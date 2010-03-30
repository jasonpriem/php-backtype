<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>php-backtype</title>
    </head>
    <body>
        <?php
	include("./backtype.php");
	$bt = new backtype("key");

	// echo $bt->comments_search("backtype", NULL, "2010/01/01", "2010/02/01");
	// echo $bt->comments_connect("plosone123.org");
	// echo $bt->comments_connect_stats("http://www.plosone.org/article/info:doi/10.1371/journal.pone.0005723")
	// echo $bt->tweet_stats("http://www.plosone.org/article/info:doi/10.1371/journal.pone.0005723");
	// echo $bt->tweets_by_url("plosone.org");
	// echo $bt->good_tweets('plosone.org');
        ?>
    </body>
</html>


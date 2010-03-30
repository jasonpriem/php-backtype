<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../backtype.php';

/**
 * Test class for backtype.
 * Notice that I'm testing using live data from the api.  This changes from
 * day-to-day. However, I think the URLs I've used are pretty reliable in
 * returning *some* data anyway.  I've used the online journal PLoS ONE,
 * and the backtype blog as sources.
 *
 * Also notice that I take a bit of a shortcut and just test the return string
 * for keywords, rather than loading it as an object using json_decode; I'm
 * trusting BackType that it's valid.
 */
class backtypeTest extends PHPUnit_Framework_TestCase {
   /**
    * @var backtype
    */
   protected $object;


   protected function setUp() {
      $this->object = new backtype("key");
		$this->testUrl = "http://www.plosone.org/article/info:doi/10.1371/journal.pone.0005723";
		$this->testBlogPostUrl = "http://blog.backtype.com/2009/12/tweetcount-updated-supports-wordpress-2-9-bitly-pro/";
		$this->testDomain = "backtype.com";
		$this->testSearchTerm = "backtype";
   }


   public function testCommentsSearchGetsComments() {
      $this->assertContains(
	      'comments":[{',
	      $this->object->commentsSearch($this->testSearchTerm)
      );
   }


   public function testCommentsConnectByDomainGetsComments() {
      $this->assertContains( 
	      'comments":[{', 
	      $this->object->commentsConnect($this->testDomain)
      );
	}

   public function testCommentsConnectByUrlGetsComments() {
      $this->assertContains( 
	      'comments":[{', 
	      $this->object->commentsConnect($this->testUrl)
      );      
   }

   public function testCommentsConnectStatsGetsSomeStats() {
      $this->assertContains( 
	      "stats",
	      $this->object->commentsConnectStats($this->testUrl)
      );
   }

   /**
    * @todo Implement testComments_by_author().
    */
   public function testComments_by_author() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
	      'This test has not been implemented yet.'
      );
   }

   public function testCommentsByPageGetsComments() {
      $this->assertContains(
	      'comments":[{', 
	      $this->object->commentsByPage($this->testBlogPostUrl)
      );
   }

   public function testCommentStatsGetsStats() {
      $this->assertContains( 
	      "stats",
	      $this->object->commentsConnectStats($this->testUrl)
      );
   }

   public function testTweetStatsByUrlGetsTweetcount() {
      $this->assertContains( // test for one url
	      '"tweetcount"', // notice this is singular.  
	      $this->object->tweetStats($this->testUrl)
      );
	}

   public function testTweetStatsByMultiUrlsGetsTweetcounts() {
      $this->assertContains( // test for multiple urls
	      '"tweetcounts"', // notice this is plural
	      $this->object->tweetStats(
					  $this->testUrl . ',' .
					  "http://www.google.com",
					  FALSE,1)
      );
   }

   public function testTweetsByUrlGetsTweet() {
      $this->assertContains(
	      '"tweet_id"',
	      $this->object->tweetsByUrl($this->testDomain)
      );
   }

   public function testGoodtweetsGetsTweet() {
      $this->assertContains(
	      '"tweet_id"',
	      $this->object->goodtweets($this->testDomain)
      );
   }
}
?>

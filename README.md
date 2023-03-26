<h1>Inshorts News Scraper - Scraper PHP Version</h1>
<p>This is a PHP version of the Inshorts News Scraper, a module that scrapes news articles from the popular news aggregator Inshorts. This module utilizes the Symfony DomCrawler library to parse HTML and make HTTP requests with the GuzzleHttp library.</p>
<h2>Features</h2>
<ul>
  <li>Scrapes news articles from Inshorts</li>
  <li>Retrieves the title, image, author, content, posted time, and source URL for each article</li>
  <li>Ability to get more news articles using the 'getMoreNews' function</li>
  <li>Customizable language and news category options</li>
</ul>
<h2>Usage</h2>
<p>Install the module using Composer:</p>
<pre><code>composer require harshitethic/inshorts-news-scraper-php</code></pre>
<p>Then, use the module in your PHP code:</p>
<pre><code>&lt;?php
require_once 'vendor/autoload.php';

use Inshorts\NewsScraper;

// Create a new instance of the NewsScraper class
$scraper = new NewsScraper();

// Get news articles for the 'national' category in English
$news = $scraper->getNews([
    'lang' =&gt; 'en',
    'category' =&gt; 'national'
]);

// Get more news articles using the news_offset value from the previous call
$moreNews = $scraper->getMoreNews([
    'lang' =&gt; 'en',
    'category' =&gt; 'national',
    'news_offset' =&gt; $news['news_offset']
]);

// Print the news articles
print_r($news);
print_r($moreNews);
?&gt;</code></pre>
<p>You can also pass a callback function to the 'getNews' and 'getMoreNews' functions instead of returning the news articles:</p>
<pre><code>&lt;?php
require_once 'vendor/autoload.php';

use Inshorts\NewsScraper;

// Create a new instance of the NewsScraper class
$scraper = new NewsScraper();

// Get news articles for the 'national' category in English
$scraper->getNews([
    'lang' =&gt; 'en',
    'category' =&gt; 'national'
], function($news, $news_offset) {
    // Do something with the news articles
});

// Get more news articles using the news_offset value from the previous call
$scraper->getMoreNews([
    'lang' =&gt; 'en',
    'category' =&gt; 'national',
    'news_offset' =&gt; $news['news_offset']
], function($news, $news_offset) {
    // Do something with the news articles
});
?&gt;</code></pre>
<h2>Credits</h2>
<p>This module was written by <a href="https://harshitethic.in/">Harshit Sharma</a>, also known as harshitethic.</p>

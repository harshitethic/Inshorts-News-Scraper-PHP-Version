<?php

use Symfony\Component\DomCrawler\Crawler;

function getNews($options, $callback) {
    $URL = "https://inshorts.com/" . $options['lang'] . "/read/" . $options['category'];
    $response = fetchUrl($URL);
    $body = $response->getBody()->getContents();
    $posts = [];
    $crawler = new Crawler($body);
    $scriptData = $crawler->filter('script')->last()->html();
    preg_match('/var min_news_id = (.*);/', $scriptData, $id);
    $newsOffsetId = explode('\"', $id[1], 3);
    $news_offset = $newsOffsetId[1];
    $crawler->filter('.news-card')->each(function (Crawler $element, $i) use (&$posts, $URL) {
        $title = $element->filter('div.news-card-title a.clickable span')->text();
        $image = preg_match("/'(.*?)'/", $element->filter('.news-card-image')->attr('style'), $match) ? $match[1] : '';
        $author = $element->filter('div.news-card-title div.news-card-author-time span.author')->text();
        $time = $element->filter('div.news-card-title div.news-card-author-time span.time')->text();
        $date = $element->filter('div.news-card-author-time span.date')->text();
        $createdAt = $time . ' on ' . $date;
        $content = $element->filter('div.news-card-content div')->text();
        $content = substr($content, 0, strpos($content, "\n"));
        $readMore = $element->filter('div.read-more a.source')->attr('href');
        $postData = [
            'image' => $image,
            'title' => $title,
            'author' => $author,
            'content' => $content,
            'postedAt' => $createdAt,
            'sourceURL' => $URL,
            'readMore' => isset($readMore) ? $readMore : ''
        ];
        array_push($posts, $postData);
    });
    if (count($posts) < 1) {
        $callback([
            'error' => 'No data found!'
        ]);
    } else {
        $callback($posts, $news_offset);
    }
}

function getMoreNews($options, $callback) {
    $URL = "https://www.inshorts.com/" . $options['lang'] . "/ajax/more_news";
    $details = [
        'category' => $options['category'],
        'news_offset' => $options['news_offset']
    ];
    $formBody = http_build_query($details);
    $response = fetchUrl($URL, [
        'method' => 'POST',
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        ],
        'body' => $formBody
    ]);
    $body = $response->getBody()->getContents();
    $posts = [];
    $crawler = new Crawler($body);
    $news_offset = $crawler->filter('.news-card')->each(function (Crawler $element, $i) use (&$posts, $URL) {
        $title = $element->filter('div.news-card-title a.clickable span')->text();
        $image = preg_match("/'(.*?)'/", $element->filter('.news-card-image')->attr('style'), $match) ? $match[1] : '';
        $author = $element->filter('div.news-card-title div.news-card-author-time span.author')->text();
        $time = $element->filter('div.news-card-title div.news-card-author-time span.time')->text();
        $date = $element->filter('div.news-card-author-time span.date')->text();
        $createdAt = $time . ' on ' . $date;
        $content = $element->filter('div.news-card-content div')->text();
        $content = substr($content, 0, strpos($content, "\n"));
        $readMore = $element->filter('div.read-more a.source')->attr('href') ?? '';

        $postData = [
            'image' => $image,
            'title' => $title,
            'author' => $author,
            'content' => $content,
            'postedAt' => $createdAt,
            'sourceURL' => $URL,
            'readMore' => $readMore,
        ];
        array_push($posts, $postData);
    });

    $news_offset = $news_offset[0] ?? '';

    if (count($posts) < 1) {
        $callback(['error' => 'No data found!']);
    } else {
        $callback($posts, $news_offset);
    }
} catch (Exception $error) {
    $callback($error);
}

<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\LearnCard;

class TweetService
{
    protected $twitter;

    public function __construct()
    {
        $this->twitter = new TwitterOAuth(
            config('services.twitter.consumer_key'),
            config('services.twitter.consumer_secret'),
            config('services.twitter.access_token'),
            config('services.twitter.access_secret'),
        );
    }

    /**
     * 基本となるツイート関数
     */
    public function tweet($tweet_content)
    {
        $res = $this->twitter->post("statuses/update", [
            "status" => $tweet_content,
        ]);
        return $res;
    }

    /**
     * 記事用のツイート関数
     */
    public function tweetArticle(LearnCard $learnCard, $tweet_tag_str)
    {
        // カンマ区切りのタグを配列に変換
        $tweet_tag_array = explode(",", $tweet_tag_str);
        // 記事のURL取得
        //$article_url = route('article', ['article_id' => $article->id]);
        // タイトル取得
        $title = $learnCard->name;
        // tweet内容作成
        $content = "【Tri-An-Gout | 新着記事情報】" . PHP_EOL;
        $content .= "新着記事が投稿されました！" . PHP_EOL;
        $content .= "『".$title."』". PHP_EOL .PHP_EOL;
        $content .= "#プログラミング";
        // タグがあれば、タグ追加
        foreach ($tweet_tag_array as $tweet_tag) {
            if ($tweet_tag !== "") {
                $content .= PHP_EOL."#".$tweet_tag;
            }
        }
        $res = self::tweet($content);
        return $res;
    }
}

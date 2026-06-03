<?php
// rss 2.0 and atom feeds for syndication

class FeedController extends Controller {

    private Article $articleModel;

    public function __construct() {
        $this->articleModel = new Article();
    }

    public function rss(): void {
        $articles = $this->articleModel->getFeedArticles(20);

        header('Content-Type: application/rss+xml; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
        echo '<channel>' . "\n";
        echo '  <title>'          . htmlspecialchars(SITE_NAME)        . '</title>' . "\n";
        echo '  <link>'           . SITE_URL                           . '</link>'  . "\n";
        echo '  <description>'    . htmlspecialchars(SITE_DESCRIPTION) . '</description>' . "\n";
        echo '  <language>en-au</language>' . "\n";
        echo '  <lastBuildDate>'  . date('r')                          . '</lastBuildDate>' . "\n";
        echo '  <atom:link href="' . SITE_URL . '/feed/rss" rel="self" type="application/rss+xml"/>' . "\n";

        foreach ($articles as $article) {
            $articleUrl = SITE_URL . '/article/' . $article['id'];
            $pubDate    = date('r', strtotime($article['updated_at']));
            $summary    = strip_tags($article['content']);
            $summary    = strlen($summary) > 300 ? substr($summary, 0, 300) . '...' : $summary;

            echo '  <item>' . "\n";
            echo '    <title>'       . htmlspecialchars($article['title'])           . '</title>'       . "\n";
            echo '    <link>'        . htmlspecialchars($articleUrl)                 . '</link>'        . "\n";
            echo '    <description>' . htmlspecialchars($summary)                   . '</description>' . "\n";
            echo '    <author>'      . htmlspecialchars($article['author_name'])     . '</author>'      . "\n";
            echo '    <pubDate>'     . $pubDate                                      . '</pubDate>'     . "\n";
            echo '    <guid isPermaLink="true">' . htmlspecialchars($articleUrl)     . '</guid>'        . "\n";
            echo '  </item>' . "\n";
        }

        echo '</channel>' . "\n";
        echo '</rss>';
        exit;
    }

    public function atom(): void {
        $articles = $this->articleModel->getFeedArticles(20);

        header('Content-Type: application/atom+xml; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');

        $updated = !empty($articles)
            ? date('c', strtotime($articles[0]['updated_at']))
            : date('c');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<feed xmlns="http://www.w3.org/2005/Atom">' . "\n";
        echo '  <title>'    . htmlspecialchars(SITE_NAME)        . '</title>'   . "\n";
        echo '  <subtitle>' . htmlspecialchars(SITE_DESCRIPTION) . '</subtitle>' . "\n";
        echo '  <link href="' . SITE_URL . '" />'                               . "\n";
        echo '  <link href="' . SITE_URL . '/feed/atom" rel="self" />'          . "\n";
        echo '  <id>'       . SITE_URL                           . '</id>'      . "\n";
        echo '  <updated>'  . $updated                           . '</updated>' . "\n";

        foreach ($articles as $article) {
            $articleUrl = SITE_URL . '/article/' . $article['id'];
            $updated    = date('c', strtotime($article['updated_at']));
            $published  = date('c', strtotime($article['created_at']));
            $summary    = strip_tags($article['content']);
            $summary    = strlen($summary) > 300 ? substr($summary, 0, 300) . '...' : $summary;

            echo '  <entry>' . "\n";
            echo '    <title>'     . htmlspecialchars($article['title'])       . '</title>'     . "\n";
            echo '    <link href="'. htmlspecialchars($articleUrl) . '" />'                     . "\n";
            echo '    <id>'        . htmlspecialchars($articleUrl)             . '</id>'        . "\n";
            echo '    <updated>'   . $updated                                  . '</updated>'   . "\n";
            echo '    <published>' . $published                                . '</published>' . "\n";
            echo '    <author><name>' . htmlspecialchars($article['author_name']) . '</name></author>' . "\n";
            echo '    <summary>'   . htmlspecialchars($summary)                . '</summary>'   . "\n";
            echo '  </entry>' . "\n";
        }

        echo '</feed>';
        exit;
    }
}

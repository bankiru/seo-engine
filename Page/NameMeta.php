<?php

namespace Bankiru\Seo\Page;

final class NameMeta
{
    const DOCUMENT_STATE_STATIC  = 'static';
    const DOCUMENT_STATE_DYNAMIC = 'dynamic';

    /**
     * @param string[] $keywords
     *
     * @return HtmlMetaInterface
     */
    public static function keywords(array $keywords)
    {
        return HtmlMeta::createNameMeta('keywords', implode(',', $keywords));
    }

    /**
     * @param string $description
     *
     * @return HtmlMetaInterface
     */
    public static function description($description)
    {
        return HtmlMeta::createNameMeta('description', (string)$description);
    }

    /**
     * @param string|string[] $robots
     *
     * @return HtmlMetaInterface
     */
    public static function robots($robots)
    {
        if (is_array($robots)) {
            $robots = implode(',', $robots);
        }

        return HtmlMeta::createNameMeta('robots', (string)$robots);
    }

    /**
     * @param string $author
     *
     * @return HtmlMetaInterface
     */
    public static function author($author)
    {
        return HtmlMeta::createNameMeta('author', (string)$author);
    }

    /**
     * @param string $copyright
     *
     * @return HtmlMetaInterface
     */
    public static function copyright($copyright)
    {
        return HtmlMeta::createNameMeta('copyright', (string)$copyright);
    }

    /**
     * @param string $state
     *
     * @return HtmlMetaInterface
     */
    public static function documentState($state = self::DOCUMENT_STATE_DYNAMIC)
    {
        return HtmlMeta::createNameMeta('document-state', (string)$state);
    }

    /**
     * @param string $generator
     *
     * @return HtmlMetaInterface
     */
    public static function generator($generator)
    {
        return HtmlMeta::createNameMeta('generator', (string)$generator);
    }

    /**
     * @param string $subject
     *
     * @return HtmlMetaInterface
     */
    public static function subject($subject)
    {
        return HtmlMeta::createNameMeta('subject', (string)$subject);
    }

    /**
     * @param string $url
     *
     * @return HtmlMetaInterface
     */
    public static function url($url)
    {
        return HtmlMeta::createNameMeta('url', (string)$url);
    }

    /**
     * @param int $days
     *
     * @return HtmlMetaInterface
     */
    public static function revisit($days)
    {
        return HtmlMeta::createNameMeta('url', $days);
    }
}

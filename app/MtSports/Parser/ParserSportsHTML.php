<?php
namespace App\MtSports\Parser;

use voku\helper\HtmlDomParser;

class ParserSportsHTML
{
    private string $newsText;
    private string $urlImg;

    public function __construct(string $data)
    {
        $dom = HtmldomParser::str_get_html($data);

        $text = $dom->findOne('.content-article');
        $img = $dom->findOne('div.content-article img');

        $this->newsText = \str_replace(
            '…',
            '',
            \preg_replace(
                '/\s+/',
                ' ',
                $text->text()
            )
        );

        $this->urlImg = $img->getAttribute('src');
    }

    public function getText(): string
    {
        return $this->newsText;
    }

    public function getLinkImg(): string
    {
        return $this->urlImg;
    }
}

<?php

namespace App\MtSports\Parser;

class ParserSportsLinksJSON
{
    private array $data;
    private const DEFAUTL_COUNT_ITEMS = 5;
    private array $links = [];

    public function __construct(string $data)
    {
        $decoded = \json_decode(
            $data = str_replace(
                '"})',
                '"}',
                trim(
                    \preg_replace(
                        '/^\w+\(/',
                        ' ',
                        $data
                    )
                )
            ),
            true,
            JSON_THROW_ON_ERROR
        );

        if (!\array_key_exists('data', $decoded) || !\is_array($decoded['data'])) {
            throw new \Exception('The date key is missing in the passed object');
        }

        $this->data = $decoded['data'];
    }

    public function getLinks(int $count = self::DEFAUTL_COUNT_ITEMS): array
    {
        $iteration = 1;
        foreach ($this->data as $link) {

            if ($iteration > $count) break;

            if ($link['category'] == 'sports') {
                \array_push($this->links, [
                    'title' => $link['title'],
                    'url'  => $link['vurl'],
                    'url_img'  => $link['img'],
                    'min_img'  => '',
                    'big_img'  => '',
                    'announcement_text'  => $link['intro'],
                    'content'  => '',
                    'date' => $link['publish_time'],
                ]);

                $iteration++;
            }
        }

        return $this->links;
    }
}

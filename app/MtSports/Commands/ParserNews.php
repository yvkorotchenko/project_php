<?php

namespace App\MtSports\Commands;

use App\Laravue\Services\StaticStorageService;
use App\MtSports\Parser\ParserSportsLinksJSON;
use Illuminate\Console\Command;
use App\MtSports\Parser\ParserSportsHTML;
use App\MtSports\Http\HttpClient;
use App\MtSports\Models\News;
use App\MtSports\Models\NewsLink;

class ParserNews extends Command
{
    protected $signature = 'parse:site';
    protected $description = 'parse a link to a news site';

    public function __construct(
        private StaticStorageService $staticStorage,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $http = new HttpClient();

        try {
            $deleteListNews = News::where('visible', '=', '0');

            if ($deleteListNews->exists()) {
                foreach($deleteListNews->get() as $item) {
                    $this->staticStorage->deleteImages([$item->min_img]);
                    $this->staticStorage->deleteImages([$item->big_img]);

                    News::destroy($item->id);
                }
            }
        } catch (\Throwable $e) {
            $this->error('Runtime error ' . $e->getMessage());
        }

        try {
            $listLinks = NewsLink::all();

            foreach ($listLinks as $oneLink) {
                $dataArray = new ParserSportsLinksJSON($http->get($oneLink->url));
                $links = $dataArray->getLinks();

                foreach ($links as $link) {
                    $html = new ParserSportsHTML(
                        \iconv(
                            'gbk',
                            'utf-8//IGNORE',
                            $http->get($link['url'])
                        )
                    );

                    if (!News::where('title', $link)->exists()) {
                        if ($html->getText() !== '') {
                            $link['content'] = $html->getText();
                            $link['min_img'] = $this->staticStorage->uploadImageByUrl($link['url_img']);
                            $link['type'] = $oneLink->type;
                            $link['type_local'] = $oneLink->type_local;

                            if ($html->getLinkImg() != '') {
                                $link['big_img'] = $this->staticStorage->uploadImageByUrl($html->getLinkImg());
                            }

                            News::create($link);
                        }
                    }

                    sleep(1);
                }

            }
        } catch(\Throwable $e) {
            $this->error('Runtime error ' . $e->getMessage());
        }

        $this->info('Parse news finish!');

        return 0;
    }
}

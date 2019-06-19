<?php
require_once(APPPATH."libraries/Base_lib.php");

use Goutte\Client;

class Scraping extends Base_lib
{
    private $client = null;

    const TRN_URL = "https://rocketleague.tracker.network/";
    const URL_PLATFORM_PATH_LIST = [
        PLATFORM_ID_STEAM => "/profile/steam/",
        PLATFORM_ID_PS4   => "/profile/ps/",
        // PLATFORM_ID_XBOX  => "/profile/xbox/",
    ];

    /**
     * Scraping constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * スクレイピングをしてレートが取得出来るプラットフォームなのか
     * @param $platform
     * @return bool
     */
    public function is_target($platform)
    {
        return isset(self::URL_PLATFORM_PATH_LIST[$platform]);
    }

    /**
     * ロケットリーグをプレイした履歴があるかどうか
     * @param $id
     * @param null $platform
     * @return bool
     */
    public function exists($id, $platform = null)
    {
        $url = self::TRN_URL.$this->get_url_path($id, $platform);
        $crawler = $this->client->request('GET', $url);
        return count($crawler->filter('h1')) !== 0;
    }

    /**
     * MMR取得
     * @param string $id       Platform ID
     * @param string $platform steam | ps | xbox
     */
    public function get_mmr($id, $platform = null)
    {
        $url = self::TRN_URL.$this->get_url_path($id, $platform);
        $crawler = $this->client->request('GET', $url);

        $user_rate = [
            "casual"  => $this->get_data($crawler, "Un-Ranked"),
            "duel"    => $this->get_data($crawler, "Ranked Duel 1v1"),
            "doubles" => $this->get_data($crawler, "Ranked Doubles 2v2"),
            "standard"=> $this->get_data($crawler, "Ranked Standard 3v3"),
        ];

        return $user_rate;
    }

    /**
     * 詳細データを整形して取得
     * @param $crawler
     * @param $search_name
     * @return array
     */
    public function get_data($crawler, $search_name)
    {
        for($position = 1; $position < 10; $position++)
        {
            $elements = $crawler->filter('.card-table')
                                ->eq(1)
                                ->filter('tr')
                                ->eq($position);

            if (count($elements->filter('td')) !== 0)
            {
                $dom_rank = explode("\n", $elements->filter('td')
                                                   ->eq(1)
                                                   ->text());
                $dom_mmr  = explode("\n", $elements->filter('td')
                                                   ->eq(3)
                                                   ->text());

                if ($dom_rank[1] != $search_name)
                {
                    continue;
                }

                return [
                    "name" => $dom_rank[1],
                    "rank" => $dom_rank[3],
                    "mmr"  => str_replace(",", "", $dom_mmr[1]),
                ];
            }
        }

        return [
            "name" => $search_name,
            "rank" => "Unranked",
            "mmr"  => "0",
        ];
    }

    /**
     * URL生成
     * @param $id
     * @param $platform
     * @return string
     */
    private function get_url_path($id, $platform)
    {
        $platform_path = self::URL_PLATFORM_PATH_LIST[PLATFORM_ID_STEAM];
        if (isset(self::URL_PLATFORM_PATH_LIST[$platform]))
        {
            $platform_path = self::URL_PLATFORM_PATH_LIST[$platform];
        }

        return $platform_path.$id;
    }
}

<?php

namespace Odango\OdangoFeed;

use \Odango\OdangoFeed\Registry;

class FeedSeries {

    public $id;
    public $feed;
    public $query;
    public $seriesHash;

    public static function getByFeed($feed)
    {
        $db = Registry::getDatabase();
        $feedSeries = $db->builder()
            ->select()
            ->from('feedSeries')
            ->where('feed = :feed', [ ':feed' => $feed ])
            ->queryAll();

        return array_map(function($item) {
            return FeedSeries::createFromArray($item);
        }, $feedSeries);
    }

    public static function createFromArray($array)
    {
        $feedSeries = new FeedSeries();
        $feedSeries->id = $array['id'];
        $feedSeries->feed = $array['feed'];
        $feedSeries->query = $array['query'];
        $feedSeries->seriesHash = $array['serieshash'];
        $feedSeries->added = strtotime($array['added']);

        return $feedSeries;
    }

    public function getItems()
    {
        $stash = Registry::getStash();
        $cache = $stash->getItem('nyaa/feed/series/' . str_replace('/', '~', $this->seriesHash) . '/' . $this->query);

        if ($cache->isMiss() || true) {
            $cache->lock();
            $nyaaCollector = Registry::getNyaaCollector();
            $mappedByHash = $nyaaCollector->collect($this->query);
            $torrents = [];

            if (isset($mappedByHash[$this->seriesHash])) {
                $torrents = $mappedByHash[$this->seriesHash]->getTorrents();
            }

            $cache->set($torrents, 864000);
        }

        return $cache->get();
    }
}

<?php

namespace Odango\OdangoFeed;

use \Odango\OdangoFeed\Registry;

class Feed {

    public $id;
    public $name;
    public $description;
    public $password;
    public $secret;

    public static function getById($id)
    {
        $db = Registry::getDatabase();
        $feed = $db->builder()
            ->select()
            ->from('feed')
            ->where('id = :id', [ ':id' => $id ])
            ->queryRow();

        return Feed::createFromArray($feed);
    }

    public static function createFromArray($array)
    {
        $feed = new Feed();
        $feed->id = $array['id'];
        $feed->name = $array['name'];
        $feed->description = $array['description'];
        $feed->password = $array['password'];
        $feed->secret = $array['secret'];

        return $feed;
    }

    public function collectFeed()
    {
        $feedSeries = FeedSeries::getByFeed($this->id);

        $items = [];

        foreach ($feedSeries as $feedSerie) {
            $feedItems = $feedSerie->getItems();

            foreach ($feedItems as $feedItem) {
                $items[] = [
                    'date' => max($feedSerie->added, $feedItem->recordUpdated),
                    'item' => $feedItem
                ];
            }
        }

        usort($items, function ($a, $b) {
           return $a['date'] - $b['date'];
        });

        return $items;
    }
}

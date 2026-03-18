<?php
namespace App\Service;

class RssService
{
    public function fetch(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 3,
            ]
        ]);

        $content = @file_get_contents($url, false, $context);

        if ($content === false) {
            return [];
        }

        $xml = simplexml_load_string($content);

        if ($xml === false) {
            return [];
        }

        $items = [];
        foreach ($xml->channel->item as $item) {
            $items[] = [
                "titre"       => (string) $item->title,
                "description" => strip_tags((string) $item->description),
                "lien"        => (string) $item->link,
                "date"        => (string) $item->pubDate,
            ];
        }

        return $items;
    }
}
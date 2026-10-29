<?php

return [
    'routes' => [
        [
            'pattern' => 'maps/(:any)/points',
            'method' => 'GET',
            'action' => function ($mapId) {
                $map = page('maps')->find($mapId);
                
                if (!$map || $map->intendedTemplate() !== 'map') {
                    return [
                        'status' => 'error',
                        'message' => 'Map not found'
                    ];
                }
                
                $points = $map->children()->listed()->template('map-point');
                $data = [];
                
                foreach ($points as $point) {
                    $data[] = [
                        'id' => $point->id(),
                        'title' => $point->title()->value(),
                        'description' => $point->description()->kt()->value(),
                        'type' => $point->type()->value(),
                        'location' => [
                            'latitude' => $point->location()->toStructure()->first()->latitude()->value(),
                            'longitude' => $point->location()->toStructure()->first()->longitude()->value()
                        ],
                        'youtube_id' => $point->youtube_id()->value(),
                        'images' => $point->gallery()->toFiles()->map(function($file) {
                            return [
                                'url' => $file->url(),
                                'alt' => $file->alt()->value()
                            ];
                        })->data(),
                        'audio' => $point->audio()->toFile() ? $point->audio()->toFile()->url() : null,
                        'link' => $point->link()->value()
                    ];
                }
                
                return [
                    'status' => 'success',
                    'data' => $data
                ];
            }
        ]
    ]
]; 
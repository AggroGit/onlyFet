<?php

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', '/Users/alexortuno/git/onlyFet/ffmpeg'),
        'threads'  => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', '/Users/alexortuno/git/onlyFet/ffprobe'),
    ],

    'timeout' => 3600,

    'enable_logging' => true,
];

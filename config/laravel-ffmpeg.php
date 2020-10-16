<?php

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', '/Users/alexortuno/git/enterprise/ffmpeg'),
        'threads'  => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', '/Users/alexortuno/git/enterprise/ffprobe'),
    ],

    'timeout' => 3600,

    'enable_logging' => true,
];

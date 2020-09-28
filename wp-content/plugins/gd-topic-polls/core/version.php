<?php

if (!defined('ABSPATH')) exit;

class gdpol_core_info {
    public $code = 'gd-topic-polls';

    public $version = '1.4';
    public $build = 30;
    public $edition = 'lite';
    public $status = 'stable';
    public $updated = '2019.06.05';
    public $url = 'https://plugins.dev4press.com/gd-topic-polls/';
    public $author_name = 'Milan Petrovic';
    public $author_url = 'https://www.dev4press.com/';
    public $released = '2017.08.01';

    public $php = '5.6';
    public $mysql = '5.1';
    public $wordpress = '4.5';
    public $bbpress = '2.5';

    public $install = false;
    public $update = false;
    public $previous = 0;

    function __construct() { }

    public function to_array() {
        return (array)$this;
    }
}

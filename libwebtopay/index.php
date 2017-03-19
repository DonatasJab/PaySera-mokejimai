<?php
require __DIR__ . '/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;

// For the sake of this example
Header( 'Content-Type: text/plain' );
Header( 'X-Content-Type-Options: nosniff' );

// Edit this ->
define( 'SQ_SERVER_ADDR',   'Serverio IP' );
define( 'SQ_SERVER_PORT',   25575 );
define( 'SQ_RCON_PASS',     'rconpass' );
define( 'SQ_TIMEOUT',       1 );
define( 'SQ_ENGINE',        SourceQuery::SOURCE );
// Edit this <-

$Query = new SourceQuery( );
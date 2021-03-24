#!/usr/bin/env php
<?php
/**
 * Fuzzy search Espanso matches
 * 
 * Helps when forgetting the trigger.
 * 
 * @see espanso.org
 * 
 * usage searching for a match that has `backup` in it: 
 *   `$ esp backup`
 */

require '../_helpers.php';
require '../vendor/autoload.php';

define('ESP_SHOW_LIMIT', 3);

//@TODO should be a helper library for cli output instead.
define('CLI_NORMAL', "\e[0m");
define('CLI_DIM', "\e[2m");
define('CLI_INDENT', "    ");

$query = sanitize($_SERVER['argv'][1]) ?? die("❌ a query is needed\n"); 

$cmd = '/usr/bin/espanso match list -j';
$espanso_list = shell_exec( $cmd );

if ( ! $espanso_list || empty( $espanso_list ) )
    die("❌ unable to run `{$cmd}`\n");

$matches = json_decode( $espanso_list, JSON_OBJECT_AS_ARRAY );

$for_fuse = [];
foreach ( $matches as $match ) {
    $for_fuse[] = [
        "trigger" => $match['triggers'][0], //@TODO multiple trigger support
        "replace" => $match['replace']
    ];
}

$fuse = new \Fuse\Fuse($for_fuse, [
    "keys" => [ "trigger", "replace" ],
]);
  
$results = $fuse->search( $query );

$i = 0;
foreach ( $results as $result ) {
    if ( $i == ESP_SHOW_LIMIT) 
        break;
    echo CLI_NORMAL."{$result['trigger']}\n";
    echo CLI_DIM.CLI_INDENT.str_replace("\n","\n".CLI_INDENT,truncate( $result['replace'], 100 ))."\n";
    $i++;
}
<?php
require_once dirname(__FILE__) . '/../../../config.php';
require_once nZEDb_LIB . 'backfill.php';
require_once nZEDb_LIB . 'nntp.php';
require_once nZEDb_LIB . 'ColorCLI.php';
require_once nZEDb_LIB . 'site.php';

$c = new ColorCLI;
$s = new Sites();
$site = $s->get();
if (!isset($argv[1]))
	exit($c->error("This script is not intended to be run manually, it is called from backfill_threaded.py."));

// Create the connection here and pass
$nntp = new Nntp();
if ($nntp->doConnect() === false)
	exit($c->error("Unable to connect to usenet."));
if ($site->nntpproxy === "1")
	usleep(500000);

$pieces = explode(' ', $argv[1]);
$backfill = new Backfill();
$backfill->backfillPostAllGroups($pieces[0], 10000, 'normal', $nntp);
if ($site->nntpproxy != "1")
	$nntp->doQuit();
?>

<?php
require('Slim/Slim/Slim.php');
require('constants.php');
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

function format_url($app)
{
	$group_id = $app->request->params('group_id');
	if (!is_numeric($group_id))
	{
		ob_start();
		var_dump($app->request);
		$dump = ob_get_contents();
		ob_end_clean();
		email_log($dump, 1, 'dan.j.conley@gmail.com', 'From:jaconda@danconley.net');
	}
	return 'https://jaconda.im/api/v1/endpoint/groups/' . $group_id . '/messages?token=' . TOKEN . '&text=';
}

function email_log($log)
{
	error_log($log, 1, 'dan.j.conley@gmail.com', 'From:jaconda@danconley.net');
}

function send_curl($url, $text)
{
	$text = urlencode($text);
	$ch = curl_init($url . $text);
	if (!$ch)
	{
		email_log('curl_init() failed with url ' . $url . $text);
	}
	if (!curl_exec($ch))
	{
		$error = 'Error with url ' . $url . ' and text /' . $text . '/';
		email_log($error, 1, 'dan.j.conley@gmail.com', 'From: jaconda@danconley.net');
	}
}
	
$app->group('/' . KEY, function () use ($app)
		{
		$app->post('/wolf', function() use ($app)
			{
			$people = array(
				'Dan',
				'Elizabeth',
				'Patricia',
				'Zach',
				'Amanda',
				'Paul',
				'Matt',
				'Sam',
				);
			$message = 'I am the seer! ' . $people[array_rand($people)] . ' is the wolf!';
			send_curl(format_url($app), $message);
			});

		$app->get('/countdown/:title/:date', function ($title, $date) use ($app)
			{
			echo 'please use post';
			});

		$app->post('/countdown/:title/:date', function ($title, $date) use ($app)
		{
			$date = new DateTime(date('Y-m-d', strtotime($date)), new DateTimeZone('America/New_York'));
			$days = $date->diff(new DateTime('now', new DateTimeZone('America/New_York')));
			$message = 'Only ' . $days->days . ' days until ' . urldecode($title) . '!';
			send_curl(format_url($app), $message);
		});

		});

$app->run();
?>

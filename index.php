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
		throw new Exception('Bad/empty group_id');
	}
	return 'https://jaconda.im/api/v1/endpoint/groups/' . $group_id . '/messages?token=' . TOKEN . '&text=';
}

function send_curl($url, $text)
{
	$ch = curl_init($url . urlencode($text));
	curl_exec($ch);
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

		$app->post('/countdown/:title/:date', function ($title, $date) use ($app)
			{
				$days = 2;
				send_curl(format_url($app), 'Only ' . $days . ' days until ' . $title . '!');
			});

		});

$app->run();
?>

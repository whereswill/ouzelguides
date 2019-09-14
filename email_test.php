<?php
require 'vendor/autoload.php';
require 'ASEngine/ASConfig.php';

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Ivory\HttpAdapter\Guzzle6HttpAdapter;

$httpAdapter = new Guzzle6HttpAdapter(new Client());
$sparky = new SparkPost($httpAdapter, ['key' => SPARKY_KEY]);

$recipient_name = 'Will Sharp';
$recipient_email = 'whereswill@bendcable.com';

if(!empty($_POST['sender_name'])){
	$sender_name = $_POST['sender_name'];	
} else {
	$sender_name = SENDER_NAME;
}

if(!empty($_POST['sender_email'])){
	$sender_name = $_POST['sender_email'];	
} else {
	$sender_name = SENDER_EMAIL;
}

$message = 'There are two service types available: SparkPost, our self-service product, and SparkPost Elite, a managed service with guaranteed burst rates and white-glove<br><br>support. These services have shared and unique aspects, with those unique aspects indicated in our consolidated API documentation as follows:';
$template = 'og-basic';

try {
    // Build your email and send it!
    $results = $sparky->transmission->send([
        'from'=>[
            'name' => 'OuzelGuides',
            'email' => $sender_email
        ],
        'substitutionData'=>['name' => $recipient_name,
        											'message' => $message,
        											'signed'  => $sender_name,
        										],
        'recipients'=>[
            [
                'address'=>[
                    'name' => $recipient_name,
                    'email' => $recipient_email
                ]
            ]
        ],
        'template' => $template,
    ]);
    echo 'Woohoo! You just sent your first mailing!';
} catch (\Exception $err) {
    echo 'Whoops! Something went wrong';
    var_dump($err);
}
?>
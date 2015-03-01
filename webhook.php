<?php
require_once 'lockitron.php';

//get an access token from http://api.lockitron.com, then "Your Apps"
 $access_token = "xxxxxxxxxxxx";
 //get your lock ID from logging in to http://lockitron.com and clicking on your lock. The ID is in the address bar
 $lock = "xxxxxxxxx";
 $lockitronInstance = new LockitronAPI($access_token);
 $attributes = array(
	'state' => 'lock'
);
//the lockitron webhook response is undocumented but you can learn it by setting your app's Webhhook URI to a requestb.in address
//annd then analysizing the response when you lock/unlock
if(file_get_contents('php://input')) {
	$decoded_response = json_decode(file_get_contents('php://input'));
	if ($decoded_response->data->lock->id == $lock) {
		if ($decoded_response->data->activity->updated_kind == "lock-updated-unlocked") {
			//anything more than 120 and the Lockitron will fall alseppt before it receives the lock command
			sleep(105);
			//the $results variable holds an array containing the status and the results.
			$results = $lockitronInstance->modify_lock_attribute($lock, $attributes);
		}
	}
}
?>
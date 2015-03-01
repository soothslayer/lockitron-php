Lockitron-PHP

This is is a simple HTTP_Request2 class to help use the Lockitron API using PHP

Setup:

1. Clone or copy lockitron.php
2. Clone or copy webhhok.php
3. Get an access token for your lock by going to http://api.lockitron.com
Log in and click on “Your Apps”, then “New App”
Give a name to your app and fill in the address of the webhhok.php file (or requestb.in address if testing) in the Webhook URI field
4. Click Save and copy the access token listed
5. Paste the access token into webhook.php
6. Visit http://www.lockitron.com and log in and click on your lock
7. Copy your lock ID from the address bar (ex. https://lockitron.com/dashboard/xxxxx)
8. Paste the lock ID into webhook.php

How to Use:

Create an instance of the Lockitron API with the access token as the argument:
example:  $lockitronInstance = new LockitronAPI($access_token);

Use any of the functions listed inside of lockitron.php
Some functions take no arguments, get_locks()
Some functions take a lock or user or key id and some take parameters in an array

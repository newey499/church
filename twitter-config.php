<?php
/**********************************************

twitter-config.php

Contains magic number for reading/writing from/to the Twitter Account christchurchlye


**********************************************/

/***********************

user information from twitter via url
https://api.twitter.com/1/users/show.json?screen_name=ChristChurchLye&include_entities=true


RSS feed
(Magic number in URL is account id seen at start of JSON returned from above URL)
http://twitter.com/statuses/user_timeline/566458845.rss


*****************************/


/************************************
OAuth settings

Your application's OAuth settings. Keep the "Consumer secret" a secret. This key should never be human-readable in your application.
Access level 	Read and write
About the application permission model
Consumer key 	iEt75z0jWLd3zmstj3Dg
Consumer secret 	85U4v2jzOGIYszEWBlSNxGfG5m2c64in4ZkhnI5lIOg
Request token URL 	https://api.twitter.com/oauth/request_token
Authorize URL 	https://api.twitter.com/oauth/authorize
Access token URL 	https://api.twitter.com/oauth/access_token
Callback URL 	None

========================================

Your access token

Use the access token string as your "oauth_token" and the access token secret as your "oauth_token_secret" to sign requests with your own Twitter account. Do not share your oauth_token_secret with anyone.
Access token 	566458845-fqMR4Z9KtsRU8pvC6lGZ1XP46O4JshB53ouqKV0j
Access token secret 	Yd9ukHVO6RXQa9YFUCJwH4Ax4VCn7IvLjH3w2XG84
Access level 	Read and write

************************************************/

define("CONSUMER_KEY", 		"iEt75z0jWLd3zmstj3Dg");
define("CONSUMER_SECRET", 	"85U4v2jzOGIYszEWBlSNxGfG5m2c64in4ZkhnI5lIOg");
define("REQUEST_TOKEN_URL",	"https://api.twitter.com/oauth/request_token");
define("AUTHORIZE_URL", 	"https://api.twitter.com/oauth/authorize");
define("ACCESS_TOKEN_URL", 	"https://api.twitter.com/oauth/access_token");
define("CALLBACK_URL",	 	"None");


// Your access token
//Use the access token string as your "oauth_token" and the access token secret as your "oauth_token_secret" to sign requests with your own Twitter account. Do not share your oauth_token_secret with anyone.
define("ACCESS_TOKEN", 			"566458845-fqMR4Z9KtsRU8pvC6lGZ1XP46O4JshB53ouqKV0j");
define("ACCESS_TOKEN_SECRET", 	"Yd9ukHVO6RXQa9YFUCJwH4Ax4VCn7IvLjH3w2XG84");
define("ACCESS_LEVEL",		 	"Read and write");







?>

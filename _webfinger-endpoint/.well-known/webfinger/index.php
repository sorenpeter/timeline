<?php 

// Webfinger companion script for timeline - a single user twtxt/yarn server and client (https://github.com/sorenpeter/timeline)
// By: sorenpeter@darch.dk (2024)
// Based on: https://tildegit.org/team/site/src/branch/master/.well-known/webfinger

// What folder did you install timeline in on your server?
$timeline_dir = "/timeline"; // from the root of your domain, so if it at: example.com/timeline this should be "/timeline"

// Get the path to the private config.inig by steping two levels of directories back from /.well-known/webfinger/
$timeline_config = __DIR__."/../..".$timeline_dir."/private/config.ini";

if (file_exists($timeline_config))
{
    $config = parse_ini_file($timeline_config);

    $webfinger_array = [
        
        "subject" => "acct:".$config["public_nick"]."@".$_SERVER['SERVER_NAME'],
        "links" => [

            ["rel" => "self",
            "type" => "text/plain",
            "href" => $config['public_txt_url']
            ],
            
            ["rel" => "https://webfinger.net/rel/profile-page",
            "type" => "text/html",
            "href" => "http://".$_SERVER['SERVER_NAME'].$timeline_dir
            ],

            ["rel" => "http://webfinger.net/rel/avatar",
            "type" => "image/png",
            "href" => $config['public_avatar']
            ],
        ]
    ];

    header("Content-type: application/jrd+json");
    echo json_encode($webfinger_array);
}
else
    header("Status: 404");

die();

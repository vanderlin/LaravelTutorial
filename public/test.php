<?php

$creds = [
'host'      => 'external-db.s131103.gridserver.com',
'database'  => 'db131103_experiments',
'username'  => 'db131103_demo',
'password'  => 'monger33',
];
print_r($creds);

// Create connection
//$link = mysqli_connect("myhost","myuser","mypassw","mybd") or die("Error " . mysqli_error($link));

$con = mysqli_connect($creds['host'],$creds['username'],$creds['password'],$creds['database']);

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

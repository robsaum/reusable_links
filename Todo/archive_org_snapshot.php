<?php

$url = 'https://archive.org/wayback/available?url=mesonuktion.net'; // path to your JSON file

ini_set("allow_url_fopen", 1);

// Loop through the associative array
foreach($array as $key=>$value)
    {
        echo $key . "=>" . $value . "<br>";
    }

// Read JSON file
$readjson = file_get_contents($url) ;

//Decode JSON
$data = json_decode($readjson, true);

//Print data
print_r($data);

//Parse the employee name
foreach ($data as $key) {
  $get_archive = $key['closest']['url']."<br/>";
}

echo "<br>Da URL: " . $get_archive . "<br>";

$JSON = file_get_contents($url);
$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($JSON, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(!is_array($val)) {
        if($key == "Year") {
            print "<br/>";
        }
    print $key."    : ".$val . "<br/>";
    }
}




?>



<?php

function decodeScore($numSystem, $encodedScore)
{
    $score = 0;
    $length = strlen($encodedScore);

    for ($i = 0; $i < $length; $i++) {

        $digit = strpos($numSystem, $encodedScore[$i]);

        $score += $digit * pow(strlen($numSystem), $length - $i - 1);
    }

    return $score;
}

$filename = 'data/encryptedScore.csv';
$scores = [];

if (($handle = fopen($filename, 'r')) !== FALSE) {

    while (($data = fgetcsv($handle)) !== FALSE) {
        $username = $data[0];
        $numSystem = $data[1];
        $encodedScore = $data[2];

        $score = decodeScore($numSystem, $encodedScore);

        $scores[] = ['username' => $username, 'score' => $score];
    }

    fclose($handle);
}

usort($scores, function ($a, $b) {
    return $b['score'] - $a['score'];
});

foreach ($scores as $entry) {
    echo $entry['username'] . ',' . $entry['score'] . "\n";
}

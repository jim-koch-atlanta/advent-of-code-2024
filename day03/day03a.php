<?php
// Specify the input file path
$inputFile = "input.txt";

// Define a function to parse a line into two integers
function parseLine($line) {
    // Regex to match `mul(x,y)` with 1 to 3 digit numbers
    $pattern = '/mul\(\d{1,3},\d{1,3}\)/';

    // Use preg_match_all to find all matches
    $matches = [];
    if (preg_match_all($pattern, $line, $matchesResult)) {
        for ($i = 0; $i < count($matchesResult[0]); $i++) {
            $commaPosition = strpos($matchesResult[0][$i], ',');
            $leftNumber = substr($matchesResult[0][$i], 4, $commaPosition - 4);
            $rightNumber = substr($matchesResult[0][$i], $commaPosition + 1, strlen($matchesResult[0][$i]) - ($commaPosition + 1) - 1);
            print_r($matchesResult[0][$i] . ";" . $leftNumber . ";" . $rightNumber . PHP_EOL);
            array_push($matches, [(int)$leftNumber, (int)$rightNumber]);
        }
    }

    return $matches;
}

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$total = 0;

if ($fileHandle) {
    // Read and print each line until end of file
    while (($line = fgets($fileHandle)) !== false) {
        try {
            $matches = parseLine($line);
            for ($i = 0; $i < count($matches); $i++) {
                $total = $total + ($matches[$i][0] * $matches[$i][1]);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

echo "Total: " . $total . PHP_EOL;

?>
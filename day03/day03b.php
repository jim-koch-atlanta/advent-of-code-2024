<?php
// Specify the input file path
$inputFile = "input.txt";

// Define a function to parse a line into two integers
function parseLine($line) {
    // Define static so it's persisted between lines.
    static $do = true;

    // Regex to match `mul(x,y)` with 1 to 3 digit numbers
    $pattern = '/mul\(\d{1,3},\d{1,3}\)|do\(\)|don\'t\(\)/';

    // Use preg_match_all to find all matches
    $matches = [];
    if (preg_match_all($pattern, $line, $matchesResult)) {
        for ($i = 0; $i < count($matchesResult[0]); $i++) {
            if ($matchesResult[0][$i] === "do()") {
                print_r("do = true" . PHP_EOL);
                $do = true;
            }
            else if ($matchesResult[0][$i] === "don't()") {
                print_r("do = false" . PHP_EOL);
                $do = false;
            }
            else if ($do === true) {
                $commaPosition = strpos($matchesResult[0][$i], ',');
                $leftNumber = substr($matchesResult[0][$i], 4, $commaPosition - 4);
                $rightNumber = substr($matchesResult[0][$i], $commaPosition + 1, strlen($matchesResult[0][$i]) - ($commaPosition + 1) - 1);
                print_r($matchesResult[0][$i] . ";" . $leftNumber . ";" . $rightNumber . PHP_EOL);
                array_push($matches, [(int)$leftNumber, (int)$rightNumber]);
            }
        }
    }

    $lineTotal = 0;
    for ($i = 0; $i < count($matches); $i++) {
        $lineTotal = $lineTotal + ($matches[$i][0] * $matches[$i][1]);
    }

    return $lineTotal;
}

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$total = 0;

if ($fileHandle) {
    // Read and print each line until end of file
    while (($line = fgets($fileHandle)) !== false) {
        try {
            $lineTotal = parseLine($line);
            $total = $total + $lineTotal;
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
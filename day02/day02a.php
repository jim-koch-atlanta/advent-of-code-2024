<?php
// Specify the input file path
$inputFile = "input.txt";

// Define a function to parse a line into two integers
function parseLine($line) {
    // Trim any whitespace and split the line by tabs or spaces
    $parts = preg_split('/\s+/', trim($line));
    
    $intParts = [];
    for ($i = 0; $i < count($parts); $i++) {
        array_push($intParts, (int)$parts[$i]);
    }

    return $intParts;
}

function isSafe($intParts) {
    if (count($intParts) < 2) {
        return true;
    }

    if ($intParts[0] === $intParts[1]) {
        return false;
    }

    $isIncreasing = false;
    if ($intParts[0] < $intParts[1]) {
        $isIncreasing = true;
    }

    for ($i = 1; $i < count($intParts); $i++) {
        $diff = $intParts[$i] - $intParts[$i - 1];
        if (! $isIncreasing) {
            $diff = 0 - $diff;
        }

        if (($diff != 1) && ($diff != 2) && ($diff != 3)) {
            return false;
        }
    }

    return true;
}

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$safeLines = 0;

if ($fileHandle) {
    // Read and print each line until end of file
    while (($line = fgets($fileHandle)) !== false) {
        try {
            $intParts = parseLine($line);
            if (isSafe($intParts)) {
                $safeLines++;
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

echo "Safe Lines: " . $safeLines . PHP_EOL;

?>
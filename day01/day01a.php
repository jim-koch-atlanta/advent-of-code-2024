<?php
// Specify the input file path
$inputFile = "input.txt";

// Define a function to parse a line into two integers
function parseLine($line) {
    // Trim any whitespace and split the line by tabs or spaces
    $parts = preg_split('/\s+/', trim($line));
    
    // Ensure we have exactly two parts and return them as integers
    if (count($parts) === 2) {
        return [(int)$parts[0], (int)$parts[1]];
    } else {
        throw new Exception("Invalid line format: $line");
    }
}

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

// Lists to hold the left and right values
$leftValues = [];
$rightValues = [];

if ($fileHandle) {
    // Read and print each line until end of file
    while (($line = fgets($fileHandle)) !== false) {
        try {
            [$value1, $value2] = parseLine($line);

            // Add the values to their respective lists
            array_push($leftValues, $value1);
            array_push($rightValues, $value2);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

sort($leftValues);
sort($rightValues);

$sum = 0;
$size = count($leftValues); // Assume both arrays are the same size
for ($i = 0; $i < $size; $i++) {
    $left = $leftValues[$i];
    $right = $rightValues[$i];

    $sum = $sum + abs($left - $right);
}

echo "Sum: " . $sum . PHP_EOL;

?>
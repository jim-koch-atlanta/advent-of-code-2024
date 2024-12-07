<?php
// Specify the input file path
$inputFile = "input.txt";

function findWord($grid, $word) {
    $rows = count($grid);
    $cols = strlen($grid[0]);
    print_r("Rows: " . $rows . ", Cols: " . $cols . PHP_EOL);
    $wordLength = strlen($word);
    $directions = [
        [0, 1],  // Right
        [0, -1], // Left
        [1, 0],  // Down
        [-1, 0], // Up
        [1, 1],  // Down-Right
        [-1, -1], // Up-Left
        [1, -1], // Down-Left
        [-1, 1]  // Up-Right
    ];
    
    $count = 0;
    
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            foreach ($directions as $direction) {
                $found = true;
                for ($i = 0; $i < $wordLength; $i++) {
                    $newRow = $row + $direction[0] * $i;
                    $newCol = $col + $direction[1] * $i;
                    
                    if ($newRow < 0 || $newRow >= $rows || $newCol < 0 || $newCol >= $cols || $grid[$newRow][$newCol] !== $word[$i]) {
                        $found = false;
                        break;
                    }
                }
                if ($found) {
                    $count++;
                }
            }
        }
    }
    
    return $count;
}

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$total = 0;

$grid = [];
if ($fileHandle) {
    // Read and print each line until end of file
    while (($line = fgets($fileHandle)) !== false) {
        array_push($grid, trim($line));
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

$xmasCount = findWord($grid, "XMAS");

echo "Total: " . $xmasCount . PHP_EOL;

?>
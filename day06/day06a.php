<?php

function withinBoundaries($map, $currentPos) {
    if ($currentPos[0] < 0 ||
        $currentPos[1] < 0 ||
        $currentPos[0] > count($map) - 1 ||
        $currentPos[1] > count($map) - 1) {
        return false;
    }

    return true;
}

// Specify the input file path
$inputFile = "input.txt";

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$directions = [
    [-1, 0], // Up
    [0, 1],  // Right
    [1, 0],  // Down
    [0, -1], // Left
];

// Start facing up.
$currentDir = 0;

$map = [];

// The starting position.
$currentPos = [0,0];

if ($fileHandle) {
    $currentLine = 0;
    while (($line = fgets($fileHandle)) !== false) {
        $line = trim($line);

        // Are we in this line?
        if (strpos($line, '^') != false) {
            $linePos = strpos($line, '^');
            $currentPos = [$currentLine, $linePos];
        }

        array_push($map, $line);
        $currentLine++;
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

printf("currentPos=%s\n", json_encode($currentPos));

while (withinBoundaries($map, $currentPos)) {
    $map[$currentPos[0]][$currentPos[1]] = 'X';
    $nextPosX = $currentPos[0] + $directions[$currentDir][0];
    $nextPosY = $currentPos[1] + $directions[$currentDir][1];

    if (withinBoundaries($map, [$nextPosX, $nextPosY]) && ($map[$nextPosX][$nextPosY] == '#')) {
        $currentDir = ($currentDir + 1) % count($directions);
    } else {
        $currentPos = [$nextPosX, $nextPosY];
    }
    printf("currentPos=%s\n", json_encode($currentPos));
}

$visited = 0;
for ($i = 0; $i < count($map); $i++) {
    for ($j = 0; $j < strlen($map[$i]); $j++) {
        if ($map[$i][$j] == 'X') {
            $visited++;
        }
    }
}

printf("Visited: %d", $visited);
?>
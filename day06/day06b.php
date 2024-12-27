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

function checkMap($map, $mapVisited, $currentPos, $currentDir) {
    $directions = [
        [-1, 0], // Up
        [0, 1],  // Right
        [1, 0],  // Down
        [0, -1], // Left
    ];
    
    while (withinBoundaries($map, $currentPos)) {
        if (in_array($currentDir, $mapVisited[$currentPos[0]][$currentPos[1]])) {
            return true;
        }
        array_push($mapVisited[$currentPos[0]][$currentPos[1]], $currentDir);
        $nextPosX = $currentPos[0] + $directions[$currentDir][0];
        $nextPosY = $currentPos[1] + $directions[$currentDir][1];
    
        if (withinBoundaries($map, [$nextPosX, $nextPosY]) &&
            (($map[$nextPosX][$nextPosY] == '#') || ($map[$nextPosX][$nextPosY] == 'O'))) {
            $currentDir = ($currentDir + 1) % count($directions);
        } else {
            $currentPos = [$nextPosX, $nextPosY];
        }
    }
    
    return false;
}

function printMap($map) {
    for ($i = 0; $i < count($map); $i++) {
        for ($j = 0; $j < strlen($map[$i]); $j++) {
            print($map[$i][$j]);
        }
        print("\n");
    }
}

// Specify the input file path
$inputFile = "input.txt";

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$map = [];

// The starting position.
$startingPos = [0,0];
$startingDir = 0;

if ($fileHandle) {
    $currentLine = 0;
    while (($line = fgets($fileHandle)) !== false) {
        $line = trim($line);

        // Are we in this line?
        if (strpos($line, '^') != false) {
            $linePos = strpos($line, '^');
            $startingPos = [$currentLine, $linePos];
        }

        array_push($map, $line);
        $currentLine++;
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

$permanentLoop = 0;
$count = 0;

// Locations that have been visited, and which direction the guard moved
// through it. Needs to be an array of directions.
$mapVisited = [];
for ($i = 0; $i < count($map); $i++) {
    $mapVisited[$i] = [];
    for ($j = 0; $j < strlen($map[$i]); $j++) {
        $mapVisited[$i][$j] = [];
    }
}

for ($i = 0; $i < count($map); $i++) {
    for ($j = 0; $j < strlen($map[$i]); $j++) {
        if (!($startingPos[0] == $i && $startingPos[1] == $j) && ($map[$i][$j] != 'X')) {
            $mapCopy = $map;
            $mapCopy[$i][$j] = 'O';
            $mapCopy[$startingPos[0]][$startingPos[1]] = '.';
            $mapVisitedCopy = $mapVisited;
            
            // Uncomment for debugging.
            // printMap($mapCopy);
            
            if (checkMap($mapCopy, $mapVisitedCopy, $startingPos, $startingDir) == true) {
                $permanentLoop++;
            }
            $count++;
            printf("Completed: %d\n", $count);
        }
    }
}

printf("Loops: %d\n", $permanentLoop);
?>
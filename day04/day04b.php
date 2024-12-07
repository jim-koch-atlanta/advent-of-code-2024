<?php
// Specify the input file path
$inputFile = "input.txt";

function findXMAS($grid) {
    $rows = count($grid);
    $cols = strlen($grid[0]);
    $patterns = ["MAS", "SAM"];
    $patternLength = strlen($patterns[0]);
    $count = 0;

    // Helper function to check if "MAS" appears forward or backward in a direction
    function matchesPattern($grid, $startRow, $startCol, $dRow, $dCol, $pattern) {
        $rows = count($grid);
        $cols = strlen($grid[0]);
        $length = strlen($pattern);

        for ($i = 0; $i < $length; $i++) {
            $row = $startRow + $dRow * $i;
            $col = $startCol + $dCol * $i;

            if ($row < 0 || $row >= $rows || $col < 0 || $col >= $cols || $grid[$row][$col] !== $pattern[$i]) {
                return false;
            }
        }
        return true;
    }

    // Check for "X" pattern
    for ($row = 1; $row < $rows - 1; $row++) {
        for ($col = 1; $col < $cols - 1; $col++) {

            $matchesDiagonal1 = false;
            $matchesDiagonal2 = false;

            foreach ($patterns as $pattern) {
                // Check for "MAS" / "SAM" in diagonal arms (forward and backward)
                $matchesDiagonal1 = $matchesDiagonal1 || matchesPattern($grid, $row - 1, $col - 1, 1, 1, $pattern);
                $matchesDiagonal2 = $matchesDiagonal2 || matchesPattern($grid, $row - 1, $col + 1, 1, -1, $pattern);
            }

            if ($matchesDiagonal1 && $matchesDiagonal2) {
                $count++;
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

$xmasCount = findXMAS($grid);

echo "Total: " . $xmasCount . PHP_EOL;

?>
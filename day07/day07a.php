<?php

/**
 * Try to solve for $target using only + and * on the given array of numbers,
 * evaluated strictly left-to-right. If a solution is found, print the solution
 * and return true; otherwise return false.
 *
 * @param int   $target  The test value (e.g., 190)
 * @param array $numbers The array of numbers (e.g., [10, 19])
 * @return bool
 */
function canSolveWithAddAndMultiply($target, array $numbers)
{
    // If there's only one number, just compare directly.
    if (count($numbers) === 1) {
        if ($numbers[0] === $target) {
            // "Equation" is just that single number.
            echo "{$target}: {$numbers[0]} = {$target} => true\n";
            return $target;
        }
        echo "{$target}: {$numbers[0]} cannot produce {$target} => false\n";
        return 0;
    }

    $n = count($numbers);
    // We have (n-1) "slots" where each slot can be '+' or '*'.
    // So there are 2^(n-1) possible combinations of operators.
    $maxCombinations = 1 << ($n - 1); // 2^(n-1)

    for ($mask = 0; $mask < $maxCombinations; $mask++) {
        // Build/evaluate expression for the current operator combination.
        $accumulator = $numbers[0];
        $expression  = (string)$numbers[0];

        for ($i = 1; $i < $n; $i++) {
            // Determine whether this bit is 0 (use '+') or 1 (use '*').
            $useMultiply = (($mask >> ($i - 1)) & 1) === 1;

            if ($useMultiply) {
                $accumulator = $accumulator * $numbers[$i];
                $expression .= " * {$numbers[$i]}";
            } else {
                $accumulator = $accumulator + $numbers[$i];
                $expression .= " + {$numbers[$i]}";
            }
        }

        // Check if we got the target
        if ($accumulator === $target) {
            // Print the successful equation
            echo "{$target}: {$expression} = {$accumulator} => true\n";
            return $target;
        }
    }

    // If we reach here, no combination matched the target.
    echo "{$target}: No combination of +/* produces {$target} => false\n";
    return 0;
}

// Specify the input file path
$inputFile = "input.txt";

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$lines = [];
if ($fileHandle) {
    while (($line = fgets($fileHandle)) !== false) {
        $line = trim($line);

        array_push($lines, $line);
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

$solvableValues = 0;
foreach ($lines as $line) {
    // Split at the colon
    [$targetStr, $numStr] = explode(":", $line);
    $targetStr = trim($targetStr);
    $numStr    = trim($numStr);

    // Convert target to integer
    $target = (int)$targetStr;

    // Convert the rest of the numbers to an array of ints
    $numbers = array_map('intval', explode(" ", $numStr));

    // Check if we can solve this line
    $solvableValues += canSolveWithAddAndMultiply($target, $numbers);
}

printf("Solvable: %d\n", $solvableValues);
?>
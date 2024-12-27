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
    // There are (n-1) slots, each of which can be +, * or || (3 possibilities)
    // => 3^(n-1) total combinations.
    $maxCombinations = 3 ** ($n - 1);

    // We'll use a "bitmask" approach but with base-3 rather than base-2.
    // 0 => '+', 1 => '*', 2 => '||'
    for ($mask = 0; $mask < $maxCombinations; $mask++) {
        $tempMask   = $mask;
        $accumulator = $numbers[0];
        $expression  = (string) $numbers[0];

        for ($i = 1; $i < $n; $i++) {
            $operator = $tempMask % 3;   // which of the 3 operators?
            $tempMask = intdiv($tempMask, 3);

            switch ($operator) {
                case 0: // '+'
                    $accumulator = $accumulator + $numbers[$i];
                    $expression .= " + {$numbers[$i]}";
                    break;
                case 1: // '*'
                    $accumulator = $accumulator * $numbers[$i];
                    $expression .= " * {$numbers[$i]}";
                    break;
                case 2: // '||' (concatenation)
                    // Convert to strings, concatenate, convert back to int.
                    $accumulator = (int) ($accumulator . $numbers[$i]);
                    $expression .= " || {$numbers[$i]}";
                    break;
            }
        }

        // Check if we got the target
        if ($accumulator === $target) {
            echo "{$target}: {$expression} = {$accumulator} => true\n";
            return $target;
        }
    }

    // No combination matched the target
    echo "{$target}: No combination of +, *, || produces {$target} => false\n";
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
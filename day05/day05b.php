<?php
// Specify the input file path
$inputFile = "input.txt";

function canPrint($currentPages, $dependencyMap) {
    printf("canPrint()\n");
    printf("----------\n");
    $printedPages = [];
    for ($i = 0; $i < count($currentPages); $i++) {
        $currentPage = $currentPages[$i];
        if (array_key_exists($currentPage, $dependencyMap)) {
            for ($depCounter = 0; $depCounter < count($dependencyMap[$currentPage]); $depCounter++) {
                $currentDependency = $dependencyMap[$currentPage][$depCounter];
                if (in_array($currentDependency, $currentPages) && !in_array($currentDependency, $printedPages)) {
                    echo "false" . PHP_EOL;
                    return false;
                }
            }
        }
        array_push($printedPages, $currentPage);
    }

    echo "true" . PHP_EOL;
    return true;
}

function getPrintable($currentPages, $dependencyMap) {
    printf("canPrint()\n");
    printf("----------\n");
    $printedPages = [];
    for ($i = 0; $i < count($currentPages); $i++) {
        $currentPage = $currentPages[$i];
        if (array_key_exists($currentPage, $dependencyMap)) {
            for ($depCounter = 0; $depCounter < count($dependencyMap[$currentPage]); $depCounter++) {
                $currentDependency = $dependencyMap[$currentPage][$depCounter];
                if (in_array($currentDependency, $currentPages) && !in_array($currentDependency, $printedPages)) {
                    $currentDependencyIndex = array_search($currentDependency, $currentPages);
                    // Hopefully this doesn't exceed the call stack.
                    $currentPagesCopy = $currentPages;
                    $temp = $currentPagesCopy[$i];
                    $currentPagesCopy[$i] = $currentPagesCopy[$currentDependencyIndex];
                    $currentPagesCopy[$currentDependencyIndex] = $temp;
                    return getPrintable($currentPagesCopy, $dependencyMap);
                }
            }
        }
        array_push($printedPages, $currentPage);
    }

    return $currentPages;
}

function getMiddlePageNumber($currentPages, $dependencyMap) {
    if (!canPrint($currentPages, $dependencyMap)) {
        $properOrder = getPrintable($currentPages, $dependencyMap);
        $middlePageIndex = ceil(count($properOrder) / 2.0);
        return $properOrder[$middlePageIndex - 1];
    }
    return 0;
}

// Open the file in read mode
$fileHandle = fopen($inputFile, "r");

$dependencyMap = [];
$middlePageNumbers = 0;

if ($fileHandle) {
    $reachedBreak = false;

    // Read and print each line until end of file
    while (($line = fgets($fileHandle)) !== false) {
        $line = trim($line);
        if (!$reachedBreak && strlen($line) === 0) {
            $reachedBreak = true;
        }
        elseif (!$reachedBreak) {
            // Split the string at '|'
            list($num1, $num2) = explode('|', $line);

            // Convert them to integers (optional if you actually need them as integers)
            $num1 = (int)$num1;
            $num2 = (int)$num2;
            $dependencyMap[$num2][] = $num1;
        }
        else {
            $nums = explode(',', $line);
            for ($i = 0; $i < count($nums); $i++) {
                $nums[$i] = (int)$nums[$i];
            }
            $middlePageNumbers += getMiddlePageNumber($nums, $dependencyMap);
        }
    }

    // Close the file handle
    fclose($fileHandle);
} else {
    echo "Error: Unable to open the file.";
}

echo "Sum of middle pages: " . $middlePageNumbers . PHP_EOL;
?>
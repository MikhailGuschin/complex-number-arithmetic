<?php
/**
 * Script to run tests.
 * run cli command: php run_tests.php
 */
require 'TestComplex.php';

/** Precision to check. */
$prec = 2;
$maxPrec = Complex::MAX_FLOAT_PRECISION;
$minPrec = 0;
foreach ($argv as $key => $arg) {
	switch($arg) {
		case '-h':
		case '--help':
			echo "Usage: php run_tests.php [args...]\n";
			echo "Command to run tests. Supports options: \n  -h (--help): Show help information.\n  -p (--precision): Precision of complex number to check. Int between {$minPrec} and {$maxPrec}. Default is {$prec}. \n";
			exit;
		case '-p';
		case '--precision':
			if (!is_numeric($argv[$key + 1])) {
				echo "Error: Precision must be int value."."\n";
				exit;
			}
			if ($argv[$key + 1] > $maxPrec) {
				echo "Error: Precision must be int value less then or equal to {$maxPrec}."."\n";
				exit;
			}
			if ($argv[$key + 1] < $minPrec) {
				echo "Error: Precision must be int value bigger then {$minPrec}."."\n";
				exit;
			}
			$prec = $argv[$key + 1];
	}
}

echo "Precision is {$prec}.\n";
TestComplex::testAll($prec);
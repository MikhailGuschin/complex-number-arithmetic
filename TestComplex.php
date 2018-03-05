<?php

include_once 'Complex.php';

/**
 * Class provides unit tests of Complex class methods: add, sub, multi, div
 *
 * Each class example creates test_complex.txt file with test data. Count of data depends on float precision
 * For 1 float number precision (example: 0.1) will be created file 1.6 Kb size with 10 rows test data
 * For 2 float number precision (example: 0.01) will be created file 17 Kb size with 100 rows test data
 * For 3 float number precision (example: 0.001) will be created file 188 Kb size with 1000 rows test data
 * For 4 float number precision (example: 0.0001) will be created file 2 Mb size with 10000 rows test data
 * For 5 float number precision (example: 0.00001) will be created file 21.2 Mb size with 100000 rows test data. It's default float precision
 * For 6 float number precision (example: 0.000001) will be created file 224 Mb size with 1000000 rows test data
 * For 7 float number precision (example: 0.0000001) will be created file 2.36 Gb size with 10000000 rows test data
 * For 8 float number precision (example: 0.00000001) will be created file >24 Gb size with 100000000 rows test data
 *
 * PHP version 7
 *
 * @author Mikhail Guschin <gn.mikle@gmail.com>
 */
class TestComplex {

    /*
     * Default float precision
     * @var int
     */
    protected $floatPrecision = 5;

    /**
     * Random float generator
     * @param int $min default value: 1
     * @param int $max default value: -1
     * @return float
     */
    protected function randomFloat($min = -1, $max = 1): float {
        $rand = ($min+lcg_value()*(abs($max-$min)));
        return round($rand, $this->floatPrecision + 1);
    }

    /** 
     * Constructor generate file test_complex.txt with test data. Count data depends on float precision
     * @param int $floatPrecision
     */
    public function __construct(int $floatPrecision = null) {
        if (!is_null($floatPrecision)) {
            $this->floatPrecision = $floatPrecision;
        }
        $fp = fopen("test_complex.txt", "w");
        if ($fp === false) {
            throw new Exception("Test file couldn't be open");
        }
        for ($i = 0; $i < pow(10, $this->floatPrecision); $i++) {
            $complex1 = new Complex($this->randomFloat(), $this->randomFloat(), $this->floatPrecision);
            $complex2 = new Complex($this->randomFloat(), $this->randomFloat(), $this->floatPrecision);
            $sum = $complex1->add($complex2);
            $sub = $complex1->sub($complex2);
            $multi = $complex1->multi($complex2);
            $div = $complex1->div($complex2);
            $succ = fwrite($fp, "complex1={{$complex1}}; complex2={{$complex2}}; sum={{$sum}}; sub={{$sub}}; multi={{$multi}}; div={{$div}}\n");
            if (false === $succ) {
                throw new Exception("Test file couldn't be write");
            }
        }
        fclose($fp);
    }

    /**
     * Test sum method. Check method: expression a+b=c true when a=c-b and b=c-a
     * @param int $floatPrecision
     */
    public static function testSum(int $floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            // Parse test data row
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $sum = new Complex($matches[2][1], $matches[2][2], $test->floatPrecision);
                
                /**
                 * new Complex object subtraction $sum and $b
                 * @var Complex
                 */
                $exp_a = $sum->sub($b);
                
                /**
                 * new Complex object subtraction $sum and $a
                 * @var Complex
                 */
                $exp_b = $sum->sub($a);
                
                if (strval($exp_a) != strval($a)) {
                    throw new Exception("Test A Failed! Expect: {$a}; Value: {$exp_a}");
                }
                if (strval($exp_b) != strval($b)) {
                    throw new Exception("Test B Failed! Expect: {$b}; Value: {$exp_b}");
                }
            } else {
                throw new Exception("Invalid test file row format");
            }
        }
        echo "Test sum {$count} complex Success!\n";
    }

    /**
     * Test sub method. Check method: expression a-b=c true when a=b+c and b=a-c
     * @param int $floatPrecision
     */
    public static function testSub(int $floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            // Parse test data row
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $sub = new Complex($matches[3][1], $matches[3][2], $test->floatPrecision);

                /**
                 * new Complex object addition $sub and $b
                 * @var Complex
                 */
                $exp_a = $sub->add($b);
                
                /**
                 * new Complex object subtraction $c and $sub
                 * @var Complex
                 */
                $exp_b = $a->sub($sub);
                
                if (strval($exp_a) != strval($a)) {
                    throw new Exception("Test A Failed! Expect: {$a}; Value: {$exp_a}");
                }
                if (strval($exp_b) != strval($b)) {
                    throw new Exception("Test B Failed! Expect: {$b}; Value: {$exp_b}");
                }
            } else {
                throw new Exception("Invalid test file row format");
            }
        }
        echo "Test sub {$count} complex Success!\n";
    }

    /**
     * Test multi method. Check method: expression a*b=c true when a=c/b and b=c/a
     * @param int $floatPrecision
     */
    public static function testMulti(int $floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            // Parse test data row
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $multi = new Complex($matches[4][1], $matches[4][2], $test->floatPrecision);

                /**
                 * new Complex object division $multi and $b
                 * @var Complex
                 */
                $exp_a = $multi->div($b);
                $abs_real_a = abs($exp_a->getReal() - $a->getReal());
                $abs_img_a = abs($exp_a->getImg() - $a->getImg());

                // Check must be into predetermined float precision
                if ($abs_real_a > 1/pow(10, $test->floatPrecision) || $abs_img_a > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test A Failed! Expect: {$a}; Value: {$exp_a}");
                }

                /**
                 * new Complex object division $multi and $a
                 * @var Complex
                 */
                $exp_b = $multi->div($a);
                $abs_real_b = abs($exp_b->getReal() - $b->getReal());
                $abs_img_b = abs($exp_b->getImg() - $b->getImg());

                // Check must be into predetermined float precision
                if ($abs_real_b > 1/pow(10, $test->floatPrecision) || $abs_img_b > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test B Failed! Expect: {$b}; Value: {$exp_b}");
                }
            } else {
                throw new Exception("Invalid test file row format");
            }
        }
        echo "Test multi {$count} complexes Success!\n";
    }

    /**
     * Test div method. Check method: expression a/b=c true when a=c*b and b=a/c
     * @param int $floatPrecision
     */
    public static function testDiv(int $floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            // // Parse test data row
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $div = new Complex($matches[5][1], $matches[5][2], $test->floatPrecision);

                /**
                 * new Complex object multiplication $div and $b
                 * @var Complex
                 */
                $exp_a = $div->multi($b);
                $abs_real_a = abs($exp_a->getReal() - $a->getReal());
                $abs_img_a = abs($exp_a->getImg() - $a->getImg());

                // Check must be into predetermined float precision
                if ($abs_real_a > 1/pow(10, $test->floatPrecision) || $abs_img_a > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test A Failed! Expect: {$a}; Value: {$exp_a}");
                }

                /**
                 * new Complex object division $a and $div
                 * @var Complex
                 */
                $exp_b = $a->div($div);
                $abs_real_b = abs($exp_b->getReal() - $b->getReal());
                $abs_img_b = abs($exp_b->getImg() - $b->getImg());

                // Check must be into predetermined float precision
                if ($abs_real_b > 1/pow(10, $test->floatPrecision) || $abs_img_b > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test B Failed! Expect: {$b}; Value: {$exp_b}");
                }
            } else {
                throw new Exception("Invalid test file row format");
            }
        }
        echo "Test div {$count} complexes Success!\n";
    }

    /**
     * Test all methods
     * @param int $floatPrecision
     */
    public static function testAll(int $floatPrecision = null) {
        self::testSum($floatPrecision);
        self::testSub($floatPrecision);
        self::testMulti($floatPrecision);
        self::testDiv($floatPrecision);
    }
    
}

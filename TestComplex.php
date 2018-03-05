<?php

include_once 'Complex.php';

class TestComplex {

    protected $floatPrecision = 5;
    
    public function randomFloat($min = -1, $max = 1): float {
        $rand = ($min+lcg_value()*(abs($max-$min)));
        return round($rand, $this->floatPrecision + 1);
    }

    public function __construct($floatPrecision = null) {
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
            $diff = $complex1->diff($complex2);
            $multi = $complex1->multi($complex2);
            $div = $complex1->div($complex2);
            $succ = fwrite($fp, "complex1={{$complex1}}; complex2={{$complex2}}; sum={{$sum}}; diff={{$diff}}; multi={{$multi}}; div={{$div}}\n");
            if (false === $succ) {
                throw new Exception("Test file couldn't be write");
            }
        }
        fclose($fp);
    }

    public static function testSum($floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $sum = new Complex($matches[2][1], $matches[2][2], $test->floatPrecision);
                $exp_a = $sum->diff($b);
                $exp_b = $sum->diff($a);
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

    public static function testDiff($floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $diff = new Complex($matches[3][1], $matches[3][2], $test->floatPrecision);
                $exp_a = $diff->add($b);
                $exp_b = $a->diff($diff);
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
        echo "Test diff {$count} complex Success!\n";
    }

    public static function testMulti($floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $multi = new Complex($matches[4][1], $matches[4][2], $test->floatPrecision);

                $exp_a = $multi->div($b);
                $abs_real_a = abs($exp_a->getReal() - $a->getReal());
                $abs_img_a = abs($exp_a->getImg() - $a->getImg());

                if ($abs_real_a > 1/pow(10, $test->floatPrecision) || $abs_img_a > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test A Failed! Expect: {$a}; Value: {$exp_a}");
                }
                
                $exp_b = $multi->div($a);
                $abs_real_b = abs($exp_b->getReal() - $b->getReal());
                $abs_img_b = abs($exp_b->getImg() - $b->getImg());
                
                if ($abs_real_b > 1/pow(10, $test->floatPrecision) || $abs_img_b > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test B Failed! Expect: {$b}; Value: {$exp_b}");
                }
            } else {
                throw new Exception("Invalid test file row format");
            }
        }
        echo "Test multi {$count} complexes Success!\n";
    }

    public static function testDiv($floatPrecision = null) {
        $test = new self($floatPrecision);
        $rows = file("test_complex.txt");
        $count = count($rows);
        if ($count == 0) {
            throw new Exception("Test file is empty");
        }
        foreach($rows as $row) {
            if (preg_match_all("/{([-+]?[0-9]*\.?[0-9]*)([-+]?[0-9]*\.?[0-9]*)\*i}/", $row, $matches, PREG_SET_ORDER)) {
                $a = new Complex($matches[0][1], $matches[0][2], $test->floatPrecision);
                $b = new Complex($matches[1][1], $matches[1][2], $test->floatPrecision);
                $div = new Complex($matches[5][1], $matches[5][2], $test->floatPrecision);

                $exp_a = $div->multi($b);
                $abs_real_a = abs($exp_a->getReal() - $a->getReal());
                $abs_img_a = abs($exp_a->getImg() - $a->getImg());

                if ($abs_real_a > 1/pow(10, $test->floatPrecision) || $abs_img_a > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test A Failed! Expect: {$a}; Value: {$exp_a}");
                }

                $exp_b = $a->div($div);
                $abs_real_b = abs($exp_b->getReal() - $b->getReal());
                $abs_img_b = abs($exp_b->getImg() - $b->getImg());
                
                if ($abs_real_b > 1/pow(10, $test->floatPrecision) || $abs_img_b > 1/pow(10, $test->floatPrecision)) {
                    throw new Exception("Test B Failed! Expect: {$b}; Value: {$exp_b}");
                }
            } else {
                throw new Exception("Invalid test file row format");
            }
        }
        echo "Test div {$count} complexes Success!\n";
    }

    public static function testAll($floatPrecision = null) {
        self::testSum($floatPrecision);
        self::testDiff($floatPrecision);
        self::testMulti($floatPrecision);
        self::testDiv($floatPrecision);
    }
    
}

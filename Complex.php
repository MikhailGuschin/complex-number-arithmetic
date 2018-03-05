<?php

include_once 'IComplexArithmeticOperable.php';

use IComplexArithmeticOperable as Operable;

/**
 * Class provides common arithmetic oprations methods: addition, subtraction, multiplication, division
 *
 * Support algebraic complex number string representation only in "[-]x[+-]y*i" format
 * Support max 8 float precision 
 * 
 * PHP version 7
 *
 * @property float $real real part of complex number
 * @property float $img imaginary part of complex number
 * @property int $precision float precision
 * @author Mikhail Guschin <gn.mikle@gmail.com>
 */
class Complex implements Operable {

    const MAX_FLOAT_PRECISION = 8;

    // Real part
    private $real;
    // Imaginary part
    private $img;
    private $floatPrecision;
    
    public function __construct(float $real, float $img, int $floatPrecision = self::MAX_FLOAT_PRECISION) {
        $p = ($floatPrecision > self::MAX_FLOAT_PRECISION) ? self::MAX_FLOAT_PRECISION : $floatPrecision;
        $this->real = $real;
        $this->img = $img;
        $this->floatPrecision = $p;
    }

    /**
     * @return string complex number "[-]x[+-]y*i" format
     */
    public function __toString() {
        $p = $this->getFloatPrecision() + 4;
        $format = "%.{$p}f%+.{$p}f*i";
        $real = $this->getReal();
        $img = $this->getImg();
        return sprintf($format, $real, $img);
    }

    /**
     * @return float
     */
    public function getReal(): float {
        return $this->real;
    }

    /**
     * @return float
     */
    public function getImg(): float {
        return $this->img;
    }

    /**
     * @return int
     */
    public function getFloatPrecision(): int {
        return $this->floatPrecision;
    }

    /**
     * @return Compex new Complex object
     * @param Operable $complex
     */
    public function add(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        /**
         * Max float precision choose between operands
         * @var int
         */
        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());

        return new self($real_1 + $real_2, $img_1 + $img_2, $p);
    }

    /**
     * @return Complex new Complex object
     * @param Operable $complex
     */
    public function sub(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        /**
         * Max float precision choose between operands
         * @var int
         */
        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());

        return new self($real_1 - $real_2, $img_1 - $img_2, $p);
    }

    /**
     * @return Complex new Complex object
     * @param Operable $complex
     */
    public function multi(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        /**
         * Real part of subtraction two complex numbers. Formula: x1*x2-y1*y2
         * @var float
         */
        $real = $real_1*$real_2 - $img_1*$img_2;
        /**
         * Imaginary part of subtraction two complex numbers. Formula: x1*y2+x2*y1
         * @var float
         */
        $img = $real_1*$img_2 + $real_2*$img_1;

        /**
         * Max float precision choose between operands
         * @var int
         */
        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());

        return new self($real, $img, $p);
    }

    /**
     * @return Complex new Complex object
     * @param Operable $complex
     */
    public function div(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        /**
         * Real part of subtraction two complex numbers. Formula: (x1*x2+y1*y2)/(x2*x2+y2*y2)
         * @var float
         */
        $real = ( $real_1*$real_2 + $img_1*$img_2 ) / ( $real_2*$real_2 + $img_2*$img_2 );
        /**
         * Imaginary part of subtraction two complex numbers. Formula: (x2*y1-x1*y2)/(x2*x2+y2*y2)
         */
        $img = ( $real_2*$img_1 - $real_1*$img_2 ) / ( $real_2*$real_2 + $img_2*$img_2 );

        /**
         * Max float precision choose between operands
         * @var int
         */
        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());
        
        return new self($real, $img, $p);
    }
  
}

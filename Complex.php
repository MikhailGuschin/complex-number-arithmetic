<?php

include_once 'IComplexArithmeticOperable.php';

use IComplexArithmeticOperable as Operable;

class Complex implements Operable {

    const MAX_FLOAT_PRECISION = 8;
    
    private $real;
    private $img;
    private $floatPrecision;
    
    public function __construct(float $real, float $img, int $floatPrecision = self::MAX_FLOAT_PRECISION) {
        $p = ($floatPrecision > self::MAX_FLOAT_PRECISION) ? self::MAX_FLOAT_PRECISION : $floatPrecision;
        $this->real = $real;
        $this->img = $img;
        $this->floatPrecision = $p;
    }

    public function __toString() {
        $p = $this->getFloatPrecision() + 4;
        $format = "%.{$p}f%+.{$p}f*i";
        $real = $this->getReal();
        $img = $this->getImg();
        return sprintf($format, $real, $img);
    }

    public function getReal(): float {
        return $this->real;
    }

    public function getImg(): float {
        return $this->img;
    }

    public function getFloatPrecision(): int {
        return $this->floatPrecision;
    }

    public function add(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());

        return new self($real_1 + $real_2, $img_1 + $img_2, $p);
    }

    public function diff(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());

        return new self($real_1 - $real_2, $img_1 - $img_2, $p);
    }

    public function multi(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        $real = $real_1*$real_2 - $img_1*$img_2;
        $img = $real_1*$img_2 + $real_2*$img_1;

        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());

        return new self($real, $img, $p);
    }

    public function div(Operable $complex): self {

        $real_1 = $this->getReal();
        $real_2 = $complex->getReal();
        $img_1 = $this->getImg();
        $img_2 = $complex->getImg();

        $real = ( $real_1*$real_2 + $img_1*$img_2 ) / ( $real_2*$real_2 + $img_2*$img_2 );
        $img = ( $real_2*$img_1 - $real_1*$img_2 ) / ( $real_2*$real_2 + $img_2*$img_2 );

        $p = max($this->getFloatPrecision(), $complex->getFloatPrecision());
        
        return new self($real, $img, $p);
    }
  
}

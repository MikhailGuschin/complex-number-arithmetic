<?php

/**
 * Interface contains required methods to provide arithmetic operations on complex numbers
 *
 * PHP version 7
 * 
 * @author Mikhail Guschin <gn.mikle@gmail.com>
 * @link https://github.com/MikhailGuschin/complex-number-arithmetic
 */
interface IComplexArithmeticOperable {

    public function getReal(): float;
    public function getImg(): float;
    public function getFloatPrecision(): int;

}


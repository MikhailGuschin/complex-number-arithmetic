<?php

/**
 * Interface contains required methods to provide arithmetic operations on complex numbers
 *
 * PHP version 7
 * 
 * @author Mikhail Guschin <gn.mikle@gmail.com>
 */
interface IComplexArithmeticOperable {

    public function getReal(): float;
    public function getImg(): float;
    public function getFloatPrecision(): int;

}


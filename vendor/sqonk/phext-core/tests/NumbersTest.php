<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use sqonk\phext\core\numbers;

class NumbersTest extends TestCase
{
    public function testConstrainToMin()
    {
        $this->assertSame(numbers::constrain(5, 6, 10), 6);
    }
    
    public function testConstrainToMax()
    {
        $this->assertSame(numbers::constrain(10.1, 6, 10), 10);
    }
    
    public function testNoConstrain()
    {
        $this->assertSame(numbers::constrain(7, 6, 10), 7);
    }
    
    public function testIsWithinFailsMin()
    {
        $this->assertSame(false, numbers::is_within(4, 5, 10));
    }
    
    public function testIsWithinFailsMax()
    {
        $this->assertSame(false, numbers::is_within(11, 5, 10));
    }
    
    public function testIsWithinPasses()
    {
        $this->assertSame(true, numbers::is_within(7, 5, 10));
    }
    
    public function testRandFloat()
    {
        $f1 = numbers::rand_float();
        $f2 = numbers::rand_float(1.5, 2.5);
        $f3 = numbers::rand_float(1.5, 3.5, 1000);
        
        foreach ([$f1, $f2, $f3] as $f)
            $this->assertIsFloat($f);
        
        $this->assertGreaterThanOrEqual(0.0, $f1);
        $this->assertLessThanOrEqual(1.0, $f1);
        
        $this->assertGreaterThanOrEqual(1.5, $f2);
        $this->assertLessThanOrEqual(3.5, $f2);
        
        $this->assertGreaterThanOrEqual(1.5, $f3);
        $this->assertLessThanOrEqual(3.5, $f3);
        
        $this->expectException(InvalidArgumentException::class);
        numbers::rand_float(4.5, 2.5);
    }
}
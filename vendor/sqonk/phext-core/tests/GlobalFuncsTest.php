<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;

class GlobalFuncsTest extends TestCase
{
    public function testPrintstr(): void 
    {
        $this->expectOutputString("This is a test".PHP_EOL);
        printstr('This is a test');
    }
    
    public function testPrintlnBasic(): void 
    {
        $this->expectOutputString("This is a test".PHP_EOL);
        println('This is a test');
    }
    
    public function testPrintlnMultiArg(): void 
    {
        $this->expectOutputString("Arg 1 Arg 2 3".PHP_EOL);
        println('Arg 1', 'Arg 2', '3');
    }
    
    public function testPrintlnArrayDump(): void 
    {
        $a = [1,2,3];
        $this->expectOutputString(var_export($a, true).PHP_EOL);
        println($a);
    }
    
    public function testPrintlnBoolStrFalse(): void 
    {
        $this->expectOutputString('false'.PHP_EOL);
        println(false);
    }
    
    public function testPrintlnBoolStrTrue(): void 
    {
        $this->expectOutputString('true'.PHP_EOL);
        println(true);
    }
    
    public function testSequence()
    {
        $expected = range(0, 10);
        foreach (sequence(10) as $i)
            $this->assertSame($i, array_shift($expected));
        
        $expected = range(1, 10);
        foreach (sequence(1, 5) as $i)
            $this->assertSame($i, array_shift($expected));
        
        $expected = [2,4,6,8,10];
        foreach (sequence(2, 10, 2) as $i)
            $this->assertSame($i, array_shift($expected));
    }
    
    public function testObjectify()
    {
        $o = objectify(['x' => 1, 'y' => 2]);
        $this->assertIsObject($o);
        
        $this->assertSame(1, $o->x);
        $this->assertSame(2, $o->y);
    }
    
    public function testNamedObjectify()
    {
        $c = named_objectify(['x', 'y']);
        $this->assertIsCallable($c);
        
        $o = $c(1,2);
        $this->assertIsObject($o);
        $this->assertSame(1, $o->x);
        $this->assertSame(2, $o->y);
    }
    
    public function testVarIsStringable()
    {
        $ob = new class() {
            public function __toString() {
                return 'test';
            }
        };
        $ob2 = new class() {};
        
        $this->assertSame(true, var_is_stringable('abc'));
        $this->assertSame(true, var_is_stringable(2));
        $this->assertSame(true, var_is_stringable($ob));
        $this->assertSame(false, var_is_stringable($ob2));
    }
    
    public function testBoolstr(): void 
    {
        $this->assertSame(expected:'true', actual:boolstr(true));
        $this->assertSame(expected:'false', actual:boolstr(false));
    }
    
    public function testOnExitScope(): void 
    {
        $func = function() {
            on_exit_scope($_, fn() => println("after text"));
            println("before text");
        };
        $cr = PHP_EOL;
        $this->expectOutputString("before text{$cr}after text{$cr}");
        $func();
    }
}
<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use sqonk\phext\core\dates;

class DatesTest extends TestCase
{
    public function testFlipAusUs()
    {
        $this->assertSame("05/25/2020", dates::flip_aus_us("25/05/2020"));
        $this->assertSame("18/03/20", dates::flip_aus_us("03/18/20"));
        $this->assertSame("18/03/2020 01:42:35 am", dates::flip_aus_us("03/18/2020 01:42:35 am"));

        $this->expectException(InvalidArgumentException::class);
        dates::flip_aus_us("25/05");
    }

    public function testIsDate()
    {
        $this->assertSame(true, dates::is_date('01/01/2020'));
        $this->assertSame(true, dates::is_date('01/03/20'));
        $this->assertSame(true, dates::is_date('1980-06-03'));
        $this->assertSame(true, dates::is_date('Mon 29 Jun'));

        $this->assertSame(false, dates::is_date('hello'));
        $this->assertSame(false, dates::is_date('19800307'));
    }

    public function testStrtotime()
    {
        $this->assertEquals(1576800000, dates::strtotime('2020-01-01'));
    }

    public function testDiff()
    {
        $this->assertSame(86400, dates::diff('2020-01-01', '2020-01-02'));
    }

    public function testIsValid()
    {
        $this->assertSame(true, dates::is_valid('2020-01-02', 'Y-m-d'));
        $this->assertSame(false, dates::is_valid('10/02/2020', 'Y-m-d', 'Aus != Y-m-d'));

        $this->assertSame(false, dates::is_valid('d/m/Y', '02/10/1'));

        $this->assertSame(true, dates::is_valid('25/03/2020', 'd/m/Y', 'Aus Format Match'));
        $this->assertSame(false, dates::is_valid('03/25/2020', 'd/m/Y', 'Us != Aus'));
    }

    public function testDaysBetween()
    {
        $now = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $this->assertSame(0, dates::days_between());
        $this->assertSame(1, dates::days_between($yesterday));
        $this->assertSame(1, dates::days_between($yesterday, 'now'));
        $this->assertSame(1, dates::days_between('', $yesterday));
        $this->assertSame(1, dates::days_between('now', $yesterday));
        $this->assertSame(2, dates::days_between($yesterday, '', true));
    }
}

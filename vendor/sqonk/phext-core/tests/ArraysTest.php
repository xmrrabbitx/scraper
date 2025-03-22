<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use sqonk\phext\core\arrays;

class ArraysTest extends TestCase
{
    public function testSafeValue()
    {
        $arr = ['a' => 2, 'b' => 3];
        $this->assertSame(2, arrays::safe_value($arr, 'a'));
        $this->assertSame(null, arrays::safe_value($arr, 'c'));
        $this->assertSame('test', arrays::safe_value($arr, 'c', 'test'));
    }
    
    public function testPop()
    {
        $arr = [1,2,3,4]; $items = [];
        $this->assertSame([1,2], arrays::pop($arr, 2, $items));
        $this->assertSame([4,3], $items);
    }
    
    public function testShift()
    {
        $arr = [1,2,3,4]; $items = [];
        $this->assertSame([3,4], arrays::shift($arr, 2, $items));
        $this->assertSame([1,2], $items);
    }
    
    public function testAddConstrain()
    {
        $items = [1];
        $this->assertSame([1,1], arrays::add_constrain($items, 1, 3));
        $this->assertSame([1,1,1], arrays::add_constrain($items, 1, 3));
        $this->assertSame([1,1,1], arrays::add_constrain($items, 1, 3));
    }
    
    public function testSorted()
    {
        $items = ['c' => 2, 'b' => 1, 'a' => 3];
        $this->assertSame([1,2,3], arrays::sorted($items));
        $this->assertSame(['a' => 3, 'b' => 1, 'c' => 2], arrays::sorted($items, BY_KEY));
        $this->assertSame(['b' => 1,'c' => 2, 'a' => 3], arrays::sorted($items, MAINTAIN_ASSOC));
    }
    
    public function testReverseSorted()
    {
        $items = ['c' => 2, 'b' => 1, 'a' => 3];
        $this->assertSame([3,2,1], arrays::rsorted($items));
        $this->assertSame(['c' => 2, 'b' => 1, 'a' => 3], arrays::rsorted($items, BY_KEY));
        $this->assertSame(['a' => 3,'c' => 2, 'b' => 1], arrays::rsorted($items, MAINTAIN_ASSOC));
    }
    
    public function testKeySort()
    {
        $arr = [
            'x' => ['a' => 2, 'b' => 13, 'c' => 2],
            'm' => ['a' => 1, 'b' => 20, 'c' => 1],
            'y' => ['a' => 5, 'b' => 12, 'c' => 2]
        ];
        $arr2 = $arr;
        $arr3 = $arr;
        
        $non = arrays::key_sort($arr, 'a', false);
        $assoc = arrays::key_sort($arr2, 'a', true);
            
        $this->assertSame([
            ['a' => 1, 'b' => 20, 'c' => 1],
            ['a' => 2, 'b' => 13, 'c' => 2],
            ['a' => 5, 'b' => 12, 'c' => 2]
        ], $non);
        
        $this->assertSame([
            'm' => ['a' => 1, 'b' => 20, 'c' => 1],
            'x' => ['a' => 2, 'b' => 13, 'c' => 2],
            'y' => ['a' => 5, 'b' => 12, 'c' => 2]
        ], $assoc);
        
        // multi key test
        $arr3['m']['a'] = 5;
        $arr3['m']['c'] = 3;
        $r = arrays::key_sort($arr3, ['a', 'c'], true);
        $this->assertSame([
            'x' => ['a' => 2, 'b' => 13, 'c' => 2],
            'y' => ['a' => 5, 'b' => 12, 'c' => 2],
            'm' => ['a' => 5, 'b' => 20, 'c' => 3]
        ], $r);
    }
    
    public function testGroupBy()
    {
        $arr = [
            ['a' => 2, 'b' => 13],
            ['a' => 2, 'b' => 15],
            ['a' => 1, 'b' => 20],
            ['a' => 5, 'b' => 12],
        ];
        $expected = [
            2 => [
                ['a' => 2, 'b' => 13],
                ['a' => 2, 'b' => 15]
            ],
            1 => [
                ['a' => 1, 'b' => 20]
            ],
            5 => [
                ['a' => 5, 'b' => 12]
            ]
        ];
        
        $this->assertSame($expected, arrays::group_by($arr, 'a'));
    }
    
    public function testSplitBy()
    {
        $numbers = [1,2,3,4,5,6,7,8,9,10,11];
        $sets = arrays::splitby($numbers, function($v) {
            if ($v == 11)
                return null; // to test omission from results.
            return ($v % 2 == 0) ? 'even' : 'odd';
        });
        
        $this->assertSame(2, count($sets));
        $this->assertSame(true, array_key_exists('even', $sets));
        $this->assertSame(true, array_key_exists('odd', $sets));
        
        $this->assertSame([1,3,5,7,9], $sets['odd']);
        $this->assertSame([2,4,6,8,10], $sets['even']);
        
        $this->expectException(UnexpectedValueException::class);
        arrays::splitby($numbers, function($v) {
            return [$v];
        });
    }
    
    public function testTranspose()
    {
        $data = [
            ['character' => 'Actor A', 'decade' => 1970, 'appearances' => 1],
            ['character' => 'Actor A', 'decade' => 1980, 'appearances' => 2],
            ['character' => 'Actor A', 'decade' => 1990, 'appearances' => 2],
            ['character' => 'Actor A', 'decade' => 2000, 'appearances' => 1],
            ['character' => 'Actor A', 'decade' => 2010, 'appearances' => 1],
    
            ['character' => 'Actor B', 'decade' => 1980, 'appearances' => 1],
            ['character' => 'Actor B', 'decade' => 1990, 'appearances' => 1],
            ['character' => 'Actor B', 'decade' => 2000, 'appearances' => 1],
        ];
        $transformed = arrays::transpose(arrays::key_sort($data, 'decade'), 'decade', ['character' => 'appearances']);
        
        $expected = [
            ['decade' => 1970, 'Actor A' => 1, 'Actor B' => ''],
            ['decade' => 1980, 'Actor A' => 2, 'Actor B' => 1],
            ['decade' => 1990, 'Actor A' => 2, 'Actor B' => 1],
            ['decade' => 2000, 'Actor A' => 1, 'Actor B' => 1],
            ['decade' => 2010, 'Actor A' => 1, 'Actor B' => '']
        ];
        
        $this->assertSame($expected, $transformed);
    }
    
    public function testFirst()
    {
        $arr = [1,2,3];
        $this->assertSame(1, arrays::first($arr));
    }
    
    public function testLast()
    {
        $arr = [1,2,3];
        $this->assertSame(3, arrays::last($arr));
    }
    
    public function testMiddle()
    {
        $this->assertSame(2, arrays::middle([1,2,3]));
        $this->assertSame(2, arrays::middle([1,2,3,4], true));
        $this->assertSame(3, arrays::middle([1,2,3,4], false));
    }
    
    public function testPrune()
    {
        $arr = [1,2,'','a',' ','d'];
        $this->assertSame([0 => 1,1 => 2, 3 => 'a',4 => ' ', 5 => 'd'], arrays::prune($arr));
        $this->assertSame([0 => 1,1 => 2, 2 => '', 3 => 'a', 5 => 'd'], arrays::prune($arr, ' '));
    }
    
    public function testCompact()
    {
        $arr = [1,2,null,'a',null,'d'];
        $this->assertSame([0 => 1, 1 => 2, 3 => 'a', 5 => 'd'], arrays::compact($arr));
    }
    
    public function testOnlyKeys()
    {
        $arr = ['a' => 10, 'b' => 2, 'c' => 40];
        $this->assertSame(['a' => 10, 'c' => 40], arrays::only_keys($arr, 'a', 'c'));
        $this->assertSame(['a' => 10, 'c' => 40], arrays::only_keys($arr, ['a', 'c']));
    }
    
    public function testMap()
    {
        $arr = ['a' => 1, 'b' => 2, 'c' => 3];
        $expected = ['a' => 3, 'b' => 4, 'c' => 5];
        $this->assertSame($expected, arrays::map($arr, function($v, $k) {
            return $v + 2;
        }));
    }
    
    public function testChoose()
    {
        $arr = range(1, 10);
        $this->assertContains(arrays::choose($arr), $arr);
    }
    
    public function testZip()
    {
        $a1 = [1,2,3];
        $a2 = ['a', 'b'];
        foreach (arrays::zip($a1, $a2) as $set)
            $this->assertContains($set, [ [1, 'a'], [2, 'b'], [3,null] ]);
    }
    
    public function testZipAll()
    {
        $a1 = [1,2,3];
        $a2 = ['a', 'b'];
        foreach (arrays::zipall($a1, $a2) as $set)
            $this->assertContains($set, [ [1, 'a'], [1,'b'], 
                [2, 'a'], [2,'b'], [3,'a'], [3,'b'] 
        ]);
    }
    
    public function testEncapsulate()
    {
        $arr = ['a', 'b', 'c'];
        $this->assertSame(['"a"', '"b"', '"c"'], arrays::encapsulate($arr, '"'));
        $this->assertSame(['"ab', '"bb', '"cb'], arrays::encapsulate($arr, '"', 'b'));
    }
    
    public function testImplodeAssoc()
    {
        $arr = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertSame('a=1&b=2&c=3', arrays::implode_assoc('&', $arr, '='));
    }
    
    public function testValues()
    {
        $arr = ['a' => 1, 'b' => 2, 'g'=> 10];
        $this->assertSame([1,2], arrays::values($arr, 'a', 'b'));
    }
    
    public function testImplode()
    {
        $arr = [
            'a', 'b', 'c', [1,2,3]
        ];
        $this->assertSame('a,b,c,1,2,3', arrays::implode(',', $arr));
        $this->assertSame('a,b,c,1-2-3', arrays::implode(',', $arr, '-'));
    }
    
    public function testImplodeOnly()
    {
        $arr = [
            'a', 'b', 'c', null
        ];
        $this->assertSame('a,c', arrays::implode_only(',', $arr, 0, 2, 3));
    }
    
    public function testContains()
    {
        $this->assertSame(true, arrays::contains([1,2,3], 2));
        $this->assertSame(false, arrays::contains([1,2,3], 4));
        
        /* 
            Test for 'end' and 'contains' here is intentional. contains() originally  
            checked for 'callable' status, which was changed to strictly Closure
            detection for custom needles.
        */ 
        $this->assertSame(true, arrays::contains(['a', 'end', 'start'], 'end'));
        $r = arrays::contains(['a', 'end', 'start'], function($v) {
            return ($v == 'end');
        });
        $this->assertSame(true, $r);
        $this->assertSame(false, arrays::contains(['a', 'end', 'start'], 'contains'));
    }
    
    public function testAll()
    {
        $this->assertSame(true, arrays::all([2,2,2,2], 2));
        $this->assertSame(false, arrays::all([2,2,3,2], 2));
    }
    
    public function testStartsWith()
    {
        $this->assertSame(true, arrays::starts_with([1,2,3], 1));
        $this->assertSame(false, arrays::starts_with([1,2,3], 2));
    }
    
    public function testEndsWith()
    {
        $this->assertSame(true, arrays::ends_with([1,2,3], 3));
        $this->assertSame(false, arrays::ends_with([1,2,3], 2));
    }
    
    public function testSample()
    {
        $sample = arrays::sample(1, 50, 20);
        $this->assertSame(20, count($sample));
        
        foreach ($sample as $v) {
            $this->assertGreaterThan(0, $v);
            $this->assertLessThan(51, $v);
        }
    }
    
    public function testFirstMatch()
    {
        $arr = [
            ['name' => 'Phil', 'age' => 20],
            ['name' => 'Jane', 'age' => 25],
            ['name' => 'Jill', 'age' => 18],
            ['name' => 'Jane', 'age' => 33], // duplicate name test (should find prior item).
        ];
        
        $r = arrays::first_match($arr, function($item) {
            return $item['name'] == 'Jane';
        });
        
        $this->assertEquals(['name' => 'Jane', 'age' => 25], $r);
        
        $r = arrays::first_match($arr, function($item) {
            return $item['name'] == 'Bob';
        });
        $this->assertNull($r);
    }
    
    public function testHead(): void 
    {
       $arr = [1,2,3,4,5,6];
       
       $this->assertEquals(expected:[1,2], actual:arrays::head($arr, amount:2));
       $this->assertEquals(expected:[1,2,3], actual:arrays::head($arr, amount:3));
       $this->assertEquals(expected:[1,2,3,4,5,6], actual:arrays::head($arr, amount:6));
       $this->assertEquals(expected:[1,2,3,4,5,6], actual:arrays::head($arr, amount:7));
       
       $this->expectException(\Exception::class);
       arrays::head($arr, amount:0);
    }
    
    public function testTail(): void 
    {
       $arr = [1,2,3,4,5,6];
       
       $this->assertEquals(expected:[5,6], actual:arrays::tail($arr, amount:2));
       $this->assertEquals(expected:[4,5,6], actual:arrays::tail($arr, amount:3));
       $this->assertEquals(expected:[1,2,3,4,5,6], actual:arrays::tail($arr, amount:6));
       $this->assertEquals(expected:[1,2,3,4,5,6], actual:arrays::tail($arr, amount:7));
       
       $this->expectException(\Exception::class);
       arrays::tail($arr, amount:0);
    }
}
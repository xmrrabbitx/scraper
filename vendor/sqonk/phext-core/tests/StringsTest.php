<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use sqonk\phext\core\strings;

class StringsTest extends TestCase
{
  public function testPop()
  {
    $str = '1,2,3,4,5,6';
    $this->assertSame('1,2,3,4', strings::pop($str, ',', 2));
  }
    
  public function testShift()
  {
    $str = '1,2,3,4,5,6';
    $this->assertSame('3,4,5,6', strings::shift($str, ',', 2));
  }
    
  public function testPopex()
  {
    $str = '1,2,3,4,5,6';
    $this->assertSame('1,2,3,4,5', strings::popex($str, ',', $item));
    $this->assertSame('6', $item);
  }
    
  public function testPop_ex()
  {
    $str = '1,2,3,4,5,6';
    $item = strings::pop_ex($str, ',');
    $this->assertSame('1,2,3,4,5', $str);
    $this->assertSame('6', $item);
  }
    
  public function testShiftex()
  {
    $str = '1,2,3,4,5,6';
    $this->assertSame('2,3,4,5,6', strings::shiftex($str, ',', $item));
    $this->assertSame('1', $item);
  }
    
  public function testShift_ex()
  {
    $str = '1,2,3,4,5,6';
    $item = strings::shift_ex($str, ',');
    $this->assertSame('2,3,4,5,6', $str);
    $this->assertSame('1', $item);
  }
    
  public function testContainsWord()
  {
    $sample = 'Somewords have a word or two';
    $this->assertSame(true, strings::contains_word($sample, 'word'));
    $this->assertSame(false, strings::contains_word($sample, 'words'));
  }
    
  public function testReplaceWord()
  {
    $sample = 'Somewords have a word or two';
    $this->assertSame('Somewords have a charm or two', strings::replace_word($sample, 'word', 'charm'));
    $this->assertSame('Somewords have a word or two', strings::replace_word($sample, 'words', 'charm'));
  }
    
  public function testReplaceWords()
  {
    $sample = 'Somewords have a word or two';
    $this->assertSame('Somewords have a charm or two', strings::replace_words($sample, ['word' => 'charm', 'words' => 'no']));
  }
    
  public function testOneSpace()
  {
    $sample = 'a  b     c';
    $this->assertSame('a b c', strings::one_space($sample));
  }
    
  public function testTruncate()
  {
    $sample = 'sample';
    $this->assertSame('sampl...', strings::truncate($sample, 5, 'r'));
    $this->assertSame('...ample', strings::truncate($sample, 5, 'l'));
    $this->assertSame('sa...ple', strings::truncate($sample, 5, 'c'));
  }
    
  public function testStripNonAlphaNumeric()
  {
    $this->assertSame('123abc', strings::strip_non_alpha_numeric('123abc'));
    $this->assertSame('123abc', strings::strip_non_alpha_numeric('12#3abc$'));
    $this->assertSame(false, strings::strip_non_alpha_numeric('123abc', 7));
    $this->assertSame(false, strings::strip_non_alpha_numeric('123abc', 1, 5));
  }
}

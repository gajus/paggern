<?php
class TokenisationTest extends PHPUnit_Framework_TestCase {
    public function testLiteralString () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('abc');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'literal', 'string' => 'abc'], $tokens[0]);
    }

    public function testExplicitRange () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('[a-z]{2}');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'range', 'token' => 'a-z', 'repetition' => 2], $tokens[0]);
    }

    public function testImplicitRange () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('[0-9]');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'range', 'token' => '0-9', 'repetition' => 1], $tokens[0]);
    }

    public function testCombined () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('abc[a-z]{2}[0-9]');
        $this->assertCount(3, $tokens);

        $this->assertSame(['type' => 'literal', 'string' => 'abc'], $tokens[0]);
        $this->assertSame(['type' => 'range', 'token' => 'a-z', 'repetition' => 2], $tokens[1]);
        $this->assertSame(['type' => 'range', 'token' => '0-9', 'repetition' => 1], $tokens[2]);
    }
}
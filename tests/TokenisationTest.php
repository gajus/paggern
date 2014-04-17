<?php
class TokenisationTest extends PHPUnit_Framework_TestCase {
    public function testLiteralString () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('abc');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'literal', 'string' => 'abc'], $tokens[0]);
    }

    public function testRangeImplicit () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('[0-9]');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'range', 'token' => '0-9', 'repetition' => 1], $tokens[0]);
    }

    public function testRangeExplicit () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('[a-z]{2}');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'range', 'token' => 'a-z', 'repetition' => 2], $tokens[0]);
    }

    public function testClassImplicit () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('\U');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'class', 'class' => \Gajus\Parsley\Parser::CLASS_UPPERCASE_UNAMBIGUOUS, 'repetition' => 1], $tokens[0]);
    }

    public function testClassExplicit () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('\U{10}');

        $this->assertCount(1, $tokens);
        $this->assertSame(['type' => 'class', 'class' => \Gajus\Parsley\Parser::CLASS_UPPERCASE_UNAMBIGUOUS, 'repetition' => 10], $tokens[0]);
    }

    public function testCombined () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('abc[a-z]{2}[0-9]\U\U{3}');
        $this->assertCount(5, $tokens);

        $this->assertSame(['type' => 'literal', 'string' => 'abc'], $tokens[0]);
        $this->assertSame(['type' => 'range', 'token' => 'a-z', 'repetition' => 2], $tokens[1]);
        $this->assertSame(['type' => 'range', 'token' => '0-9', 'repetition' => 1], $tokens[2]);
        $this->assertSame(['type' => 'class', 'class' => \Gajus\Parsley\Parser::CLASS_UPPERCASE_UNAMBIGUOUS, 'repetition' => 1], $tokens[3]);
        $this->assertSame(['type' => 'class', 'class' => \Gajus\Parsley\Parser::CLASS_UPPERCASE_UNAMBIGUOUS, 'repetition' => 3], $tokens[4]);
    }
}
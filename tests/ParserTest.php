<?php
class ParserTest extends PHPUnit_Framework_TestCase {
    public function testTokeniseLiteralString () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->tokenise('a[a-z]{1}b[0-9]');

        die(var_dump($tokens));

        $this->assertCount(1, $tokens[0]);
        $this->assertInstanceOf('Gajus\Parsley\Token\Literal', $tokens[0]);
        $this->assertSame('foobar', $tokens[0]->getValue());
    }
}
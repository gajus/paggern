<?php
class ParserTest extends PHPUnit_Framework_TestCase {
    public function testLiteralString () {
        $parser = new \Gajus\Parsley\Parser();
        $tokens = $parser->parse('foobar');

        $this->assertCount(1, $tokens[0]);
        $this->assertInstanceOf('Gajus\Parsley\Token\Literal', $tokens[0]);
        $this->assertSame('foobar', $tokens[0]->getValue());
    }
}
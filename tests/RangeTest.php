<?php
class RangeTest extends PHPUnit_Framework_TestCase {
    /**
     * @dataProvider alphabeticalProvider
     */
    public function testAlphabetical ($range_defition, $haystack) {
        $this->assertSame($haystack, \Gajus\Parsley\Lexer::expandRange($range_defition));
    }

    public function alphabeticalProvider () {
        return [
            ['a-z', 'abcdefghijklmnopqrstuvwxyz'],
            ['A-Z', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
            ['0-9', '0123456789'],
            ['abc', 'abc']
        ];
    }

    /**
     * @dataProvider invalidProvider
     * @expectedException Gajus\Parsley\Exception\LogicException
     * @expectedExceptionMessage Invalid range definition. Start greater than end.
     */
    public function testInvalid ($range_defition) {
        \Gajus\Parsley\Lexer::expandRange($range_defition);
    }

    public function invalidProvider () {
        return [
            ['z-a'],
            ['9-0']
        ];
    }

    /**
     * @dataProvider composedProvider
     */
    public function testComposed ($range_defition, $haystack) {
        $this->assertSame($haystack, \Gajus\Parsley\Lexer::expandRange($range_defition));
    }

    public function composedProvider () {
        return [
            ['a-c0-3', 'abc0123'],
            ['abc0-3', 'abc0123'],
            ['a-ca-c', 'abc']
        ];
    }
}
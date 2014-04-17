<?php
class RangeTest extends PHPUnit_Framework_TestCase {
    /**
     * @dataProvider alphabeticalRangeProvider
     */
    public function testAlphabeticalRange ($range_defition, $haystack) {
        $this->assertSame($haystack, \Gajus\Parsley\Parser::expandRange($range_defition));
    }

    public function alphabeticalRangeProvider () {
        return [
            ['a-z', 'abcdefghijklmnopqrstuvwxyz'],
            ['A-Z', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
            ['0-9', '0123456789'],
            ['abc', 'abc']
        ];
    }

    /**
     * @dataProvider invalidRangeProvider
     * @expectedException Gajus\Parsley\Exception\LogicException
     * @expectedExceptionMessage Invalid range definition. Start greater than end.
     */
    public function testInvalidRange ($range_defition) {
        \Gajus\Parsley\Parser::expandRange($range_defition);
    }

    public function invalidRangeProvider () {
        return [
            ['z-a'],
            ['9-0']
        ];
    }
}
<?php
namespace Gajus\Parsley;

/**
 * @link https://github.com/gajus/parsley for the canonical source repository
 * @license https://github.com/gajus/parsley/blob/master/LICENSE BSD 3-Clause
 */
class Parser {
    const
        CLASS_UPPERCASE_UNAMBIGUOUS = 1;

    private
        /**
         * @var Psr\Log\LoggerInterface
         */
        $logger;

    /**
     * @param string $pattern
     * @param boolean $expand
     * @return array
     */
    public function tokenise ($pattern, $expand = false) {
        preg_match_all('
        /
                (?<class_U_explicit>\\\U)
                \{
                    (?<class_U_repetition>[0-9]+)
                \}
            |
                (?<class_U_implicit>\\\U)
            |
                \[
                    (?<range_token_explicit>[^]]+)
                \]
                \{
                    (?<range_repetition>[0-9]+)
                \}
            |
                \[
                    (?<range_token_implicit>[^]]+)
                \]
            |
                (?<literal_string>[^\\\[]+)

        /x
        ', $pattern, $matches, \PREG_SET_ORDER);

        $tokens = [];

        foreach ($matches as $match) {
            
            if (!empty($match['class_U_explicit'])) {
                $token = [
                    'type' => 'class',
                    'class' => static::CLASS_UPPERCASE_UNAMBIGUOUS,
                    'repetition' => (int) $match['class_U_repetition']
                ];

                if ($expand) {
                    $token['haystack'] = 'ABCDEFGHKMNOPRSTUVWXYZ23456789';
                }

                $tokens[] = $token;
            } else if (!empty($match['class_U_implicit'])) {
                $token = [
                    'type' => 'class',
                    'class' => static::CLASS_UPPERCASE_UNAMBIGUOUS,
                    'repetition' => 1
                ];

                if ($expand) {
                    $token['haystack'] = 'ABCDEFGHKMNOPRSTUVWXYZ23456789';
                }

                $tokens[] = $token;
            } else if (!empty($match['range_token_explicit'])) {
                $token = [
                    'type' => 'range',
                    'token' => $match['range_token_explicit'],
                    'repetition' => (int) $match['range_repetition']
                ];

                if ($expand) {
                    $token['haystack'] = static::expandRange($match['range_token_explicit']);
                }

                $tokens[] = $token;
            } else if (!empty($match['range_token_implicit'])) {
                $token = [
                    'type' => 'range',
                    'token' => $match['range_token_implicit'],
                    'repetition' => 1
                ];

                if ($expand) {
                    $token['haystack'] = static::expandRange($match['range_token_implicit']);
                }

                $tokens[] = $token;
            } else if (!empty($match['literal_string'])) {
                $tokens[] = [
                    'type' => 'literal',
                    'string' => $match['literal_string']
                ];
            }
        }

        return $tokens;
    }

    /**
     * @param string $range_definition Set of characters defined individually, using range or both.
     * @return string All characters that fit in the range.
     */
    static public function expandRange ($range_definition) {
        $haystack = preg_replace_callback('/(?<from>.)\-(?<to>.)/', function ($e) {
            if (is_numeric($e['from']) || is_numeric($e['to'])) {
                $from = $e['from'];
                $to = $e['to'];

                if ($from > $to) {
                    throw new Exception\LogicException('Invalid range definition. Start greater than end.');
                }

                $haystack = '';

                for ($from; $from <= $to; $from++) {
                    $haystack .= $from;
                }
            } else {
                $from = ord($e['from']);
                $to = ord($e['to']);

                if ($from > $to) {
                    throw new Exception\LogicException('Invalid range definition. Start greater than end.');
                }

                $haystack = '';

                for ($from; $from <= $to; $from++) {
                    $haystack .= chr($from);
                }
            }

            return $haystack;
        }, $range_definition);

        // Remove overlaping characters.
        $haystack = implode('', array_unique(str_split($haystack)));

        return $haystack;
    }
}
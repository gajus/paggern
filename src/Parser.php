<?php
namespace Gajus\Parsley;

/**
 * @link https://github.com/gajus/parsley for the canonical source repository
 * @license https://github.com/gajus/parsley/blob/master/LICENSE BSD 3-Clause
 */
class Parser {
    private
        /**
         * @var Psr\Log\LoggerInterface
         */
        $logger;

    /**
     * @param string $pattern
     * @return array
     */
    public function tokenise ($subject) {
        preg_match_all('
/
    \[
    (?<range_explicit_token>[^]]+)
    \]
    \{
        (?<range_explicit_repetition>[0-9]+)
    \}
        |
    \[
    (?<range_implicit_token>[^]]+)
    \]
        |
    (?<literal_string>[^[]+)

/x
        ', $subject, $matches, \PREG_SET_ORDER);

        $tokens = [];

        foreach ($matches as $match) {
            if (!empty($match['literal_string'])) {
                $tokens[] = [
                    'type' => 'literal',
                    'string' => $match['literal_string']
                ];
            } else if (!empty($match['range_explicit_token'])) {
                $tokens[] = [
                    'type' => 'range',
                    'token' => $match['range_explicit_token'],
                    'repetition' => (int) $match['range_explicit_repetition']
                ];
            } else if (!empty($match['range_implicit_token'])) {
                $tokens[] = [
                    'type' => 'range',
                    'token' => $match['range_implicit_token'],
                    'repetition' => 1
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

        #die(var_dump($haystack));

        return $haystack;
    }
}
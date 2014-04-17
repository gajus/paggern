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
                    'type' => 'range 1',
                    'token' => $match['range_explicit_token'],
                    'repetition' => $match['range_explicit_repetition']
                ];
            } else if (!empty($match['range_implicit_token'])) {
                $tokens[] = [
                    'type' => 'range 2',
                    'token' => $match['range_implicit_token'],
                    'repetition' => 1
                ];
            }
        }

        return $tokens;
    }
}

/**/
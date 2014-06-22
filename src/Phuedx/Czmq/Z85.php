<?php

namespace Phuedx\Czmq;

class Z85
{
    private static $Z85_CHARACTER_MAP = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.-:+=^!/*?&<>()[]{}@%$#";
    private static $Z85_CHARACTER_MAP_FLIPPED = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 'a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35, 'A' => 36, 'B' => 37, 'C' => 38, 'D' => 39, 'E' => 40, 'F' => 41, 'G' => 42, 'H' => 43, 'I' => 44, 'J' => 45, 'K' => 46, 'L' => 47, 'M' => 48, 'N' => 49, 'O' => 50, 'P' => 51, 'Q' => 52, 'R' => 53, 'S' => 54, 'T' => 55, 'U' => 56, 'V' => 57, 'W' => 58, 'X' => 59, 'Y' => 60, 'Z' => 61, '.' => 62, '-' => 63, ':' => 64, '+' => 65, '=' => 66, '^' => 67, '!' => 68, '/' => 69, '*' => 70, '?' => 71, '&' => 72, '<' => 73, '>' => 74, '(' => 75, ')' => 76, '[' => 77, ']' => 78, '{' => 79, '}' => 80, '@' => 81, '%' => 82, '$' => 83, '#' => 84);

    public function decode($string)
    {
        $length = strlen($string);

        if ( ! $length) {
            return '';
        }

        if ($length % 5) {
            throw new \RuntimeException("The string frame isn't a multiple of five bytes long.");
        }

        $numArguments = $length / 5;
        $arguments = array_fill(0, $numArguments + 1, 0);

        // $length / 5 unsigned 32 bit ingegers in big-endian format
        $arguments[0] = 'N' . $numArguments;

        for ($i = 0, $j = 1; $i < $length; $i += 5, ++$j) {
            $arguments[$j] += self::$Z85_CHARACTER_MAP_FLIPPED[$string[$i    ]] * 52200625; // 85 ** 4
            $arguments[$j] += self::$Z85_CHARACTER_MAP_FLIPPED[$string[$i + 1]] * 614125; // 85 ** 3
            $arguments[$j] += self::$Z85_CHARACTER_MAP_FLIPPED[$string[$i + 2]] * 7225; // 85 ** 2
            $arguments[$j] += self::$Z85_CHARACTER_MAP_FLIPPED[$string[$i + 3]] * 85; // 85 ** 1
            $arguments[$j] += self::$Z85_CHARACTER_MAP_FLIPPED[$string[$i + 4]];
        }

        $result = call_user_func_array('pack', $arguments);

        return $result;
    }

    public function encode($binary)
    {
        $length = strlen($binary);

        if ( ! $length) {
            return '';
        }

        if ($length % 4) {
            throw new \RuntimeException("The binary frame isn't a multiple of four bytes long.");
        }

        $values = unpack('N' . $length / 4, $binary);
        $result = '';

        foreach ($values as $value) {
            $result .= self::$Z85_CHARACTER_MAP[($value / 52200625) % 85];
            $result .= self::$Z85_CHARACTER_MAP[($value / 614125)   % 85];
            $result .= self::$Z85_CHARACTER_MAP[($value / 7225)     % 85];
            $result .= self::$Z85_CHARACTER_MAP[($value / 85)       % 85];
            $result .= self::$Z85_CHARACTER_MAP[$value              % 85];
        }

        return $result;
    }
}

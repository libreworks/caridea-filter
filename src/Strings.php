<?php
declare(strict_types=1);
/**
 * Caridea
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
namespace Caridea\Filter;

/**
 * A loaded six-string on my back.
 */
class Strings
{
    /**
     * Coerces a value into a string or throws an exception.
     *
     * @param mixed $var - The value to coerce
     * @return string The converted string
     * @throws \InvalidArgumentException if the value could not be coerced
     */
    public static function coerce($var): string
    {
        if ($var === null || is_scalar($var) || is_callable([$var, '__toString'])) {
            return (string) $var;
        }
        throw new \InvalidArgumentException("Could not convert to string: " . gettype($var));
    }

    /**
     * Returns a filter that coerces a value to a string.
     *
     * @return callable The created filter
     */
    public static function toString(): callable
    {
        return [__CLASS__, 'coerce'];
    }

    /**
     * Returns a new lowercasing filter.
     *
     * @return \Closure The created filter
     */
    public static function lowerCase(): \Closure
    {
        return function ($value) {
            return mb_convert_case(Strings::coerce($value), MB_CASE_LOWER, 'UTF-8');
        };
    }

    /**
     * Returns a new uppercasing filter.
     *
     * @return \Closure The created filter
     */
    public static function upperCase(): \Closure
    {
        return function ($value) {
            return mb_convert_case(Strings::coerce($value), MB_CASE_UPPER, 'UTF-8');
        };
    }

    /**
     * Returns a new uppercasing filter.
     *
     * @return \Closure The created filter
     */
    public static function titleCase(): \Closure
    {
        return function ($value) {
            return mb_convert_case(Strings::coerce($value), MB_CASE_TITLE, 'UTF-8');
        };
    }

    /**
     * Returns a new whitespace trimming filter.
     *
     * @return \Closure The created filter
     */
    public static function trim(): \Closure
    {
        return function ($value) {
            return trim(Strings::coerce($value));
        };
    }

    /**
     * Returns a new substring filter.
     *
     * @param int $length The maximum string length
     * @return \Closure The created filter
     */
    public static function cut(int $length): \Closure
    {
        return function ($value) use ($length) {
            return substr(Strings::coerce($value), 0, $length);
        };
    }

    /**
     * Creates a new search and replace filter.
     *
     * @param string $search The value to find
     * @param string $replacement The value to use as a replacement
     * @return \Closure The created filter
     */
    public static function replace(string $search, string $replacement): \Closure
    {
        return function ($value) use ($search, $replacement) {
            return str_replace($search, $replacement, Strings::coerce($value));
        };
    }

    /**
     * Creates a new regular expression filter.
     *
     * @param string $pattern The search pattern
     * @param string $replacement The value to use as a replacement
     * @return \Closure The created filter
     */
    public static function regex(string $pattern, string $replacement): \Closure
    {
        return function ($value) use ($pattern, $replacement) {
            return preg_replace($pattern, $replacement, Strings::coerce($value));
        };
    }

    /**
     * Creates a new filter that removes non-alphanumeric characters.
     *
     * @return \Closure The created filter
     */
    public static function alnum(): \Closure
    {
        return self::regex('/[^\p{L}\p{Nd}]/u', '');
    }

    /**
     * Creates a new filter that removes non-alphabetic characters.
     *
     * @return \Closure The created filter
     */
    public static function alpha(): \Closure
    {
        return self::regex('/[^\p{L}]/u', '');
    }

    /**
     * Creates a new filter that removes non-digit characters.
     *
     * @return \Closure The created filter
     */
    public static function numeric(): \Closure
    {
        return self::regex('/[^\p{N}]/u', '');
    }

    /**
     * Creates a new filter that turns any newlines into UNIX-style newlines.
     *
     * @return \Closure The created filter
     */
    public static function unixNewlines(): \Closure
    {
        return self::regex("/\R/", "\n");
    }

    /**
     * Creates a new filter that turns multiple newlines into two.
     *
     * Meant to be run after `unixNewlines`.
     *
     * @return \Closure The created filter
     */
    public static function compactNewlines(): \Closure
    {
        return self::regex("/\n\n+/", "\n\n");
    }
}

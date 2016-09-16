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
 * Go get her, Ray!
 *
 * This class contains some array-related filters.
 */
class Arrays
{
    /**
     * Coerces a value into an array or throws an exception.
     *
     * @param mixed $var - The value to coerce
     * @return array The converted array
     * @throws \InvalidArgumentException if the value could not be coerced
     */
    public static function coerce($var): array
    {
        if (is_array($var)) {
            return $var;
        } elseif ($var instanceof \Traversable) {
            return iterator_to_array($var, true);
        } elseif ($var === null || is_scalar($var)) {
            return (array) $var;
        }
        throw new \InvalidArgumentException("Could not convert to array: " . gettype($var));
    }

    /**
     * Creates a new regular expression split filter.
     *
     * @param string $pattern The search pattern
     * @return \Closure The created filter
     */
    public static function split(string $pattern): \Closure
    {
        return function ($value) use ($pattern) {
            return preg_split($pattern, Strings::coerce($value));
        };
    }

    /**
     * Creates a new explode filter.
     *
     * @param string $needle The search string
     * @return \Closure The created filter
     */
    public static function explode(string $needle): \Closure
    {
        return function ($value) use ($needle) {
            return explode($needle, Strings::coerce($value));
        };
    }

    /**
     * Creates a new array slice filter.
     *
     * @param int $length The maximum array length
     * @return \Closure The created filter
     */
    public static function slice(int $length): \Closure
    {
        return function ($value) use ($length) {
            return array_slice(Arrays::coerce($value), 0, $length);
        };
    }

    /**
     * Creates a new implode filter.
     *
     * @param string $joiner The glue string
     * @return \Closure The created filter
     */
    public static function join(string $joiner): \Closure
    {
        return function ($value) use ($joiner) {
            return implode($joiner, Arrays::coerce($value));
        };
    }
}

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
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
namespace Caridea\Filter;

/**
 * Turn that mush into muscle!
 */
class Casts
{
    private static $truthy = ['yes', 'y', 'on', '1', 'true', 't'];

    /**
     * Creates a filter that casts values to booleans.
     *
     * @return \Closure The created filter
     */
    public static function toBoolean(): \Closure
    {
        return function ($value) {
            return is_bool($value) ? $value :
                (is_scalar($value) ?
                    in_array(strtolower(trim(Strings::coerce($value))), Casts::$truthy, true)
                    : false);
        };
    }

    /**
     * Creates a filter that casts values to integers.
     *
     * @return \Closure The created filter
     */
    public static function toInteger(): \Closure
    {
        return function ($value) {
            return (int)(is_scalar($value) ? $value : Strings::coerce($value));
        };
    }

    /**
     * Creates a filter that casts values to floats.
     *
     * @return \Closure The created filter
     */
    public static function toFloat(): \Closure
    {
        return function ($value) {
            return (float)(is_scalar($value) ? $value : Strings::coerce($value));
        };
    }

    /**
     * Creates a filter that casts values to arrays.
     *
     * @return callable The created filter
     */
    public static function toArray(): callable
    {
        return [Arrays::class, 'coerce'];
    }

    /**
     * Creates a filter that casts values to a default if empty.
     *
     * @return \Closure The created filter
     */
    public static function toDefault($default = null): \Closure
    {
        return function ($value) use ($default) {
            return $value === null || $value === '' ? $default : $value;
        };
    }
}

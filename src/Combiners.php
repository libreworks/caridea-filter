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
 * Come Together Right Now.
 *
 * This class provides some Reducers.
 */
class Combiners
{
    /**
     * A combiner that takes any field with a given prefix and adds it to a List
     *
     * For example, `value1`, `value2`, and `value3` can be combined into an
     * outgoing field `value` with an array that contains 3 entries.
     *
     * @param string $destination The outgoing field name
     * @param string $prefix The prefix to find
     * @return \Caridea\Filter\Reducer The created filter
     */
    public static function appender(string $destination, string $prefix): Reducer
    {
        return new class($destination, $prefix) implements Reducer {
            public function __construct($destination, $prefix)
            {
                $this->destination = $destination;
                $this->prefix = $prefix;
            }
            public function __invoke(array $input): array
            {
                $subl = strlen($this->prefix);
                $out = array_filter($input, function ($k) use ($subl) {
                    return substr($k, 0, $subl) === $this->prefix;
                }, ARRAY_FILTER_USE_KEY);
                return $out ? [($this->destination) => array_values($out)] : [];
            }
        };
    }

    /**
     * A combiner that combines prefixed fields into a Map.
     *
     * For example, `address-street` and `address-city` can be combined into an
     * outgoing field `address` with `street` and `city` keys.
     *
     * @param string $destination The outgoing field name
     * @param string $prefix The prefix to find
     * @return \Caridea\Filter\Reducer The created filter
     */
    public static function prefixed(string $destination, string $prefix): Reducer
    {
        return new class($destination, $prefix) implements Reducer {
            public function __construct($destination, $prefix)
            {
                $this->destination = $destination;
                $this->prefix = $prefix;
            }
            public function __invoke(array $input): array
            {
                $subl = strlen($this->prefix);
                $out = [];
                foreach ($input as $k => $v) {
                    if (substr($k, 0, $subl) === $this->prefix) {
                        $out[substr($k, $subl)] = $v;
                    }
                }
                return $out ? [($this->destination) => $out] : [];
            }
        };
    }

    /**
     * Creates a combiner that combines datetime values.
     *
     * @param string $date The field to find date (e.g. `2016-09-15`)
     * @param string $time The field to find time (e.g. `T12:04:06`)
     * @param string $timezone The field to find timezone name (e.g. `America/New_York`)
     * @return \Caridea\Filter\Reducer The created filter
     */
    public static function datetime(string $destination, string $date, string $time, string $timezone = null): Reducer
    {
        return new class($destination, $date, $time, $timezone) implements Reducer {
            public function __construct($destination, $date, $time, $timezone = null)
            {
                $this->destination = $destination;
                $this->dfield = $date;
                $this->tfield = $time;
                $this->zfield = $timezone;
            }
            public function __invoke(array $input): array
            {
                $date = $input[$this->dfield] ?? '';
                $time = $input[$this->tfield] ?? '';
                $zone = $this->zfield === null ? null : ($input[$this->zfield] ?? null);
                return $date || $time ? [
                    ($this->destination) => new \DateTime(
                        "{$date}{$time}",
                        !$this->zfield ? null : new \DateTimeZone($zone)
                    )
                ] : [];
            }
        };
    }
}

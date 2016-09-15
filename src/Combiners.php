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
 * Combines fields into one
 */
class Combiners implements Multi
{
    /**
     * @var string - The operation
     */
    private $op;
    /**
     * @var string - The outbound key
     */
    private $destination;
    /**
     * @var string[] - The field names
     */
    private $fields = [];

    /**
     * Creates a new Combiners.
     */
    protected function __construct(string $op, string $destination, array $fields)
    {
        $this->op = $op;
        $this->destination = $destination;
        $this->fields = $fields;
    }

    /**
     * Combines the values.
     *
     * @param array $input The input values
     * @return array The returned values
     */
    public function __invoke(array $input): array
    {
        switch ($op) {
            case "datetime":
                list($dfield, $tfield, $zfield) = array_pad($this->fields, 3, null);
                $date = $input[$dfield] ?? '';
                $time = $input[$tfield] ?? '';
                $zone = $zfield === null ? null : ($input[$zfield] ?? null);
                return [
                    ($this->destination) => new \DateTime("{$date}T{$time}",
                        !$zfield ? null : new \DateTimeZone($zone))
                ];
            case "prefixed":
                $prefix = $this->fields[0];
                $subl = strlen($prefix);
                $out = [];
                foreach ($input as $k => $v) {
                    if (substr($k, 0, $subl) === $prefix) {
                        $out[substr($k, $subl)] = $v;
                    }
                }
                return [($this->destination) => $out];
            case "appender":
                $prefix = $this->fields[0];
                $subl = strlen($prefix);
                $out = array_filter($input, function ($k) use ($subl, $prefix) {
                    return substr($k, 0, $subl) === $prefix;
                }, ARRAY_FILTER_USE_KEY);
                return [($this->destination) => $out];
        }
    }

    /**
     * A combiner that takes any field with a given prefix and adds it to a List
     *
     * For example, `value1`, `value2`, and `value3` can be combined into an
     * outgoing field `value` with an array that contains 3 entries.
     *
     * @param string $destination The outgoing field name
     * @param string $prefix The prefix to find
     * @return Combiners The created filter
     */
    public static function appender(string $destination, string $prefix): Combiners
    {
        return new self('appender', $destination, [$prefix]);
    }

    /**
     * A combiner that combines prefixed fields into a Map.
     *
     * For example, `address-street` and `address-city` can be combined into an
     * outgoing field `address` with `street` and `city` keys.
     *
     * @param string $destination The outgoing field name
     * @param string $prefix The prefix to find
     * @return Combiners The created filter
     */
    public static function prefixed(string $destination, string $prefix): Combiners
    {
        return new self('prefixed', $destination, [$prefix]);
    }

    /**
     * Creates a combiner that combines datetime values.
     *
     * @param string $date The field to find date (e.g. `2016-09-15`)
     * @param string $time The field to find time (e.g. `12:04:06`)
     * @param string $timezone The field to find timezone name (e.g. `America/New_York`)
     * @return Combiners The created filter
     */
    public static function datetime(string $destination, string $date, string $time, string $timezone = null): Combiners
    {
        return new self('datetime', $destination, [$date, $time, $timezone]);
    }
}

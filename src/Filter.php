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
 * A set of filter chains for fields on an array.
 */
class Filter
{
    /**
     * @var array<string,Chain> - The filters keyed by field
     */
    protected $chains = [];

    /**
     * Creates a new Filter (but you're probably better off using `Builder`)
     *
     * @param array<string,Chain> $chains - The filters keyed by field
     */
    public function __construct(array $chains)
    {
        foreach ($chains as $k => $f) {
            if (!($f instanceof Filters)) {
                throw new \InvalidArgumentException("Must be an instance of Filters");
            }
            $this->chains[$k] = $f;
        }
    }

    /**
     * Runs the array filter.
     *
     * @param array<string,mixed> $values The values to filter
     * @return array The filtered array
     */
    public function __invoke(array $values): array
    {
        $out = [];
        foreach ($this->chains as $field => $chain) {
            if (array_key_exists($field, $values) || $chain->isRequired()) {
                $out[$field] = $chain($values[$field] ?? null);
            }
        }
        return $out;
    }
}

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
 * That's why I say hey man, nice shot.
 *
 * This class contains several chains of functions which sanitize values.
 */
class Filter implements Reducer
{
    /**
     * @var array<string,\Caridea\Filter\Chain> - The filters keyed by field
     */
    protected $chains = [];
    /**
     * @var array<\Caridea\Filter\Reducer> - The reducers
     */
    protected $reducers = [];
    /**
     * @var \Caridea\Filter\Chain - The chain to run if field is unregistered
     */
    protected $otherwise;

    /**
     * Creates a new Filter (but you're probably better off using `Builder`).
     *
     * Any `Chain`s supplied to this method will be cloned. Modifications to the
     * originals will not appear once a `Filter` is constructed.
     *
     * @param array<string,\Caridea\Filter\Chain> $chains - The filters keyed by field
     * @param array<\Caridea\Filter\Reducer> $reducers - Any Reducer filters to include
     * @param \Caridea\Filter\Chain $otherwise - The chain to run for missing fields
     */
    public function __construct(array $chains, array $reducers = [], Chain $otherwise = null)
    {
        foreach ($chains as $k => $f) {
            if (!($f instanceof Chain)) {
                throw new \InvalidArgumentException("Must be an instance of Chain");
            }
            $this->chains[$k] = clone $f;
        }
        foreach ($reducers as $k => $f) {
            if (!($f instanceof Reducer)) {
                throw new \InvalidArgumentException("Must be an instance of Reducer");
            }
            $this->reducers[$k] = $f;
        }
        $this->otherwise = $otherwise;
    }

    /**
     * Runs the array filter.
     *
     * Chains are run in the order in which they were inserted. Reducers are run
     * afterward and operate on the filtered values. Each reducer is run
     * sequentially and operates on the values returned from the previous.
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
        if ($this->otherwise !== null) {
            $f = $this->otherwise;
            foreach (array_diff_key($values, $this->chains) as $k => $v) {
                $out[$k] = $f($v);
            }
        }
        foreach ($this->reducers as $multi) {
            $out = $multi($out);
        }
        return $out;
    }
}

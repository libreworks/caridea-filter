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
 * Creates a Filters object.
 */
class Builder
{
    /**
     * @var Registry - The filter registry
     */
    protected $registry;
    /**
     * @var array<string,Chain> - The chains by field name
     */
    protected $chains = [];

    /**
     * Creates a new Builder.
     *
     * @param Registry $registry The filter registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Creates a new Chain for a field
     *
     * @param string $name The field name
     * @return Chain the chain
     */
    public function field(string $name): Chain
    {
        $f = new Chain($this->registry);
        $this->chains[$name] = $f;
        return $f;
    }

    /**
     * Creates a new *required* Chain for a field.
     *
     * @param string $name The field name
     * @return Chain the chain
     */
    public function always(string $name): Chain
    {
        $f = new Chain($this->registry, true);
        $this->chains[$name] = $f;
        return $f;
    }

    /**
     * Builds a new Filter.
     *
     * @return Filter the created Filter.
     */
    public function build(): Filter
    {
        return new Filter(array_filter($this->chains, function ($v) {
            return !$v->isEmpty();
        }));
    }
}

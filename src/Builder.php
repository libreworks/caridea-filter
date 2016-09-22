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
 * We Built this Filter on Rock and Roll.
 */
class Builder
{
    /**
     * @var \Caridea\Filter\Registry - The filter registry
     */
    protected $registry;
    /**
     * @var array<string,\Caridea\Filter\Chain> - The chains by field name
     */
    protected $chains = [];
    /**
     *
     * @var \Caridea\Filter\Reducer[]
     */
    protected $reducers = [];
    /**
     * @var \Caridea\Filter\Chain|null
     */
    protected $otherwiseChain;

    /**
     * Creates a new Builder.
     *
     * @param \Caridea\Filter\Registry $registry The filter registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Creates a new Chain for a field
     *
     * @param string $name The field name
     * @return \Caridea\Filter\Chain the chain
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
     * @return \Caridea\Filter\Chain the chain
     */
    public function always(string $name): Chain
    {
        $f = new Chain($this->registry, true);
        $this->chains[$name] = $f;
        return $f;
    }

    /**
     * Adds a multiple filter to the builder.
     *
     * @param \Caridea\Filter\Reducer $multi
     * @return self provides a fluent interface
     */
    public function reducer(Reducer $multi): self
    {
        $this->reducers[] = $multi;
        return $this;
    }

    /**
     * Adds a chain that runs for any non-mentioned fields.
     *
     * @param string $name The filter name
     * @param mixed $args The filter arguments
     * @return \Caridea\Filter\Chain the chain
     */
    public function otherwise(string $name, ...$args): Chain
    {
        $chain = new Chain($this->registry, false);
        $this->otherwiseChain = $chain;
        return $chain->then($name, ...$args);
    }

    /**
     * Builds a new Filter.
     *
     * @return \Caridea\Filter\Filter the created Filter.
     */
    public function build(): Filter
    {
        return new Filter($this->chains, $this->reducers, $this->otherwiseChain);
    }
}

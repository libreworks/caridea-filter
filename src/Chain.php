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
 * Unchained, yeah you hit the ground running.
 *
 * This class holds several callable objects and invokes them in insert order.
 */
class Chain implements \IteratorAggregate, \Countable
{
    /**
     * @var Registry - The builder containing definitions
     */
    protected $registry;
    /**
     * @var callable[] - The filters to apply
     */
    protected $filters = [];
    /**
     * @var bool - Whether to run this filter regardless of value existence
     */
    protected $required;

    /**
     * Creates a new Chain
     *
     * @param \Caridea\Filter\Registry $registry The builder
     * @param bool $required Whether these filters run even if value is missing
     */
    public function __construct(Registry $registry, bool $required = false)
    {
        $this->registry = $registry;
        $this->required = $required;
    }

    /**
     * Run all of the filters.
     *
     * @param mixed $value The value to filter
     * @return mixed The sanitized value
     */
    public function __invoke($value)
    {
        $result = $value;
        foreach ($this->filters as $f) {
            $result = $f($result);
        }
        return $result;
    }

    /**
     * Whether this set is empty.
     *
     * @return bool `true` if this set is empty
     */
    public function isEmpty(): bool
    {
        return !$this->filters;
    }

    /**
     * Whether this set is required to run.
     *
     * @return bool `true` if this set is required
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Gets the count.
     *
     * @return int The count
     */
    public function count(): int
    {
        return count($this->filters);
    }

    /**
     * Gets the iterator.
     *
     * @return \Traversable The iterator
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->filters);
    }

    /**
     * Returns this chain with the defined filter appended.
     *
     * @param string $name The filter name
     * @param mixed $args Any remaining arguments for the filter
     * @return self provides a fluent interface
     */
    public function then(string $name, ...$args): self
    {
        $this->filters[] = $this->registry->factory($name, $args);
        return $this;
    }

    /**
     * Returns this chain with the defined *each* filter appended.
     *
     * If the filter receives an array, it will run for every entry, and if it
     * receives anything else, it will be run once.
     *
     * @param string $name The filter name
     * @param mixed $args Any remaining arguments for the filter
     * @return self provides a fluent interface
     */
    public function each(string $name, ...$args): self
    {
        $f = $this->registry->factory($name, $args);
        $this->filters[] = function ($input) use ($f) {
            return is_array($input) ? array_map($f, $input) : $f($input);
        };
        return $this;
    }
}

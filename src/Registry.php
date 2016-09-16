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
 * A filter registry.
 */
class Registry
{
    /**
     * @var array<string,callable> Associative array of definition name to function callback
     */
    protected $definitions = [];

    /**
     * @var array<string,callable> Associative array of definition name to function callback
     */
    private static $defaultDefinitions = [
        'trim' => ['Caridea\Filter\Strings', 'trim'],
        'lowercase' => ['Caridea\Filter\Strings', 'lowerCase'],
        'uppercase' => ['Caridea\Filter\Strings', 'upperCase'],
        'titlecase' => ['Caridea\Filter\Strings', 'titleCase'],
        'string' => ['Caridea\Filter\Strings', 'toString'],
        'replace' => ['Caridea\Filter\Strings', 'replace'],
        'regex' => ['Caridea\Filter\Strings', 'regex'],
        'alnum' => ['Caridea\Filter\Strings', 'alnum'],
        'alpha' => ['Caridea\Filter\Strings', 'alpha'],
        'numeric' => ['Caridea\Filter\Strings', 'numeric'],
        'nl' => ['Caridea\Filter\Strings', 'unixNewlines'],
        'compactnl' => ['Caridea\Filter\Strings', 'compactNewlines'],
        'bool' => ['Caridea\Filter\Casts', 'toBoolean'],
        'int' => ['Caridea\Filter\Casts', 'toInteger'],
        'float' => ['Caridea\Filter\Casts', 'toFloat'],
        'array' => ['Caridea\Filter\Casts', 'toArray'],
        'default' => ['Caridea\Filter\Casts', 'toDefault'],
    ];

    /**
     * Creates a new filter Builder.
     */
    public function __construct()
    {
        $this->definitions = array_merge([], self::$defaultDefinitions);
    }

    /**
     * Registers rule definitions.
     *
     * ```php
     * $builder = new \Caridea\Filter\Builder();
     * $builder->register([
     *     'gzip' => ['My\Gzip', 'compressor'],
     *     'password' => function($hash){return new Hasher($hash);},
     *     'something' => 'my_function_that_returns_a_closure'
     * ]);
     * ```
     *
     * @param array<string,callable> $definitions Associative array of definition name to function callback
     * @return $this provides a fluent interface
     */
    public function register(array $definitions): self
    {
        foreach ($definitions as $name => $callback) {
            if (!is_callable($callback)) {
                throw new \InvalidArgumentException('Values passed to register must be callable');
            }
            $this->definitions[$name] = $callback;
        }
        return $this;
    }

    /**
     * Constructs a filter.
     *
     * @param string $name The name of the filter
     * @param array $args Any filter arguments
     * @return callable The filter
     * @throws \InvalidArgumentException if the filter name is not registered
     * @throws \UnexpectedValueException if the factory returns a non-callable value
     */
    public function factory(string $name, array $args): callable
    {
        if (!array_key_exists($name, $this->definitions)) {
            throw new \InvalidArgumentException("No filter registered with name: $name");
        }
        $factory = $this->definitions[$name];
        $filter = $factory(...$args);
        if (!is_callable($filter)) {
            throw new \UnexpectedValueException('Definitions must return callable');
        }
        return $filter;
    }

    /**
     * Creates a new Builder using this Repository.
     *
     * @return \Caridea\Filter\Builder The builder
     */
    public function builder(): Builder
    {
        return new Builder($this);
    }
}

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
 * Cherry-picks values that aren't in a list.
 */
class Otherwise implements Reducer
{
    /**
     * @var \Caridea\Filter\Chain
     */
    protected $chain;
    /**
     * @var array<string,mixed>
     */
    protected $ignores;

    /**
     * Creates a new Otherwise.
     *
     * @param \Caridea\Filter\Chain $chain The filter chain to apply
     * @param array<string,mixed> $ignores Array keys should be fields to skip
     */
    public function __construct(Chain $chain, array $ignores)
    {
        $this->chain = $chain;
        $this->ignores = $ignores;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(array $input): array
    {
        $out = [];
        $f = $this->chain;
        foreach (array_diff_key($input, $this->ignores) as $k => $v) {
            $out[$k] = $f($v);
        }
        return $out;
    }
}

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
 * Turn that mush into muscle!
 */
class Casts implements Filter
{
    /**
     * @var int - The operator
     */
    private $op;

    private static $truthy = ['yes', 'y', 'on', '1', 'true', 't'];

    /**
     * Creates a new Casts filter.
     *
     * @param int $op The operator
     */
    protected function __construct(int $op)
    {
        $this->op = $op;
    }

    /**
     * {@inheritDoc}
     * @return mixed The sanitized input
     */
    public function __invoke($input)
    {
        switch ($this->op) {
            case T_BOOL_CAST:
                return is_bool($input) ? $input :
                    (is_scalar($input) ? in_array(trim((string) $input), self::$truthy, true) : false);
            case T_INT_CAST:
                return (int) $input;
            case T_DOUBLE_CAST:
                return (float) $input;
            case T_STRING_CAST:
                return (string) $input;
            default:
                throw new \UnexpectedValueException("I should not have been constructed with an incorrect operator");
        }
    }

    /**
     * Creates a filter that casts values to booleans.
     *
     * @return \Caridea\Filter\Casts The created filter
     */
    public static function booleans(): Casts
    {
        return new self(T_BOOL_CAST);
    }

    /**
     * Creates a filter that casts values to integers.
     *
     * @return \Caridea\Filter\Casts The created filter
     */
    public static function integers(): Casts
    {
        return new self(T_INT_CAST);
    }

    /**
     * Creates a filter that casts values to floats.
     *
     * @return \Caridea\Filter\Casts The created filter
     */
    public static function floats(): Casts
    {
        return new self(T_DOUBLE_CAST);
    }

    /**
     * Creates a filter that casts values to strings.
     *
     * @return \Caridea\Filter\Casts The created filter
     */
    public static function strings(): Casts
    {
        return new self(T_STRING_CAST);
    }
}

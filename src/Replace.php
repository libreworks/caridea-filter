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
 * Searchin'… seek and destroy!
 */
class Replace implements Filter
{
    /**
     * @var bool - Whether this Replace is for a regular expression
     */
    private $preg;
    /**
     * @var string - The value to search
     */
    private $search;
    /**
     * @var string - The value to replace
     */
    private $replace;

    /**
     *
     * @param bool $preg - Whether this Replace is for a regular expression
     * @param string $search - The value to find
     * @param string $replace - The value to replace
     */
    protected function __construct(bool $preg, string $search, string $replace)
    {
        $this->preg = $preg;
        $this->search = $search;
        $this->replace = $replace;
    }

    /**
     * {@inheritDoc}
     * All input values will be cast to a string.
     *
     * @return string The replaced value
     */
    public function __invoke($input)
    {
        $subject = (string) $input;
        return $this->preg ?
            preg_replace($this->search, $this->replace, $subject) :
            str_replace($this->search, $this->replace, $subject);
    }

    /**
     * Creates a new search and replace filter.
     *
     * @param string $search The value to find
     * @param string $replacement The value to use as a replacement
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function fixed(string $search, string $replacement): Replace
    {
        return new self(false, $search, $replacement);
    }

    /**
     * Creates a new regular expression filter.
     *
     * @param string $pattern The search pattern
     * @param string $replacement The value to use as a replacement
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function regex(string $pattern, string $replacement): Replace
    {
        return new self(true, $pattern, $replacement);
    }

    /**
     * Creates a new filter that removes non-alphanumeric characters.
     *
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function alnum(): Replace
    {
        return new self(true, '/[^\p{L}\p{Nd}]/u', '');
    }

    /**
     * Creates a new filter that removes non-alphabetic characters.
     *
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function alpha(): Replace
    {
        return new self(true, '/[^\p{L}]/u', '');
    }

    /**
     * Creates a new filter that removes non-digit characters.
     *
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function numeric(): Replace
    {
        return new self(true, '/[^\p{N}]/u', '');
    }

    /**
     * Creates a new filter that turns any newlines into UNIX-style newlines.
     *
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function unixNewlines(): Replace
    {
        return new self(true, "/\R/", "\n");
    }

    /**
     * Creates a new filter that turns multiple newlines into two.
     *
     * Meant to be run after `unixNewlines`.
     *
     * @return \Caridea\Filter\Replace The created filter
     */
    public static function compactNewlines(): Replace
    {
        return new self(true, "/\n+/", "\n\n");
    }
}

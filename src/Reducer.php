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
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
namespace Caridea\Filter;

/**
 * Values check in but they don't check out!
 *
 * Reducers are meant to alter multiple fields. While the name infers that array
 * fields are reduced, a Reducer *could* in fact add additional fields.
 *
 * The real purpose is to squash several values into a different type. For
 * example:
 *
 * - A datetime filter may take the date value from one key and the time value
 *   from another
 * - An address object could take several values out of the input and return a
 *   single address
 * - Maybe you have a phone number in several fields as country code, area code,
 *   local, extension
 */
interface Reducer
{
    /**
     * Sanitizes multiple input values, potentially combining/reducing fields.
     *
     * When implementing this class, make sure to include any untouched fields
     * in the output array.
     *
     * @param array<string,mixed> $input The input values to sanitize
     * @return array<string,mixed> The sanitized values
     */
    public function __invoke(array $input): array;
}

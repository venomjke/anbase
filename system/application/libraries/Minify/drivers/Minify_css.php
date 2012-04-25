<?php
/**
 * @name		CodeIgniter Minify
 * @author		Jens Segers
 * @link		http://www.jenssegers.be
 * @license		MIT License Copyright (c) 2012 Jens Segers
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

class Minify_css extends Minify_Driver {
    
    /* (non-PHPdoc)
     * @see Minify_Driver::minify()
     */
    public function min($source) {
        return trim($this->_optimize($source));
    }
    
	/**
	 * Optimize
	 * Optimize the contents of a css file
	 * based on Drupal 7 CSS Core aggregator
	 *
	 * @param string $contents
	 * @return string
	 */
	private function _optimize($contents)
	{
		// Perform some safe CSS optimizations.
		// Regexp to match comment blocks.
		$comment     = '/\*[^*]*\*+(?:[^/*][^*]*\*+)*/';
		// Regexp to match double quoted strings.
		$double_quot = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';
		// Regexp to match single quoted strings.
		$single_quot = "'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'";
		// Strip all comment blocks, but keep double/single quoted strings.
		$contents = preg_replace(
			"<($double_quot|$single_quot)|$comment>Ss",
			"$1",
			$contents
		);
		// Remove certain whitespace.
		// There are different conditions for removing leading and trailing
		// whitespace.
		// @see http://php.net/manual/en/regexp.reference.subpatterns.php
		$contents = preg_replace_callback(
			'<' .
			# Strip leading and trailing whitespace.
			'\s*([@{};,])\s*' .
			# Strip only leading whitespace from:
			# - Closing parenthesis: Retain "@media (bar) and foo".
			'| \s+([\)])' .
			# Strip only trailing whitespace from:
			# - Opening parenthesis: Retain "@media (bar) and foo".
			# - Colon: Retain :pseudo-selectors.
			'| ([\(:])\s+' .
			'>xS',
			array(get_class($this), '_optimize_call_back'),
			$contents
		);

		return $contents;
	}

	// ------------------------------------------------------------------------

	/**
	 * Optimize CB
	 * Optimize Callback Helper companion for optimize fn
	 * based on Drupal 7 CSS Core aggregator
	 *
	 * @param string $matches
	 * @return array
	 */
	private function _optimize_call_back($matches)
	{
		// Discard the full match.
		unset($matches[0]);

		// Use the non-empty match.
		return current(array_filter($matches));
	}
    
}
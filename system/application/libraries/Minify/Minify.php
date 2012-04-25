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

class Minify extends CI_Driver_Library {
    
    private $ci;
    
    // allowed drivers, for custom drivers: add to this array
    protected $valid_drivers = array('Minify_js', 'Minify_css');
    
    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->helper('file');
    }
    
    /**
     * Minify a file, the minified content is returned
     * 
     * @param string source
     * @return string minifed
     */
    public function min() {
        $params = func_get_args();
        $resources = array_shift($params);
        
        // uniform
        if (!is_array($resources)) {
            $resources = array($resources);
        }
        
        $minified = "";
        foreach ($resources as $resource) {
            // determine extension in order to select the correct driver
            $path_info = pathinfo($resource);
            $driver = $path_info['extension'];
            
            // get source code
            $source = @read_file($resource);
            if ($source === FALSE) {
                show_error('File does not exist: ' . $resource);
            }
            
            // add source to params
            array_unshift($params, $source);
            
            // execute driver
            $minified .= call_user_func_array(array($this->$driver, 'min'), $params) . "\n";
            
            // remove source again for next iteration
            array_shift($params);
        }
        
        return $minified;
    }
}

abstract class Minify_Driver extends CI_Driver {
    
    /**
     * Driver specific minify function
     * 
     * @param string $resource
     */
    abstract public function min($resource);

}
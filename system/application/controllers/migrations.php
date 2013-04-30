<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
*
* @author  - Alex.strigin <apstrigin@gmail.com>
* @company - Flyweb
*
*/
class Migrations extends MX_Controller {


  public function __construct(){
    parent::__construct();
  }

  public function current()
  {
    $this->load->library('migration');
    if( ! $this->migration->current()){
      show_error($this->migration->error_string());
    }
  }

}

/* End of file Site.php */
/* Location: ./application/controllers/site.php */
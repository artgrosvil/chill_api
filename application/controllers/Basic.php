<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic extends CI_Controller
{
	function index()
	{
		$this->load->view('basic/main');
	}
}

/* End of file Basic.php */
/* Location: ./application/controllers/api/Basic.php */
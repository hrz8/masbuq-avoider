<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masjid extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Masjid_model', 'masjid');
    }
	
	public function index()
	{
		$url = base_url() . 'api/masjid';
		$jsondata = file_get_contents($url);
		$arr = (array) json_decode($jsondata, TRUE);
		$data['masjids'] = $arr['data'];
		$data['h1'] = "All Masjid";

		$head['active'] = 'all';
		
		$this->load->view('_head', $head);
		$this->load->view('masjid_home', $data);
	}

	public function nearby()
	{
		$head['active'] = 'nearby';
		
		$this->load->view('_head', $head);
		$this->load->view('masjid_nearby');
	}

	public function iqamah($id)
	{
		$url = base_url() . 'api/masjid?id=' . $id;
		$jsondata = file_get_contents($url);
		$arr = (array) json_decode($jsondata, TRUE);
		$data['masjid'] = $arr['data'][0];

		$head['active'] = '';
		
		$this->load->view('_head', $head);
		$this->load->view('masjid_detail', $data);
	}

	public function search() {
		if (isset($_GET['query']) && ($_GET['query'] != '')) {
			$query = $_GET['query'];
			$data['masjids'] = $this->masjid->search($query);
			$data['h1'] = "Result(s) for: " . $query;

			$head['active'] = '';
		
			$this->load->view('_head', $head);
			$this->load->view('masjid_home', $data);
		}
		else {
			$this->index();
		}
	}
}

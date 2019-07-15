<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Masjid extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Masjid_model', 'masjid');
    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === NULL)
        {
            $masjid = $this->masjid->get();
        }
        else
        {
            $masjid = $this->masjid->get($id);

            if ($masjid) {
                    $kode_kota = $masjid[0]['kode_kota'];
                $timezone = $masjid[0]['timezone'];

                date_default_timezone_set($timezone);
                $tanggal = date('Y-m-d', time());
                $jam = date('H', time());
                $menit = date('i', time());
                $detik = date('s', time());
                // $jam = '12';
                // $menit = '10';
                // $detik = '33';
                $jammenit = $jam.':'.$menit;
                $jammenitdetik = $jammenit.':'.$detik;

                $url = "https://api.banghasan.com/sholat/format/json/jadwal/kota/$kode_kota/tanggal/$tanggal";
                
                $jsondata = file_get_contents($url);
                $data = (array) json_decode($jsondata, TRUE);
                
                $masjid[0]['masuk_waktu'] = 'BELUM';
                $masjid[0]['azan_selanjutnya'] = '';
                $masjid[0]['waktu_selanjutnya'] = '';
                $masjid[0]['timenow'] = $jammenitdetik;
                $masjid[0]['subuh'] = $data['jadwal']['data']['subuh'];
                $masjid[0]['dzuhur'] = $data['jadwal']['data']['dzuhur'];
                // $masjid[0]['dzuhur'] = "12:50";
                $masjid[0]['ashar'] = $data['jadwal']['data']['ashar'];
                $masjid[0]['maghrib'] = $data['jadwal']['data']['maghrib'];
                $masjid[0]['isya'] = $data['jadwal']['data']['isya'];

                if (strtotime($masjid[0]['timenow']) < strtotime($masjid[0]['subuh']) || strtotime($masjid[0]['timenow']) > strtotime( $masjid[0]['isya'])) {
                    $masjid[0]['azan_selanjutnya'] = 'SUBUH';
                    $masjid[0]['waktu_selanjutnya'] = $masjid[0]['subuh'];
                }
                elseif (strtotime($masjid[0]['timenow']) < strtotime($masjid[0]['dzuhur'])) {
                    $masjid[0]['azan_selanjutnya'] = 'DZUHUR';
                    $masjid[0]['waktu_selanjutnya'] = $masjid[0]['dzuhur'];
                }
                elseif (strtotime($masjid[0]['timenow']) < strtotime($masjid[0]['ashar'])) {
                $masjid[0]['azan_selanjutnya'] = 'ASHAR';
                $masjid[0]['waktu_selanjutnya'] = $masjid[0]['ashar'];
                }
                elseif (strtotime($masjid[0]['timenow']) < strtotime($masjid[0]['maghrib'])) {
                    $masjid[0]['azan_selanjutnya'] = 'MAGHRIB';
                    $masjid[0]['waktu_selanjutnya'] = $masjid[0]['maghrib'];
                }
                elseif (strtotime($masjid[0]['timenow']) < strtotime($masjid[0]['isya'])) {
                    $masjid[0]['azan_selanjutnya'] = 'ISYA';
                    $masjid[0]['waktu_selanjutnya'] = $masjid[0]['isya'];
                }

                if ($jammenit == $masjid[0]['subuh']) {
                    $masjid[0]['masuk_waktu'] = 'SUBUH';
                }
                elseif ($jammenit == $masjid[0]['dzuhur']) {
                    $masjid[0]['masuk_waktu'] = 'DZUHUR';
                }
                elseif ($jammenit == $masjid[0]['ashar']) {
                    $masjid[0]['masuk_waktu'] = 'ASHAR';
                }
                elseif ($jammenit == $masjid[0]['maghrib']) {
                    $masjid[0]['masuk_waktu'] = 'MAGHRIB';
                }
                elseif ($jammenit == $masjid[0]['isya']) {
                    $masjid[0]['masuk_waktu'] = 'ISYA';
                }
            }
        }

        if ($masjid)
        {
            $this->response([
                'status' => TRUE,
                'data' => $masjid
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'No data were found'
            ], REST_Controller::HTTP_NOT_FOUND); 
        }
    }

    public function simple_get()
    {
        $id = $this->get('id');

        if ($id === NULL)
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Params required.'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
        else
        {
            $masjid_db = $this->masjid->get($id);
        }

        if ($masjid_db)
        {
            $masjid = array();
            $url = base_url() . "api/masjid?id=" . $masjid_db[0]['id'];

            $jsondata = file_get_contents($url);
            $data = (array) json_decode($jsondata, TRUE);

            $masjid[0]['id'] =  $data['data'][0]['id'];
            $masjid[0]['nama'] =  $data['data'][0]['nama'];
            $masjid[0]['jarak_azan_iqamah'] = $data['data'][0]['jarak_azan_iqamah'];
            $masjid[0]['masuk_waktu'] = $data['data'][0]['masuk_waktu'];
            $masjid[0]['timenow'] = $data['data'][0]['timenow'];
            $masjid[0]['azan_selanjutnya'] = $data['data'][0]['azan_selanjutnya'];
            $masjid[0]['waktu_selanjutnya'] = $data['data'][0]['waktu_selanjutnya'];

            $this->response([
                'status' => TRUE,
                'data' => $masjid
            ], REST_Controller::HTTP_OK); 
        }
        else {
            {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data were found'
                ], REST_Controller::HTTP_NOT_FOUND); 
            }
        }
    }

    public function set_jarak_iqamah_put()
    {
        $id = $this->put('id');
        $data = array(
            'jarak_azan_iqamah' => $this->put('value')
        );

        if ($this->masjid->update($data, $id) > 0)
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Successfully updated'
            ], REST_Controller::HTTP_NO_CONTENT); 
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'No data were updated'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    public function start_countdown_put()
    {
        $id = $this->put('id');
        $masjid = $this->masjid->get($id);
        date_default_timezone_set($masjid[0]['timezone']);

        $data = array(
            'waktu_mulai_iqamah' => date('H:i:s', time())
        );

        if ($this->masjid->update($data, $id))
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Successfully updated'
            ], REST_Controller::HTTP_NO_CONTENT); 
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'No data were updated'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    public function stop_countdown_put()
    {
        $id = $this->put('id');
        $data = array(
            'waktu_mulai_iqamah' => '00:00:00'
        );

        if ($this->masjid->update($data, $id))
        {
            $this->response([
                'status' => TRUE,
                'message' => 'Successfully updated'
            ], REST_Controller::HTTP_NO_CONTENT); 
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'No data were updated'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }
}
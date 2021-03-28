<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//tambahin line ini
   require APPPATH . '/libraries/REST_Controller.php';

//ubah extendsnya jadi Rest
class Api extends REST_Controller{

	public function panggil_get()
	{
		$data = $this->db->get('tbm_user')->result();
		$this->response(
			array(
				"data" => $data,
				"status"=>"success"
			), 200);
	}

	// underscore post (_post) 
	//digunakan untuk mendefinisikan function POST
	public function daftar_post()
	{
		//inisialisasi array
		//untuk input ke database
		$input = array(
			//$this->post
			//untuk menangkap nilai yg dikirim
			'nama' 		=> $this->post('nama'),
			'no_telp' 	=> $this->post('no_telp'),
			'username' 	=> $this->post('username'),
			'password' 	=> md5($this->post('password'))
		);
		//syntax untuk insert ke database
		$this->db->insert('tbm_user', $input);
		//logic if
		//untuk mengecek apakah input berhasil
		if($this->db->affected_rows() > 0)
		{
			$resp = "success";
		}else{
			$resp = "failed";
		};
		//aturan untuk mengeluarkan respon api
		$this->response(array("status"=>$resp),200);
	}

	public function masuk_post()
	{
		$data_cek = array(
			'username' => $this->post('username'),
			'password' => md5($this->post('password'))
		);
		$proses_cek = $this->db->where($data_cek)->get('tbm_user')->result();
		if(count($proses_cek) > 0)
		{
			$resp = "success";
		}else{
			$resp = "failed";
		};
		$this->response(
			array(
				"data"	 => $proses_cek,
				"status" => $resp
			),200);
	}


	public function list_barang_get()
	{
		$data_kategori = $this->db->get('tbm_katbarang')->result();
		foreach ($data_kategori as $data) {
			$data->list = $this->db->where('id_kategori', $data->id_kategori)->get('tbm_barang')->result();
		}
		$this->response(
			array(
				"data"	 => $data_kategori,
			),200);

	}

	public function detail_barang_get($id_barang = null)
	{
		$detail = $this->db->where('id_barang', $id_barang)
				->get('tbm_barang')->result();

		if(count($detail) > 0)
		{
			$data = $detail;
			$resp = "success";
		}else{
			$data = "Not found";
			$resp = "failed";
		};
		$this->response(
			array(
				"data"	 => $data,
				"status" => $resp
			),200);

	}

	public function tambah_keranjang_post()
	{
		$input = array(
			'id_barang' 	=> $this->post('id_barang'),
			'id_user' 		=> $this->post('id_user'),
			'qty' 			=> $this->post('qty'),
			'harga'	 		=> $this->post('harga')
		);
		$this->db->insert('tbt_pembelian', $input);
		if($this->db->affected_rows() > 0)
		{
			$message = "Berhasil menambahkan ke keranjang.";
			$resp 	 = "success";
		}else{
			$message = "Proses gagal, Mohon ulangi kembali.";
			$resp    = "failed";
		};
		$this->response(
			array(
				"message"	 => $message,
				"status" => $resp
			),200);
	}






















}

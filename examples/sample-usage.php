<?php

use Illuminate\Support\Facades\Route;
use Novay\Word\Facades\Word;

Route::get('/', function () {

	$data = [
		'nomor' => '015/BT/SK/V/2017', 
		'nama'   => 'Melani Malik',
		'nip' => '6472065508XXXX', 
		'alamat' => 'Jl. Manunggal Gg. 8 Loa Bakung, Samarinda',
		'permohonan' => 'Permohonan pengurusan pembuatan NPWP', 
		'perusahaan' => 'CV. Borneo Teknomedia', 
		'kota' => 'Samarinda', 
		'direktur' => 'Novianto Rahmadi',
		'tanggal' => date('d F Y'),
	];

	$pengalaman = [
		[
			'no' => 1, 
			'client' => 'Diskominfo Samarinda', 
			'pekerjaan' => 'Samarinda AI', 
			'posisi' => 'Data Analyst'
		],
		[
			'no' => 2, 
			'client' => 'Disdik Berau', 
			'pekerjaan' => 'Sistem Unifikasi Data Pendidikan', 
			'posisi' => 'Programmer'
		],
		[
			'no' => 3, 
			'client' => 'BPKAD Kutai Kartanegara', 
			'pekerjaan' => 'Amanda', 
			'posisi' => 'Programmer'
		],
	];

	$filePath = storage_path('app/public/output.docx');

	Word::load(storage_path('app/templates/template.docx'))
		->setValues($data)
		->setLoop('no', $pengalaman)
		->setImage('ttd', storage_path('app/templates/ttd.png'), 150, 150)
		->saveAs($filePath);

	return response()->download($filePath);

});
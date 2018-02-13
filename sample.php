<?php

Route::get('/', function () {

	$file = public_path('surat_pernyataan.rtf');
	
	$array = array(
		'[NOMOR_SURAT]' => '015/BT/SK/V/2017',
		'[PERUSAHAAN]' => 'CV. Borneo Teknomedia',
		'[NAMA]' => 'Melani Malik',
		'[NIP]' => '6472065508XXXXX',
		'[ALAMAT]' => 'Jl. Manunggal Gg. 8 Loa Bakung, Samarinda',
		'[PERMOHONAN]' => 'Permohonan pengurusan pembuatan NPWP',
		'[KOTA]' => 'Samarinda',
		'[DIRECTOR]' => 'Noviyanto Rahmadi',
		'[TANGGAL]' => date('d F Y'),
	);

	$nama_file = 'surat-keterangan-kerja.doc';

	return WordTemplate::export($file, $array, $nama_file);

});
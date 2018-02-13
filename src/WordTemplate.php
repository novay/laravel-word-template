<?php

namespace Novay\WordTemplate;

class WordTemplate
{
    function __construct()
    {
        // 
    }

    public function export($file = null, $replace = null, $filename = 'default.doc') 
    {
        if(is_null($file))
            return response()->json(['error' => 'This method needs some parameters. Please check documentation.']);

        if(is_null($replace))
            return response()->json(['error' => 'This method needs some parameters. Please check documentation.']);

        $dokumen = $this->verify($file);
        
        foreach($replace as $key => $value) {
            $dokumen = str_replace($key, $value, $dokumen);
        }
        
        header("Content-type: application/msword");
        header("Content-disposition: inline; filename={$filename}");
        header("Content-length: ".strlen($dokumen));
        
        echo $dokumen;
    }

    public function verify($file) 
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $response = file_get_contents($file, false, stream_context_create($arrContextOptions));

        return $response;
    }

}

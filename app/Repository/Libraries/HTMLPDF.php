<?php

/*
*     AUTHOR:
*     CREATED AT:
*     DESCRIPTION: Helper class for HTML to PDF conversion API
*/

namespace NTI\Repository\Libraries;

use Illuminate\Support\Facades\DB;

class HTMLPDF{

      static function getPDF($html)
      {
            // Get cURL resource
        $curl = curl_init();
        // Set some options
        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => array(
                'Authentication: Token 5KAvAztq71IZVzuLG_KyG6Tb6SRbOMvr',
                // Idris: 5KAvAztq71IZVzuLG_KyG6Tb6SRbOMvr
                // Onu: G36--RvQ1HpTIG2G1fRFXJKtMMjw7oki
            ),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://htmlpdfapi.com/api/v1/pdf',
            CURLOPT_POST => 1,

            // URL
            CURLOPT_POSTFIELDS => array(
                'html' => $html,
            ),
        ));

        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        return $resp;
      }

      static function uploadAsset($file)
      {
        /*curl -H 'Authentication:Token G36--RvQ1HpTIG2G1fRFXJKtMMjw7oki' \
        -F 'file=@my_image.jpg' \
        'https://htmlpdfapi.com/api/v1/assets'*/
        // Get cURL resource
        //$cfile = new CurlFile('filename.png', 'image/png', 'filename.png');
        $cfile = curl_file_create($file,'image/png');
        $post = array('file' =>$cfile);
        $curl = curl_init();

        // Set some options
        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => array(
                //'Content-Type: multipart/form-data',
                'Authentication: Token 5KAvAztq71IZVzuLG_KyG6Tb6SRbOMvr', 
                // Don't forget to change the token!!!
                // Idris: 5KAvAztq71IZVzuLG_KyG6Tb6SRbOMvr
                // Onu: G36--RvQ1HpTIG2G1fRFXJKtMMjw7oki
            ),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://htmlpdfapi.com/api/v1/assets',
            CURLOPT_POST => 1,

            // URL
            CURLOPT_POSTFIELDS => $post//array(
                //'file' => $file
            //),
        ));

        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        return $resp;
      }
}
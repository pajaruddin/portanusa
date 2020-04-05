<?php

namespace App\Libraries;

Use Config;

class Shipping {

    public static function getProvinces(){
        $url = Config::get('rajaongkir.rajaongkir_url');
        $api_key = Config::get('rajaongkir.rajaongkir_api_key');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $api_key
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, TRUE);
        }
    }

    public static function getCities($province_id) {
        $url = Config::get('rajaongkir.rajaongkir_url');
        $api_key = Config::get('rajaongkir.rajaongkir_api_key');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "city?province=" . $province_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $api_key
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, TRUE);
        }
    }

    public static function getCost($city_id, $weight){
        $url = Config::get('rajaongkir.rajaongkir_url');
        $api_key = Config::get('rajaongkir.rajaongkir_api_key');
        $origin = "151";
            
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url."cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=".$origin."&originType=city&destination=".$city_id."&destinationType=city&weight=".$weight."&courier=jne",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: ". $api_key
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, TRUE);
        }
    }

    public static function get_province($province_id) {
        $url = Config::get('rajaongkir.rajaongkir_url');
        $api_key = Config::get('rajaongkir.rajaongkir_api_key');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "province?id=" . $province_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $api_key
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        $data_province = array();
        $province = json_decode($response, TRUE);
        if (!empty($province)) {
            if ($province['rajaongkir']['status']['code'] == 200) {
                $data_province = array(
                    'id' => $province['rajaongkir']['results']['province_id'],
                    'name' => $province['rajaongkir']['results']['province']
                );
            }
        }

        return $data_province;
    }

    public static function get_city($city_id, $province_id) {
        $url = Config::get('rajaongkir.rajaongkir_url');
        $api_key = Config::get('rajaongkir.rajaongkir_api_key');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "city?id=" . $city_id . "&province=" . $province_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . $api_key
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $data_city = array();
        $city = json_decode($response, TRUE);
        if (!empty($city)) {
            if ($city['rajaongkir']['status']['code'] == 200) {
                $data_city = array(
                    'id' => $city['rajaongkir']['results']['city_id'],
                    'type' => $city['rajaongkir']['results']['type'],
                    'city_name' => $city['rajaongkir']['results']['city_name']
                );
            }
        }

        return $data_city;
    }

}

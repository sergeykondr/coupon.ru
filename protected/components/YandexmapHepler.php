<?php
class YandexMapHepler
{
    public static function getGeoCoordinates($address)
    {
        $params = array(
            'geocode' => $address, // �����
            'format'  => 'json',                          // ������ ������
            'results' => 1,                               // ���������� ��������� �����������
            //'key'     => '...',                           // ��� api key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //get the code of request
        curl_close($ch);

        if($httpCode == 400)
            return 'not found'; ////

        if($httpCode == 200) //is ok?
        {

            $response = json_decode($output);

            if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
            {
                // GeoObject->Point->pos = 37.617761 55.755773 (���������� ����� ������)
                $coordinates = explode(" ", $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos); // �������� ������, [0] - �������, [1] - ������
                return ($coordinates[0] . ',' . $coordinates[1]); //  // [0] - �������, [1] - ������. �������. ����� �������
                /*
                return array($coordinates[0],
                             $coordinates[1]
                       );
                */
            }
            else
            {
                return 'not found';
            }
        }
    }



}
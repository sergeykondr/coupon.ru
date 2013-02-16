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
        $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));

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
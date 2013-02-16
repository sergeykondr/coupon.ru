<?php
class YandexMapHepler
{
    public static function getGeoCoordinates($address)
    {
        $params = array(
            'geocode' => $address, // адрес
            'format'  => 'json',                          // формат ответа
            'results' => 1,                               // количество выводимых результатов
            //'key'     => '...',                           // ваш api key
        );
        $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));

        if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
        {
            // GeoObject->Point->pos = 37.617761 55.755773 (коориднаты через пробел)
            $coordinates = explode(" ", $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos); // получаем массив, [0] - долгота, [1] - широта
            return ($coordinates[0] . ',' . $coordinates[1]); //  // [0] - долгота, [1] - широта. возвращ. через запятую
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
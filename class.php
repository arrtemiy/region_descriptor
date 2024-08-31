<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use GeoIp2\Database\Reader;
use morphos\Russian\GeographicalNamesInflection;

class RegionDescriptorComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult['CITY'] = $this->getCity();
        $this->arResult['TEXT'] = $this->getTextForCity($this->arResult['CITY']);
        $this->includeComponentTemplate();
    }

    private function getCity()
    {
        $ip = $_GET['test-ip'] ?? $_SERVER['REMOTE_ADDR'];
        $databaseFile = $_SERVER['DOCUMENT_ROOT'] . '/local/components/custom/region_descriptor/GeoLite2-City.mmdb';

        try {
            $reader = new Reader($databaseFile);
            $record = $reader->city($ip);

            if ($record->country->isoCode === 'RU') {
                $cityName = $record->city->names['ru'] ?? $record->city->name;
                return $cityName ?? 'Россия';
            } else {
                return 'Россия';
            }
        } catch (Exception $e) {
            return 'Россия';
        }
    }

    private function getTextForCity($city)
    {
        // Массив исключений для городов, которые склоняются неправильно
        $exceptions = [
//            'Калининград' => 'Калининграду',
        ];

        // Если город есть в массиве исключений, используем его склонение
        if (isset($exceptions[$city])) {
            $cityInPrepositional = $exceptions[$city];
        } else {
            $cityInPrepositional = GeographicalNamesInflection::getCase($city, 'дательный');
        }

        if ($city != 'Россия') {
            $region = ' и области';
        }

        return "Промышленные насосы с доставкой<br>по " . $cityInPrepositional . $region;
    }
}
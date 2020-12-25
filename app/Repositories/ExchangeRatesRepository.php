<?php

namespace App\Repositories;

use Arr;
use Carbon\Carbon;
use SimpleXMLElement;

class ExchangeRatesRepository
{
    /**
     * Get rates from CBR by params
     *
     * @param array $params
     * @return array
     */
    public function get(array $params): array
    {
        // Prepare params
        $params['dateStart'] = $this->dateFormat($params['dateStart'] ?? 'now');
        $params['dateEnd'] = $this->dateFormat($params['dateEnd'] ?? 'now');

        // Create unique cache key
        ksort($params);
        $cacheKey = sprintf('%s::%s(%s)', __CLASS__, __FUNCTION__, serialize($params));

        return cache()->remember($cacheKey, now()->addMonth(), function() use ($params) {
            $xmlBase = (array) $this->parse('XML_dynamic.asp', [
                'date_req1' => $params['dateStart'],
                'date_req2' => $params['dateEnd'],
                'VAL_NM_RQ' => $params['baseCode'],
            ]);
            $xmlBase['Record'] = Arr::wrap($xmlBase['Record'] ?? null);

            $xmlCompare = (array) $this->parse('XML_dynamic.asp', [
                'date_req1' => $params['dateStart'],
                'date_req2' => $params['dateEnd'],
                'VAL_NM_RQ' => $params['compareCode'],
            ]);
            $xmlCompare['Record'] = Arr::wrap($xmlCompare['Record'] ?? null);

            $result = [];
            /** @var SimpleXMLElement $item */
            foreach ($xmlBase['Record'] as $k => $item) {
                $base = floatval(str_replace(',', '.', (string) $item->Value));
                $compare = floatval(str_replace(',', '.', (string) $xmlCompare['Record'][$k]->Value));
                $result[(string) $item['Date']] = (float) number_format($base / $compare, 2);
            }
            return $result;
        });
    }

    /**
     * Get actual references from CBR
     *
     * @return array
     */
    public function getReferences(): array
    {
        $result = [];
        foreach ($this->parse('XML_valFull.asp', ['d' => 0]) as $item) {
            $result[] = [
                'code' => (string) $item['ID'],
                'iso' => (string) $item->ISO_Char_Code,
                'name' => (string) $item->Name,
            ];
        }
        return $result;
    }

    /**
     * Prepare date for CBR API
     *
     * @param string $date
     * @return string
     */
    protected function dateFormat(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /**
     * Send API request for getting XML
     *
     * @param string $type
     * @param array $params
     * @return SimpleXMLElement
     */
    protected function parse(string $type, array $params): SimpleXMLElement
    {
        $content = simplexml_load_file("http://www.cbr.ru/scripts/{$type}?" . http_build_query($params));
        return $content ?? new SimpleXMLElement('<?xml version="1.0"?><body></body>');
    }
}

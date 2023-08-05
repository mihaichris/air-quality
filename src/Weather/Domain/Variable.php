<?php

namespace Air\Quality\Weather\Domain;

use Air\Quality\Common\Trait\EnumToArrayTrait;

enum Variable: string
{
    use EnumToArrayTrait;

    case ParticulateMatter10 = 'pm10';
    case ParticulateMatter2_5 = 'pm2_5';
    case CarbonMonoxide = 'carbon_monoxide';
    case NitrogenDioxide = 'nitrogen_dioxide';
    case SulphurDioxide = 'sulphur_dioxide';
    case Ozone = 'ozone';
    case AerosolOpticalDepth = 'aerosol_optical_depth';
    case Dust = 'dust';
    case UVIndex = 'uv_index';
    case UVIndexClearSky = 'uv_index_clear_sky';
    case Ammonia = 'ammonia';
    case AlderPollen = 'alder_pollen';
    case BirchPollen = 'birch_pollen';
    case GrassPollen = 'grass_pollen';
    case MugwortPollen = 'mugwort_pollen';
    case OlivePollen = 'olive_pollen';
    case RagweedPollen = 'ragweed_pollen';
    case EuropeanAQI = 'european_aqi';

    public function getLabel(): string
    {
        $labelMapping = self::getLabelMappings();

        return $labelMapping[$this->value];
    }

    /** @return array{pm10: string, pm2_5: string, carbon_monoxide: string, nitrogen_dioxide: string, sulphur_dioxide: string, ozone: string, aerosol_optical_depth: string, dust: string, uv_index: string, uv_index_clear_sky: string, ammonia: string, alder_pollen: string, birch_pollen: string, grass_pollen: string, mugwort_pollen: string, olive_pollen: string, ragweed_pollen: string} */
    public static function getLabelMappings(): array
    {
        return [
            'pm10' => 'Particulate Matter (PM10)',
            'pm2_5' => 'Particulate Matter (PM2.5)',
            'carbon_monoxide' => 'Carbon Monoxide',
            'nitrogen_dioxide' => 'Nitrogen Dioxide',
            'sulphur_dioxide' => 'Sulphur Dioxide',
            'ozone' => 'Ozone',
            'aerosol_optical_depth' => 'Aerosol Optical Depth',
            'dust' => 'Dust',
            'uv_index' => 'UV Index',
            'uv_index_clear_sky' => 'UV Index Clear Sky',
            'ammonia' => 'Ammonia',
            'alder_pollen' => 'Alder Pollen',
            'birch_pollen' => 'Birch Pollen',
            'grass_pollen' => 'Grass Pollen',
            'mugwort_pollen' => 'Mugwort Pollen',
            'olive_pollen' => 'Olive Pollen',
            'ragweed_pollen' => 'Ragweed Pollen',
            'european_aqi' => 'European Air Quality Index (AQI)'
        ];
    }
}

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
}

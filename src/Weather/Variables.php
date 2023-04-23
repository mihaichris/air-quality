<?php

namespace Air\Quality\Weather;

enum Variables: string
{
    case ParticulateMatter10 = 'pm10';
    case ParticulateMatter2_5 = 'pm2_5';
    case CarbonMonoxide = 'carbon_monoxide';
    case NitrogenDioxide = 'nitrogen_dioxide';
}

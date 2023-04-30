<?php

namespace Air\Quality\Weather\Domain;

enum DomainsSource: string
{
    case AUTO = 'auto';
    case CAMS_EUROPE = 'cams_europe';
    case CAMS_GLOBAL = 'cams_global';
}

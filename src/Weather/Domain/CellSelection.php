<?php

namespace Air\Quality\Weather\Domain;

enum CellSelection: string
{
    case NEAREST = 'nearest';
    case LAND = 'land';
    case SEA = 'sea';
}

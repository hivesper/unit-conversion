<?php

enum Type: string
{
    case AREA = 'meter^2';
    case ENERGY = 'joule';
    case LENGTH = 'meter';
    case MASS = 'kilogram';
    case TIME = 'second';
    case VOLUME = 'meter^3';
}
<?php

enum SiPrefix: int {
    case YOTTA = 24;
    case ZETTA = 21;
    case EXA = 18;
    case PETA = 15;
    case TERA = 12;
    case GIGA = 9;
    case MEGA = 6;
    case KILO = 3;
    case HECTO = 2;
    case DECA = 1;
    case DECI = -1;
    case CENTI = -2;
    case MILLI = -3;
    case MICRO = -6;
    case NANO = -9;
    case PICO = -12;
    case FEMTO = -15;
    case ATTO = -18;
    case ZEPTO = -21;
    case YOCTO = -24;
}
<?php
/**
 * Created by PhpStorm.
 * User: bertrand
 * Date: 10/03/2015
 * Time: 18:55
 */

abstract class DATA_TYPE
{
    const TEXT = 5;
    const RICHTEXT = 6;
    const BARCODE = 19;

    const NUMERICAL = 4;
    const PERCENTAGE = 7;
    const UNITY = 16;
    const CURRENCY = 20;

    const SINGLEOPTION = 10;
    const MULTIPLEOPTIONS = 12;
    const YESNO = 9;

    const IMAGE = 11;

    const PRODUCT = 17;
    const TABLE = 18;
    const CUSTOMFIELD = 21;

    const STATUS = 100;
    const BOOLEAN = 0;
	const INT = 1;
	const LONG = 2;
	const FLOAT = 3;
}

abstract class LANG
{
    const FR = 4;
    const EN = 2;
    const DE = 1;
    const ES = 3;
    const IT = 5;
    const PT = 6;
    const AR = 7;
}

abstract class CONTACT_TYPE
{
    const SUPPLIER = 0;
    const RETAILER = 1;
    const MANUFACTURER = 2;
}

abstract class IDENTIFICATION_TYPE
{
    const INTERNAL = 0;
    const SUPPLIER = 1;
}
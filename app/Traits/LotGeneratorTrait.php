<?php 

namespace App\Traits;

// To Do: check duplicate lot numbers!!!
/*
    Un nuevo parámetro, o por configuración, hacer algo para el caso que haya más de un lote el día de fabricación de un roducto:
    1er lote: 19-03-64
    2o  lote: 19-03-64-1 ó 19-03-64a
    busco lotes like 19-03-64%% ordenados alfabéticamente (o por id ??): el primero es el original, el último es el que hay que añadir a continuación.
    luego: función para unir lotes. Pero entonces, ¿se pierde trazabilidad?
*/

use App\Models\Lot;

trait LotGeneratorTrait
{
    /**
     * Display a listing of available generators.
     *
     * @return Array
     */
    public static function getLotPolicyList()
    {
            $class = get_called_class();

            $list = [
                        'FIFO'  => l($class.'.'.'FIFO',  [], 'appmultilang'),
                        'LIFO'  => l($class.'.'.'LIFO',  [], 'appmultilang'),
            ];

            return $list;
    }
    /**
     * Display a listing of available generators.
     *
     * @return Array
     */
    public static function getGeneratorList()
    {
            $class = get_called_class();

            $list = [
                        'Default'       => l($class.'.'.'Default',       [], 'appmultilang'),
                        'LongCaducity'  => l($class.'.'.'LongCaducity',  [], 'appmultilang'),
                        'ShortCaducity' => l($class.'.'.'ShortCaducity', [], 'appmultilang'),
                        'CaducityDate'  => l($class.'.'.'CaducityDate',  [], 'appmultilang'),
            ];

            return $list;
    }

    /**
     * Default lot generator. SHOULD NOT DELETE.
     *
     * @param  \Carbon\Carbon $mfg_date
     * @param  \App\Models\Product  $product
     * @param  String $expiry_time (used if $product = null)
     * @return String
    */
    public static function generatorDefault( $mfg_date, $product = null, $expiry_time = '' )
    {
        if ($product)
            $expiry_time = $product->expiry_time;

        $expiry_date = self::getExpiryDate( $mfg_date, $expiry_time );
        
        $lot = date("y-m-d");

        return $lot;
    }

    /**
     * Lot generator LongCaducity. Customize according to your needs.
     *
     * @param  \Carbon\Carbon $mfg_date
     * @param  \App\Models\Product  $product
     * @param  String $expiry_time (used if $product = null)
     * @return String
    */
    public static function generatorLongCaducity( $mfg_date, $product = null, $expiry_time = '' )
    {
        // lot number equals to manufacturing date
        // caducity date = manufacturing date plus product caducity days (expiry time)
        // WWDDYY
        // WW: week of the year
        // DD: day of the week (1 to 7)
        // YY: year
        //    Example: Manufacturing date: Friday, 10th of july, 2020. Caducity days: 8 months
        // Result: 28320 (28 3 20)  (always 5 positions)

        if ($product)
            $expiry_time = $product->expiry_time;

        // $expiry_date = self::getExpiryDate( $mfg_date, $expiry_time );

        if( !($mfg_date instanceof \Carbon\Carbon ))
            $mfg_date = \Carbon\Carbon::parse($mfg_date);

        // Note that \Carbon\Carbon::parse( \Carbon\Carbon::parse('2020-07-10') ) is equal to \Carbon\Carbon::parse('2020-07-10')

        $theDate = $mfg_date;

        // WW: week of the year
        $ww = str_pad($theDate->weekOfYear, 2, "0", STR_PAD_LEFT);   // Always two digits!

        // DD: day of the week (1 to 7)
        $dd = $theDate->dayOfWeekIso;

        // YY: year
        $yy = $theDate->format("y");

        $lot = "$ww$dd$yy";

        return $lot;
    }

    /**
     * Lot generator ShortCaducity. Customize according to your needs.
     *
     * @return String
    */
    public static function generatorShortCaducity( $mfg_date, $product = null, $expiry_time = '' )
    {
        // lot number equals to caducity date
        // caducity date = manufacturing date plus product caducity days (expiry time)
        // Day/Month/Year   Example: 12/07/20  (always 8 positions)

        if ($product)
            $expiry_time = $product->expiry_time;

        $expiry_date = self::getExpiryDate( $mfg_date, $expiry_time );

        $lot = $expiry_date->format("d/m/y");

        return $lot;
    }

    /**
     * Lot generator Caducity Date. Customize according to your needs.
     *
     * @return String
    */
    public static function generatorCaducityDate( $mfg_date, $product = null, $expiry_time = '' )
    {
        // lot number equals to caducity date
        // caducity date = manufacturing date plus product caducity days (expiry time)
        // Day-Month-Year   Example: 12-07-20  (always 8 positions)

        if ($product)
            $expiry_time = $product->expiry_time;

        $expiry_date = self::getExpiryDate( $mfg_date, $expiry_time );

        $lot = $expiry_date->format("d-m-y");

        return $lot;
    }

    /**
     * Lot generator main function.
     *
     * @param  \Carbon\Carbon $mfg_date
     * @param  \App\Models\Product  $product
     * @param  String $expiry_time (used if $product = null)
     * @return String
    */
    public static function generate( $mfg_date = null, $product = null, $expiry_time = '' )
    {
        if ( $mfg_date == null )
            $mfg_date = \Carbon\Carbon::now();

        $expiry_time = (string) $expiry_time;   // to convert null to '' and numbers to strings (if needed)

        if ( $expiry_time == '' )
            $expiry_time = '0';
            

        $generator = $product ? 'generator'.$product->lot_number_generator : 'generatorDefault';

        if ( !method_exists(Lot::class, $generator) )
            $generator = 'generatorDefault';
        
        return self::$generator($mfg_date, $product, $expiry_time);
    }

    /* **************************************************************************************** */

    /**
     * Helper funcion.
     *
     * @param  String $expiry_time (used if $product = null)
     * @return Array
    */
    public static function parseExpiryTime( $expiry_time )
    {
        $last = substr($expiry_time, -1);

        if ( in_array($last, ['Y', 'y', 'A', 'a']) )
        {
            // $expiry_time is years
            $nbr = (float) substr($expiry_time, 0, -1);

            $term = 'y';

        } else 
        if ( in_array($last, ['M', 'm']) )
        {
            // $expiry_time is months
            $nbr = (float) substr($expiry_time, 0, -1);

            $term = 'm';

        } else 
        if ( in_array($last, ['D', 'd']) )
        {
            // $expiry_time is days
            $nbr = (float) substr($expiry_time, 0, -1);

            $term = 'd';

        } else {
            // Guess $expiry_time is days (default)
            $nbr = (float) $expiry_time;

            $term = 'd';

        }
        
        return ['nbr' => $nbr, 'term' => $term];
    }

    /**
     * Helper funcion.
     *
     * @param  \Carbon\Carbon $mfg_date
     * @param  String $expiry_time (used if $product = null)
     * @return Array
    */
    public static function getExpiryDate( $mfg_date, $expiry_time = '' )
    {
        $expiry_date = \Carbon\Carbon::parse($mfg_date); // or $mfg_date->copy() if $mfg_date is a Carbon instance

        // Lets see if $expiry_time is days, months or years!
        extract( self::parseExpiryTime( $expiry_time ) );

        if ( $term == 'y' )
        {
            // $expiry_time is years
            $expiry_date->addYears($nbr);

        } else 
        if ( $term == 'm' )
        {
            // $expiry_time is months
            $expiry_date->addMonths($nbr);

        } else 
        if ( $term == 'd' )
        {
            // $expiry_time is days
            $expiry_date->addDays($nbr);

        } else {
            // Guess $expiry_time is days (default)
            $expiry_date->addDays($nbr);

        }

        return $expiry_date;
    }
}

// Nice to see: https://stackoverflow.com/questions/1005857/how-to-call-a-function-from-a-string-stored-in-a-variable
// https://www.php.net/manual/es/function.method-exists.php
// https://stackoverflow.com/questions/4646991/call-a-php-function-dynamically
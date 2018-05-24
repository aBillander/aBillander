<?php

namespace App;

class Calculator {

    // PHP Margin Calculator
    public static function margin( $icst, $iprc, \App\Currency $currency = null)
    {
      if ($currency === null)
            $currency = Currency::find( intval(Configuration::get('DEF_CURRENCY')) );

      if ( $currency->id != intval( Configuration::get('DEF_CURRENCY') ) ) {
        $iprc /= $currency->conversion_rate;
      }

      if ( Configuration::get('MARGIN_METHOD') == 'CST' )  
      {  // margin sobre el precio de coste

         if ($icst==0) return NULL;

         $margin = ($iprc-$icst)/$icst;

      } else {
         // Default (or PRC): sobre el precio de venta

         if ($iprc==0) return NULL;

         $margin = ($iprc-$icst)/$iprc;

      }
      return 100.0*$margin;
    }

    // PHP Price Calculator
    public static function price( $icst, $im ) 
    {
      if ( Configuration::get('MARGIN_METHOD') == 'CST' )  
      {

         $price = $icst*(1.0+$im/100.0);

      } else {
         
         if ((1.0-$im/100.0)==0) return NULL;

         $price = $icst/(1.0-$im/100.0);

      }
      return $price;
    }

    // JavaScript Margin Calculator
    public static function marginJSCode( \App\Currency $currency = null, $withTags = NULL)
    {
        
      if ($currency === null)
            $currency = \App\Context::getContext()->currency;

        $jscode = "
                var crate = " . $currency->conversion_rate . ";

                function discountcalc()
                {
                   var base_price = parseFloat( $('#base_price').val() );
                   var price = parseFloat( $('#price').val() );

                   $('#discount').val( 100.0*(base_price-price/crate)/base_price );   
                }

               ";

        if ( Configuration::get('MARGIN_METHOD') == 'CST' ) {   // {* Margen sobre el precio de coste *}
           $jscode .= "
               function margincalc(icst, iprc)
               {
                  var margin = 0;

                  iprc /= crate;

                  if (icst==0) return '-';

                  margin = (iprc-icst)/icst;

                  return margin*100.0;
               }
               function pricecalc(icst, imc)
               {
                  var price = 0;

                  imc = imc/100.0;

                  price = icst*(1+imc);

                  return price*crate;
               }";
        } else {                                                // {* Default: sobre el precio de venta *}
           $jscode .= "
               function margincalc(icst, iprc)
               {
                  var margin = 0;

                  iprc /= crate;

                  if (iprc==0) return '-';

                  margin = (iprc-icst)/iprc;

                  return margin*100.0;
               }
               function pricecalc(icst, ims)
               {
                  var price = 0;

                  ims = ims/100.0;

                  if ((1-ims)==0) return '-';

                  price = icst/(1.0-ims);

                  return price*crate;
               }";
        }
        if ($withTags) $jscode = '<script type="text/javascript">'."\n" . $jscode . "\n".'</script>';

        return $jscode;
    }

    // PHP Discount Calculator
    // $product_price : Default currency ( DEF_CURRENCY )
    // $line_price : $currency
    public static function discount($product_price, $line_price, \App\Currency $currency = null)
    {
      if ($currency === null)
            $currency = \App\Context::getContext()->currency;

      $discount = 100.0*(1.0-($line_price/$currency->conversion_rate)/$product_price);

      return $discount;
    }
	
}
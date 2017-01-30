<?php
return Array(
  /*
   * Define durations with price and label, basically this is the patern :

      '<label>' => Array(
        '<price>',
        '<duration>'
      ),

   * With price as a float (Eg. 3.00) and duration in month (Eg. 1 => 1 Month).
   */
  'payOptions' => Array(
    'currencyCode' => 'EUR',
    '1 Mois - Premium' => Array (
      '20.00',
      '1'
    ),
    '1 An - Normal' => Array (
      '60.00',
      '12'
    )
  ),
);
?>

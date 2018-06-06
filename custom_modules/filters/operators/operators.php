<?php

include('clients/base/filters/operators/operators.php');

$viewdefs['{MODULENAME}']['base']['filter']['operators']['tag'] = array_merge(
    $viewdefs['base']['filter']['operators']['tag'],
    array(
        '$and_in' => 'LBL_OPERATOR_CONTAINS_ALL',
    )
);

<?php

$modules = array(
    'Contacts' => 'Contact',
    'Accounts' => 'Account',
);

$templates['custom_extension_modules'] = array(
    'directory_pattern' => 'custom/Extension/modules/{MODULENAME}/Ext',
    'modules' => $modules,
);

$templates['custom_modules'] = array(
    'directory_pattern' => 'custom/modules/{MODULENAME}/clients/base',
    'modules' => $modules,
);

<?php

return [
    'host' => env('LDAP_HOST', 'pdc.yarsi.ac.id'),
    'port' => env('LDAP_PORT', 389),
    'basedn' => env('LDAP_BASE_DN', 'dc=yarsi,dc=ac,dc=id'),
];

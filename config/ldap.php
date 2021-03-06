<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Connections
    |--------------------------------------------------------------------------
    |
    | This array stores the connections that are added to Adldap. You can add
    | as many connections as you like.
    |
    | The key is the name of the connection you wish to use and the value is
    | an array of configuration settings.
    |
    */

    'connections' => [

        'default' => [

            /*
            |--------------------------------------------------------------------------
            | Auto Connect
            |--------------------------------------------------------------------------
            |
            | If auto connect is true, Adldap will try to automatically connect to
            | your LDAP server in your configuration. This allows you to assume
            | connectivity rather than having to connect manually
            | in your application.
            |
            | If this is set to false, you **must** connect manually before running
            | LDAP operations.
            |
            */

            // 'auto_connect' => env('LDAP_AUTO_CONNECT', true),
            'auto_connect' => true,

            /*
            |--------------------------------------------------------------------------
            | Connection
            |--------------------------------------------------------------------------
            |
            | The connection class to use to run raw LDAP operations on.
            |
            | Custom connection classes must implement:
            |
            |  Adldap\Connections\ConnectionInterface
            |
            */

            'connection' => Adldap\Connections\Ldap::class,

            /*
            |--------------------------------------------------------------------------
            | Connection Settings
            |--------------------------------------------------------------------------
            |
            | This connection settings array is directly passed into the Adldap constructor.
            |
            | Feel free to add or remove settings you don't need.
            |
            */

            'settings' => [

                /*
                |--------------------------------------------------------------------------
                | Schema
                |--------------------------------------------------------------------------
                |
                | The schema class to use for retrieving attributes and generating models.
                |
                | You can also set this option to `null` to use the default schema class.
                |
                | For OpenLDAP, you must use the schema:
                |
                |   Adldap\Schemas\OpenLDAP::class
                |
                | For FreeIPA, you must use the schema:
                |
                |   Adldap\Schemas\FreeIPA::class
                |
                | Custom schema classes must implement Adldap\Schemas\SchemaInterface
                |
                */

                'schema' => Adldap\Schemas\ActiveDirectory::class,

                /*
                |--------------------------------------------------------------------------
                | Account Prefix
                |--------------------------------------------------------------------------
                |
                | The account prefix option is the prefix of your user accounts in LDAP directory.
                |
                | This string is prepended to all authenticating users usernames.
                |
                */

                // 'account_prefix' => env('LDAP_ACCOUNT_PREFIX', ''),
                'account_prefix' => '',

                /*
                |--------------------------------------------------------------------------
                | Account Suffix
                |--------------------------------------------------------------------------
                |
                | The account suffix option is the suffix of your user accounts in your LDAP directory.
                |
                | This string is appended to all authenticating users usernames.
                |
                */

                // 'account_suffix' => env('LDAP_ACCOUNT_SUFFIX', ''),
                'account_suffix' => '@televisa.com.mx',

                /*
                |--------------------------------------------------------------------------
                | Domain Controllers
                |--------------------------------------------------------------------------
                |
                | The domain controllers option is an array of servers located on your
                | network that serve Active Directory. You can insert as many servers or
                | as little as you'd like depending on your forest (with the
                | minimum of one of course).
                |
                | These can be IP addresses of your server(s), or the host name.
                |
                */

                // 'hosts' => explode(' ', env('LDAP_HOSTS', 'corp-dc1.corp.acme.org corp-dc2.corp.acme.org')),
                'hosts' => explode(' ', 'corp.televisa.com.mx'),

                /*
                |--------------------------------------------------------------------------
                | Port
                |--------------------------------------------------------------------------
                |
                | The port option is used for authenticating and binding to your LDAP server.
                |
                */

                // 'port' => env('LDAP_PORT', 636),
                'port' => 636,

                /*
                |--------------------------------------------------------------------------
                | Timeout
                |--------------------------------------------------------------------------
                |
                | The timeout option allows you to configure the amount of time in
                | seconds that your application waits until a response
                | is received from your LDAP server.
                |
                */

                // 'timeout' => env('LDAP_TIMEOUT', 5),
                'timeout' => 1000,

                /*
                |--------------------------------------------------------------------------
                | Base Distinguished Name
                |--------------------------------------------------------------------------
                |
                | The base distinguished name is the base distinguished name you'd
                | like to perform query operations on. An example base DN would be:
                |
                |        dc=corp,dc=acme,dc=org
                |
                | A correct base DN is required for any query results to be returned.
                |
                */

                // 'base_dn' => env('LDAP_BASE_DN', 'dc=corp,dc=acme,dc=org'),
                'base_dn' => 'DC=corp,dc=televisa,DC=com,DC=mx',

                /*
                |--------------------------------------------------------------------------
                | LDAP Username & Password
                |--------------------------------------------------------------------------
                |
                | When connecting to your LDAP server, a username and password is required
                | to be able to query and run operations on your server(s). You can
                | use any user account that has these permissions. This account
                | does not need to be a domain administrator unless you
                | require changing and resetting user passwords.
                |
                */

                // 'username' => env('LDAP_USERNAME'),
                // 'password' => env('LDAP_PASSWORD'),
                // 'username' => 'sysadmin@televisa.com.mx',
                // 'password' => 'eyJpdiI6IlwvaXpcL0FKeG5UamZuRHhHT0d2RXVZZz09IiwidmFsdWUiOiIxN1ZuYUV2WmZ0SGpNZzM5S0FGa2YrT011M0hKVXN6T3k1SjFtejRROE84PSIsIm1hYyI6IjRiYTgwMzkyNmRjYjlmMmMwZTYyYjcyNGQzZTJiODQwZDQ0ZWFmOWM4ZDNlZDdhNjc1Zjk2ZmY1MDQzMTJmNzAifQ==',
                'username' => 'sysadmindesarro',
                'password' => 'eyJpdiI6IldZM2tCUzgyRUlUV1I5QUFnd3dCXC9BPT0iLCJ2YWx1ZSI6IkRseUhrdTVpb1VudjZyOXJcL0xmakZRPT0iLCJtYWMiOiIxZDE3YjlmNWIzNGU3MDZiOGFkNjFjNzJiZmQxZDc5M2E3ZTZkNDU0MDUyODU1MmFlNThmYThlYzRiZDQzNzY3In0=',

                /*
                |--------------------------------------------------------------------------
                | Follow Referrals
                |--------------------------------------------------------------------------
                |
                | The follow referrals option is a boolean to tell active directory
                | to follow a referral to another server on your network if the
                | server queried knows the information your asking for exists,
                | but does not yet contain a copy of it locally.
                |
                | This option is defaulted to false.
                |
                */

                'follow_referrals' => false,

                /*
                |--------------------------------------------------------------------------
                | SSL & TLS
                |--------------------------------------------------------------------------
                |
                | If you need to be able to change user passwords on your server, then an
                | SSL or TLS connection is required. All other operations are allowed
                | on unsecured protocols.
                | 
                | One of these options are definitely recommended if you 
                | have the ability to connect to your server securely.
                |
                */

                // 'use_ssl' => env('LDAP_USE_SSL', false),
                // 'use_tls' => env('LDAP_USE_TLS', false),
                'use_ssl' => false,
                'use_tls' => false,
            ],
        ],
        /******************************************************/
        'tsm' => [
            // Auto Connect
            'auto_connect' => true,
            // Connection
            'connection' => Adldap\Connections\Ldap::class,
            // Connection Settings
            'settings' => [
                // Schema
                'schema' => Adldap\Schemas\ActiveDirectory::class,
                // Account Prefix
                'account_prefix' => '',
                // Account Suffix
                'account_suffix' => '@televisa.com.mx',
                // Domain Controllers
                'hosts' => explode(' ', 'tsm.televisa.com.mx'),
                // Port
                'port' => 636,
                // Timeout
                'timeout' => 1000,
                // Base Distinguished Name
                'base_dn' => 'DC=tsm,DC=televisa,DC=com,DC=mx',
                // LDAP Username & Password
                // 'username' => 'sysadmin@televisa.com.mx',
                // 'password' => 'eyJpdiI6IlwvaXpcL0FKeG5UamZuRHhHT0d2RXVZZz09IiwidmFsdWUiOiIxN1ZuYUV2WmZ0SGpNZzM5S0FGa2YrT011M0hKVXN6T3k1SjFtejRROE84PSIsIm1hYyI6IjRiYTgwMzkyNmRjYjlmMmMwZTYyYjcyNGQzZTJiODQwZDQ0ZWFmOWM4ZDNlZDdhNjc1Zjk2ZmY1MDQzMTJmNzAifQ==',
                'username' => 'sysadmindesarro',
                'password' => 'eyJpdiI6IldZM2tCUzgyRUlUV1I5QUFnd3dCXC9BPT0iLCJ2YWx1ZSI6IkRseUhrdTVpb1VudjZyOXJcL0xmakZRPT0iLCJtYWMiOiIxZDE3YjlmNWIzNGU3MDZiOGFkNjFjNzJiZmQxZDc5M2E3ZTZkNDU0MDUyODU1MmFlNThmYThlYzRiZDQzNzY3In0=',
                // Follow Referrals
                'follow_referrals' => false,
                // SSL & TLS
                'use_ssl' => false,
                'use_tls' => false,
            ],
        ],
        'filial' => [
            // Auto Connect
            'auto_connect' => true,
            // Connection
            'connection' => Adldap\Connections\Ldap::class,
            // Connection Settings
            'settings' => [
                // Schema
                'schema' => Adldap\Schemas\ActiveDirectory::class,
                // Account Prefix
                'account_prefix' => '',
                // Account Suffix
                'account_suffix' => '@televisa.com.mx',
                // Domain Controllers
                'hosts' => explode(' ', 'filial.televisa.com.mx'),
                // Port
                'port' => 636,
                // Timeout
                'timeout' => 1000,
                // Base Distinguished Name
                'base_dn' => 'DC=filial,dc=televisa,DC=com,DC=mx',
                // LDAP Username & Password
                // 'username' => 'sysadmin@televisa.com.mx',
                // 'password' => 'eyJpdiI6IlwvaXpcL0FKeG5UamZuRHhHT0d2RXVZZz09IiwidmFsdWUiOiIxN1ZuYUV2WmZ0SGpNZzM5S0FGa2YrT011M0hKVXN6T3k1SjFtejRROE84PSIsIm1hYyI6IjRiYTgwMzkyNmRjYjlmMmMwZTYyYjcyNGQzZTJiODQwZDQ0ZWFmOWM4ZDNlZDdhNjc1Zjk2ZmY1MDQzMTJmNzAifQ==',
                'username' => 'sysadmindesarro',
                'password' => 'eyJpdiI6IldZM2tCUzgyRUlUV1I5QUFnd3dCXC9BPT0iLCJ2YWx1ZSI6IkRseUhrdTVpb1VudjZyOXJcL0xmakZRPT0iLCJtYWMiOiIxZDE3YjlmNWIzNGU3MDZiOGFkNjFjNzJiZmQxZDc5M2E3ZTZkNDU0MDUyODU1MmFlNThmYThlYzRiZDQzNzY3In0=',
                // Follow Referrals
                'follow_referrals' => false,
                // SSL & TLS
                'use_ssl' => false,
                'use_tls' => false,
            ],
        ],
        'soi' => [
            // Auto Connect
            'auto_connect' => true,
            // Connection
            'connection' => Adldap\Connections\Ldap::class,
            // Connection Settings
            'settings' => [
                // Schema
                'schema' => Adldap\Schemas\ActiveDirectory::class,
                // Account Prefix
                'account_prefix' => '',
                // Account Suffix
                'account_suffix' => '@equiposoi.net',
                // Domain Controllers
                'hosts' => explode(' ', 'equiposoi.net'),
                // Port
                'port' => 636,
                // Timeout
                'timeout' => 1000,
                // Base Distinguished Name
                'base_dn' => 'DC=equiposoi,dc=net',
                // LDAP Username & Password
                // 'username' => 'sysadmin@televisa.com.mx',
                // 'password' => 'eyJpdiI6IlwvaXpcL0FKeG5UamZuRHhHT0d2RXVZZz09IiwidmFsdWUiOiIxN1ZuYUV2WmZ0SGpNZzM5S0FGa2YrT011M0hKVXN6T3k1SjFtejRROE84PSIsIm1hYyI6IjRiYTgwMzkyNmRjYjlmMmMwZTYyYjcyNGQzZTJiODQwZDQ0ZWFmOWM4ZDNlZDdhNjc1Zjk2ZmY1MDQzMTJmNzAifQ==',
                'username' => 'sysadmindessoi',
                'password' => 'eyJpdiI6Ikk0Mk0zN013TVpQYU54MktqQ1hhZmc9PSIsInZhbHVlIjoiQTJKcVA3QXhXTWtTWnNIbmtWR0NyQT09IiwibWFjIjoiZWZjODcyY2M1Y2I0OTBlYTQxOGUyOTc5Nzk2NTUxMTkxNTc5NTFiNDc5ZmY3MzVmNGQ4Yjk4NTdmM2I4MDBlZCJ9',
                // Follow Referrals
                'follow_referrals' => false,
                // SSL & TLS
                'use_ssl' => false,
                'use_tls' => false,
            ],
        ],
    ],
];

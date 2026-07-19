<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Automatické commity
    |--------------------------------------------------------------------------
    |
    | Tlačítko v administraci umí zapsat nahrané soubory do gitu a odeslat je
    | na server. Commity vytváří vlastní "robot", aby šlo na první pohled
    | poznat, co vzniklo ručně a co přes administraci.
    |
    */

    'enabled' => env('GIT_AUTOMATION_ENABLED', true),

    'user' => [
        'name' => env('GIT_AUTOMATION_NAME', 'Spock'),
        'email' => env('GIT_AUTOMATION_EMAIL', 'spock@jirfa.cz'),
    ],

    /*
    | Odeslání na vzdálený repozitář. Vyžaduje, aby měl server nastavené
    | přihlašovací údaje (SSH klíč). Bez nich se commit vytvoří lokálně
    | a push skončí chybou, kterou administrace zobrazí.
    */
    'push' => env('GIT_AUTOMATION_PUSH', true),
    'remote' => env('GIT_AUTOMATION_REMOTE', 'origin'),

    /*
    | Cesty, které se přidávají do commitu. Záměrně jen adresáře s obrázky --
    | tlačítko nemá commitovat rozpracovaný kód.
    */
    'paths' => [
        'public/images',
    ],

    'timeout' => env('GIT_AUTOMATION_TIMEOUT', 120),

];

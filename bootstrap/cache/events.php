<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\LogoutCartStore' => 
    array (
      0 => 'App\\Listeners\\StoreCartOnLogout',
    ),
    'Illuminate\\Auth\\Events\\Login' => 
    array (
      0 => 'App\\Listeners\\StoreCartOnLogin',
      1 => 'App\\Listeners\\LogSuccessfulLogin',
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Login' => 
    array (
      0 => 'App\\Listeners\\LogSuccessfulLogin@handle',
      1 => 'App\\Listeners\\StoreCartOnLogin@handle',
    ),
    'App\\Events\\LogoutCartStore' => 
    array (
      0 => 'App\\Listeners\\StoreCartOnLogout@handle',
    ),
  ),
);
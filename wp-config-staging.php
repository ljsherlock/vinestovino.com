<?php

/**
 * WordPress config file to use one directory above WordPress root, when awesome version of wp-config.php is in use.
 *
 * Awesome wp-config.php file - https://gist.github.com/1923821
 */

/* WordPress Local Environment DB credentials */

define( 'DB_NAME', 'ljsherlock-vinestovino' );

/** Database username */
define( 'DB_USER', 'ljsherlock-vinestovino' );

/** Database password */
define( 'DB_PASSWORD', '7TQaOetMmyWJafjdCq2Z' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );


/* Keys & Salts */

define( 'AUTH_KEY',          '5,{T?xRc0X;0W6xn<G?hkB@1A[VA>,Tu:e/(k>*@Zgbg)6&8fzJnc%,yI-fJXG=a' );
define( 'SECURE_AUTH_KEY',   'y+hYkENi,C^m#5]u#C8nJ{,.Eh^f6`zw0%D>.9xZZ?:Ds8J).2=[v[ 9k/G#y!j0' );
define( 'LOGGED_IN_KEY',     'b2nzS/$^J4Lv-<3tS<)R/{&HKR|BQL:a4MyszDTG,}-GzoF/6:og]SiD@uk;d$yC' );
define( 'NONCE_KEY',         ';]Sh0-C/4~/_[kzgTTlordZpI@:<q~Otzy.0?.x4lt|N}DA8g7~HL#5g@[UOM~$N' );
define( 'AUTH_SALT',         '`Z0()SR/,[~|ndRYhA~+v[MR{wM8!k:,sMqr9q`K$(a,UuDW(Iuo{i??1X$2Z d#' );
define( 'SECURE_AUTH_SALT',  '%H71;,DfzXybc>7l z) !32aTmT/|?l^JI8xpV!kuLrF(^*A#&|PJ,w/&cvKWj3X' );
define( 'LOGGED_IN_SALT',    'gzH1Pom`4BO:oXU9lsdtDDKJNV CqQnEJsx9@/KX9n|6t=]yuOXTC5(7bT&I8&wd' );
define( 'NONCE_SALT',        '5wj8!;q3iAd,MGCJ>aHuN-ryG6SSV;agSrpzEqf?DDX%77Yh+5`t8P^.O$1=}hI:' );
define( 'WP_CACHE_KEY_SALT', '6I1F|%.0TSc~c3>Ex?d2+3HoG}NwZ5ud%g3/!m,RLeU 0bM,!W.-MZR{p$64m>3X' );
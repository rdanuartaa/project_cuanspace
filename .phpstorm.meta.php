<?php

namespace PHPSTORM_META {

    override(
        \App\Models\User::tokens(0),
        map(['' => '@\Laravel\Sanctum\PersonalAccessToken'])
    );

    override(
        \App\Models\User::createToken(0, 1),
        map(['' => '@\Laravel\Sanctum\NewAccessToken'])
    );
}

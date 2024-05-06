<?php

namespace IAWP_SCOPED\Illuminate\Contracts\Support;

/** @internal */
interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}

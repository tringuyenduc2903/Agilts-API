<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The ProductList enum.
 *
 * @method static self CART()
 * @method static self WISHLIST()
 */
class ProductList extends Enum
{
    const CART = 0;
    const WISHLIST = 1;
}

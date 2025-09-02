<?php

use App\Models\Product;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * Disminuye el stock de un producto o de su variante.
 *
 * @param array $item El item del carrito.
 */
function decrease_stock($item)
{
    $product = Product::find($item['id']);

    // Caso 1: Producto con variantes de talla y color
    if (isset($item['options']['size_id']) && isset($item['options']['color_id'])) {
        $size = Size::find($item['options']['size_id']);
        $colorOnSize = $size->colors()->where('color_id', $item['options']['color_id'])->first();

        if ($colorOnSize) {
            $newQuantity = $colorOnSize->pivot->quantity - $item['qty'];
            $size->colors()->updateExistingPivot($item['options']['color_id'], ['quantity' => $newQuantity]);
        }
    }
    // Caso 2: Producto con variantes solo de color
    elseif (isset($item['options']['color_id'])) {
        $colorOnProduct = $product->colors()->where('color_id', $item['options']['color_id'])->first();

        if ($colorOnProduct) {
            $newQuantity = $colorOnProduct->pivot->quantity - $item['qty'];
            $product->colors()->updateExistingPivot($item['options']['color_id'], ['quantity' => $newQuantity]);
        }
    }
    // Caso 3: Producto sin variantes
    else {
        $product->quantity -= $item['qty'];
        $product->save();
    }
}

/**
 * Calcula la cantidad de stock disponible para un producto o variante.
 *
 * @param int $product_id
 * @param int|null $color_id
 * @param int|null $size_id
 * @return int
 */
function qty_available($product_id, $color_id = null, $size_id = null)
{
    return get_stock($product_id, $color_id, $size_id) - qty_added($product_id, $color_id, $size_id);
}

/**
 * Obtiene la cantidad de un producto/variante que ya está en el carrito.
 *
 * @param int $product_id
 * @param int|null $color_id
 * @param int|null $size_id
 * @return int
 */
function qty_added($product_id, $color_id = null, $size_id = null)
{
    $cart = Cart::content();

    $item = $cart->where('id', $product_id)
                 ->where('options.color_id', $color_id)
                 ->where('options.size_id', $size_id)
                 ->first();

    return $item ? $item->qty : 0;
}

/**
 * Obtiene el stock total de un producto o variante desde la base de datos.
 *
 * @param int $product_id
 * @param int|null $color_id
 * @param int|null $size_id
 * @return int
 */
function get_stock($product_id, $color_id = null, $size_id = null)
{
    $product = Product::find($product_id);

    if ($size_id) {
        $size = Size::find($size_id);
        $colorOnSize = $size->colors()->where('color_id', $color_id)->first();
        return $colorOnSize ? $colorOnSize->pivot->quantity : 0;
    } elseif ($color_id) {
        $colorOnProduct = $product->colors()->where('color_id', $color_id)->first();
        return $colorOnProduct ? $colorOnProduct->pivot->quantity : 0;
    } else {
        return $product->quantity;
    }
}
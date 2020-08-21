<?php


namespace App\Exceptions;


class QuantityExceedsCurrentStockException extends \Exception
{
    protected $message = "Jumlah melebihi stock yang ada";
}
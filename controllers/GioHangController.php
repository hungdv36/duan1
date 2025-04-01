<?php

class GioHangController
{
    public $modelGioHang;
    public function __construct()
    {
        $this->modelGioHang = new GioHang();
    }
}
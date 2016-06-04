<?php
namespace Marketplace\Interfaces;

interface Payment {
	public function process($sales, $total, $customer, $shipping);
	public function formatMoney($amount);
}
?>
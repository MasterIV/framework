<?php


namespace Iv\Framework;


class Strings {
	public static function camelize($str) {
		return implode('', array_map('ucfirst', explode('_', $str)));
	}
}
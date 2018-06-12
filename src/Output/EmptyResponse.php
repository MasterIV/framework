<?php


namespace Iv\Framework\Output;


use Iv\Framework\Routing\Response;

class EmptyResponse implements Response {
	public function send() {}
}
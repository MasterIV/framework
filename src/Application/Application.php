<?php

namespace Iv\Framework\Application;

class Application {


	public function build() {
		// read all the annotations
			// create di container
			// create routing
			// create security mapping

		// manipulate result

		return $this;
	}

	public function setup() {

		// run migrations

		return $this;
	}

	public function run() {
		// load container
		// setup filters, router and dispatcher
		// route request
	}
}
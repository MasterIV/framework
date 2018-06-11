<?php


namespace Iv\Framework\Authentication;


use Iv\Framework\Injection\Annotation\Inject;
use Iv\Framework\Injection\Container;

abstract class AuthenticationFilter {
	/** @var Container */
	protected $container;
	/** @var AuthenticationService */
	protected $service;

	/** @var array */
	private $configuration;
	/** @var bool */
	private $default = false;

	/**
	 * AuthenticationFilter constructor.
	 * @param Container $container
	 * @param AuthenticationService $service
	 * @param array $configuration
	 * @Inject({"@Container", "@AuthenticationService", "~security"})
	 */
	public function __construct(Container $container, AuthenticationService $service, array $configuration) {
		$this->container = $container;
		$this->service = $service;
		$this->configuration = $configuration;
	}

	protected function checkAccess($route, $user) {
		$access = $this->default || !empty($user);

		$controller = isset( $this->configuration[$route['controller']] )
				? $this->configuration[$route['controller']]
				: ['methods' => []];

		$method = isset( $controller['methods'][$route['method']] )
				? $controller['methods'][$route['method']]
				: [];

		if(isset($controller['public']))
			$access = $controller['public'] || !empty($user);
		if(isset($method['public']))
			$access = $method['public'] || !empty($user);
		if(!empty($controller['requires']))
			$access = $access && $this->service->has($user, $controller['requires']);
		if(!empty($method['requires']))
			$access = $access && $this->service->has($user, $method['requires']);

		if(!$access)
			throw new AccessDeniedException("Access denied.");

		$this->container->set('User', $user);
	}
}
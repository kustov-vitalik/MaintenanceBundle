<?php
/**
 * Created by PhpStorm.
 * Date: 26.06.2015
 * @author Виталий
 */

namespace KustovVitalik\MaintenanceBundle\Listener;

use Doctrine\Common\Annotations\Reader;
use KustovVitalik\MaintenanceBundle\Annotations\Maintenance;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ControllerListener
{

	/**
	 * @var Reader
	 */
	private $reader;

	private $annotationName;

	private $currentEnv;

	public function __construct(Reader $reader, $annotationName, $env)
	{
		$this->reader = $reader;
		$this->annotationName = $annotationName;
		$this->currentEnv = $env;
	}

	/**
	 * @param FilterControllerEvent $event
	 *
	 * @return Response
	 */
	public function onKernelController(FilterControllerEvent $event)
	{
		if ($event->isMasterRequest()) {
			$controller = $event->getController();
			if (is_array($controller) && array_key_exists(0, $controller) && array_key_exists(1, $controller) && is_object($controller[0])) {


				/** @var Maintenance $configuration */
				$configuration = $this->reader->getClassAnnotation(
					new \ReflectionClass($controller[0]), $this->annotationName
				);
				if (!$configuration) {
					$configuration = $this->reader->getMethodAnnotation(
						(new \ReflectionObject($controller[0]))->getMethod($controller[1]), $this->annotationName
					);
				}

				if ($configuration && in_array($this->currentEnv, $configuration->envs, true)) {
					throw new HttpException(Response::HTTP_SERVICE_UNAVAILABLE, null, null, [
						'Retry-After' => $configuration->getRetryAfter()
					]);
				}
			}
		}
	}

}
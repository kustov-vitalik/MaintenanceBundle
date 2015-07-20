<?php
/**
 * Created by PhpStorm.
 * Date: 26.06.2015
 * @author <kustov.vitalik@gmail.com>
 */

namespace KustovVitalik\MaintenanceBundle\Annotations;

/**
 * Class Maintenance
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 *
 * @package KustovVitalik\MaintenanceBundle\Annotations
 */
class Maintenance {

	public $retryAfter = null;

	public $retryAt = null;

	public $envs = ['prod'];

	public function __construct($options)
	{
		if (is_array($options) && count($options)) {
			foreach ($options as $key => $value) {
				if (!property_exists($this, $key)) {
					throw new \InvalidArgumentException(
						sprintf('Property %s does not exists', $key)
					);
				}
				$this->$key = $value;
			}


		}
	}

	/**
	 * @return string
	 */
	public function getRetryAfter()
	{
		if ((int)$this->retryAfter > 0) {
			$date = new \DateTime('now + ' . (int) $this->retryAfter . ' seconds');
		} elseif ($this->retryAt && (false !== date_parse($this->retryAt))) {
			$date = new \DateTime($this->retryAt);
		} else {
			$date = new \DateTime();
		}
		return $date->format('c');
	}

}
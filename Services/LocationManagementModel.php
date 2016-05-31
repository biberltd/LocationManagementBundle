<?php
/**
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com) (C) 2015
 * @license     GPLv3
 *
 * @date        12.01.2015
 */

namespace BiberLtd\Bundle\LocationManagementBundle\Services;

use BiberLtd\Bundle\CoreBundle\CoreModel;
use BiberLtd\Bundle\CoreBundle\Responses\ModelResponse;
use BiberLtd\Bundle\LocationManagementBundle\Entity as BundleEntity;
use BiberLtd\Bundle\PhpOrientBundle\Odm\Types\DateTime;
use BiberLtd\Bundle\SiteManagementBundle\Services as SMMService;
use BiberLtd\Bundle\MultiLanguageSupportBundle\Entity as MLSEntity;
use BiberLtd\Bundle\CoreBundle\Services as CoreServices;

class LocationManagementModel extends CoreModel {
	/**
	 * @name             __construct    ()
	 *
	 * @author          Said İmamoğlu
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @param           object $kernel
	 * @param           string $dbConnection Database connection key as set in app/config.yml
	 * @param           string $orm          ORM that is used.
	 */
	public function __construct($kernel, $dbConnection = 'default', $orm = 'doctrine') {
		parent::__construct($kernel, $dbConnection, $orm);
		/**
		 * Register entity names for easy reference.
		 */
		$this->entity = array(
			'c'  => array('name' => 'LocationManagementBundle:City', 'alias' => 'c'),
			'cil'=> array('name' => 'LocationManagementBundle:CheckinLogs', 'alias' => 'cil'),
			'cl' => array('name' => 'LocationManagementBundle:CityLocalization', 'alias' => 'cl'),
			'd'  => array('name' => 'LocationManagementBundle:District', 'alias' => 'd'),
			'dl' => array('name' => 'LocationManagementBundle:DistrictLocalization', 'alias' => 'dl'),
			'm'  => array('name' => 'MemberManagement:Member', 'alias' => 'm'),
			'n'  => array('name' => 'LocationManagementBundle:Neighborhood', 'alias' => 'n'),
			'nl' => array('name' => 'LocationManagementBundle:NeighborhoodLocalization', 'alias' => 'nl'),
			'o'  => array('name' => 'LocationManagementBundle:Office', 'alias' => 'o'),
			's'  => array('name' => 'LocationManagementBundle:State', 'alias' => 's'),
			'sl' => array('name' => 'LocationManagementBundle:StateLocalization', 'alias' => 'sl'),
			'u'  => array('name' => 'LocationManagementBundle:Country', 'alias' => 'u'),
			'ul' => array('name' => 'LocationManagementBundle:CountryLocalization', 'alias' => 'ul'),
		);
	}

	/**
	 * Deconstructor
	 */
	public function __destruct() {
		foreach ($this as $property => $value) {
			$this->$property = null;
		}
	}

	/**
	 * @param mixed $cLog
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCheckinLog($cLog) {
		return $this->deleteCheckinLogs(array($cLog));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCheckinLogs(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach ($collection as $entry) {
			if ($entry instanceof BundleEntity\CheckinLogs) {
				$this->em->remove($entry);
				$countDeleted++;
			}
			else {
				$response = $this->getCheckinLog($entry);
				if (!$response->error->exist) {
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if ($countDeleted < 0) {
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, microtime(true));
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $city
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCity($city) {
		return $this->deleteCities(array($city));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCities(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach ($collection as $entry) {
			if ($entry instanceof BundleEntity\City) {
				$this->em->remove($entry);
				$countDeleted++;
			}
			else {
				$response = $this->getCity($entry);
				if (!$response->error->exist) {
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if ($countDeleted < 0) {
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, microtime(true));
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $city
	 * @param bool $bypass
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse|bool
	 */
	public function doesCityExist($city, bool $bypass = false) {
		$response = $this->getCity($city);
		$exist = true;
		if ($response->error->exist) {
			$exist = false;
			$response->result->set = false;
		}
		if ($bypass) {
			return $exist;
		}

		return $response;
	}

	/**
	 * @param mixed $cLog
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCheckinLog($cLog) {
		$timeStamp = microtime(true);
		if ($cLog instanceof BundleEntity\CheckinLogs) {
			return new ModelResponse(CheckinLogs, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
		}
		$result = null;
		switch (CheckinLogs) {
			case is_numeric($cLog):
				$result = $this->em->getRepository($this->entity['cil']['name'])->findOneBy(array('id' => $cLog));
				break;
		}
		if (is_null($result)) {
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $city
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCity($city) {
		$timeStamp = microtime(true);
		if ($city instanceof BundleEntity\City) {
			return new ModelResponse($city, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
		}
		$result = null;
		switch ($city) {
			case is_numeric($city):
				$result = $this->em->getRepository($this->entity['c']['name'])->findOneBy(array('id' => $city));
				break;
			case is_string($city):
				$result = $this->em->getRepository($this->entity['c']['name'])->findOneBy(array('code' => $city));
				if (is_null($result)) {
					$response = $this->getCityByUrlKey($city);
					if (!$response->error->exist) {
						$result = $response->result->set;
					}
				}
				unset($response);
				break;
		}
		if (is_null($result)) {
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param string $urlKey
	 * @param null|mixed $language
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCityByUrlKey(string $urlKey, $language = null) {
		$timeStamp = microtime(true);
		if (!is_string($urlKey)) {
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
		$filter[] = array(
			'glue'      => 'and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array('column' => $this->entity['cl']['alias'] . '.url_key', 'comparison' => '=', 'value' => $urlKey),
				)
			)
		);
		if (!is_null($language)) {
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if (!$response->error->exist) {
				$filter[] = array(
					'glue'      => 'and',
					'condition' => array(
						array(
							'glue'      => 'and',
							'condition' => array('column' => $this->entity['cl']['alias'] . '.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
		$response = $this->listCities($filter, null, array('start' => 0, 'count' => 1));

		if ($response->error->exist) {
			return $response;
		}
		$response->result->set = $response->result->set[0];
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = microtime(true);

		return $response;
	}

	/**
	 * @param mixed $city
	 *
	 * @return array
	 */
	public function insertCity($city) {
		return $this->insertCities(array($city));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertCityLocalizations(array $collection) {
		$timeStamp = microtime(true);
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countInserts = 0;
		$insertedItems = [];
		foreach ($collection as $item) {
			if ($item instanceof BundleEntity\CityLocalization) {
				$entity = $item;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else {
				$city = $item['entity'];
				foreach ($item['localizations'] as $locale => $translation) {
					$entity = new BundleEntity\CityLocalization;
					$entity->setCity($city);
					$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
					$response = $lModel->getLanguage($locale);
					if ($response->error->exist) {
						return $response;
					}
					$entity->setLanguage($response->result->set);
					unset($response);
					foreach ($translation as $column => $value) {
						$set = 'set' . $this->translateColumnName($column);
						switch ($column) {
							default:
								if (is_object($value) || is_array($value)) {
									$value = json_encode($value);
								}
								$entity->$set($value);
								break;
						}
					}
					$this->em->persist($entity);
					$insertedItems[] = $entity;
					$countInserts++;
				}
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertCities(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = [];
		$localizations = [];
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\City) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$localizations = [];
				$entity = new BundleEntity\City;
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations[$countInserts]['localizations'] = $value;
							$localeSet = true;
							$countLocalizations++;
							break;
						case 'country':
						case 'state':
							$get = 'get' . $this->translateColumnName($column);
							$response = $this->$get($value, 'id');
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							break;
						default:
							if (property_exists($entity, $column)) {
								$entity->$set($value);
							}
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
		}

		/** Now handle localizations */
		if ($countInserts > 0 && $countLocalizations > 0) {
			$this->insertCityLocalizations($localizations);
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCities(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT ' . $this->entity['cl']['alias'] . ', ' . $this->entity['cl']['alias']
			. ' FROM ' . $this->entity['cl']['name'] . ' ' . $this->entity['cl']['alias']
			. ' JOIN ' . $this->entity['cl']['alias'] . '.city ' . $this->entity['c']['alias'];

		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'country':
					case 'state':
					case 'code':
						$column = $this->entity['c']['alias'] . '.' . $column;
						break;
					case 'language':
					case 'city':
					case 'name':
					case 'url_key':
						$column = $this->entity['cl']['alias'] . '.' . $column;
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}

		$qStr .= $wStr . $gStr . $oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();
		$unique = [];

		$entities = [];
		foreach ($result as $entry) {
			$id = $entry->getCity()->getId();
			if (!isset($unique[$id])) {
				$entities[] = $entry->getCity();
				$unique[$id] = '';
			}
		}
		unset($unique);
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}
	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listDistricts(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT ' . $this->entity['dl']['alias'] . ', ' . $this->entity['dl']['alias']
			. ' FROM ' . $this->entity['dl']['name'] . ' ' . $this->entity['dl']['alias']
			. ' JOIN ' . $this->entity['dl']['alias'] . '.district ' . $this->entity['d']['alias'];

		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'country':
					case 'state':
					case 'code':
						$column = $this->entity['c']['alias'] . '.' . $column;
						break;
					case 'language':
					case 'city':
					case 'name':
					case 'url_key':
						$column = $this->entity['cl']['alias'] . '.' . $column;
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}

		$qStr .= $wStr . $gStr . $oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();
		$unique = [];

		$entities = [];
		foreach ($result as $entry) {
			$id = $entry->getDistrict()->getId();
			if (!isset($unique[$id])) {
				$entities[] = $entry->getDistrict();
				$unique[$id] = '';
			}
		}
		unset($unique);
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}
	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listNeighborhoods(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT ' . $this->entity['nl']['alias'] . ', ' . $this->entity['nl']['alias']
			. ' FROM ' . $this->entity['nl']['name'] . ' ' . $this->entity['nl']['alias']
			. ' JOIN ' . $this->entity['nl']['alias'] . '.neighborhood ' . $this->entity['nl']['alias'];

		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'country':
					case 'state':
					case 'code':
						$column = $this->entity['c']['alias'] . '.' . $column;
						break;
					case 'language':
					case 'city':
					case 'name':
					case 'url_key':
						$column = $this->entity['cl']['alias'] . '.' . $column;
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}

		$qStr .= $wStr . $gStr . $oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();
		$unique = [];

		$entities = [];
		foreach ($result as $entry) {
			$id = $entry->getNeighborhood()->getId();
			if (!isset($unique[$id])) {
				$entities[] = $entry->getNeighborhood();
				$unique[$id] = '';
			}
		}
		unset($unique);
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}
	/**
	 * @param mixed $country
	 * @param array|null $ortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCitiesOfCountry($country, array $ortOrder = null, array $limit = null) {
		$response = $this->getCountry($country);
		if ($response->error->exist) {
			return $response;
		}
		$country = $response->result->set;
		unset($response);
		$filter[] = array(
			'glue'      => 'and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['c']['alias'] . '.country',
						'comparison' => '=', 'value' => $country->getId()),
				)
			)
		);

		return $this->listCities($filter, $ortOrder, $limit);
	}

	/**
	 * @param mixed $state
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCitiesOfState($state, array $sortOrder = null, array $limit = null) {
		$response = $this->getState($state);
		if ($response->error->exist) {
			return $response;
		}
		$state = $response->result->set;
		unset($response);
		$filter[] = array(
			'glue'      => 'and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['c']['alias'] . '.state',
						'comparison' => '=', 'value' => $state->getId()),
				)
			)
		);

		return $this->listCities($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed|$city
	 *
	 * @return array
	 */
	public function updateCity($city) {
		return $this->updateCities(array($city));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateCities(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\City) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if (!property_exists($data, 'id') || !is_numeric($data->id)) {
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				$response = $this->getCity($data->id, 'id');
				if ($response->error->exist) {
					return $response;
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = [];
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\CityLocalization();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode, 'iso_code');
									$localization->setLanguage($response['result']['set']);
									$localization->setCity($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'country':
						case 'state':
							/** State can be null */
							if ($column == 'state' && (is_null($value) || $value == -1)) {
								break;
							}
							$get = 'get' . $this->translateColumnName($column);
							$response = $this->$get($value, 'id');
							if ($response->error->exist) {
								return $response;
							}
							$oldEntity->$set($response->result->set);
							unset($response, $fModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if ($countUpdates > 0) {
			$this->em->flush();

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $country
	 *
	 * @return array
	 */
	public function deleteCountry($country) {
		return $this->deleteCountries(array($country));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCountries(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach ($collection as $entry) {
			if ($entry instanceof BundleEntity\Country) {
				$this->em->remove($entry);
				$countDeleted++;
			}
			else {
				$response = $this->getCity($entry);
				if (!$response->error->exist) {
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if ($countDeleted < 0) {
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, microtime(true));
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $country
	 * @param bool $bypass
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse|bool
	 */
	public function doesCountryExist($country, bool $bypass = false) {
		$response = $this->getCountry($country);
		$exist = true;
		if ($response->error->exist) {
			$exist = false;
			$response->result->set = false;
		}
		if ($bypass) {
			return $exist;
		}

		return $response;
	}

	/**
	 * @param mixed $country
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCountry($country) {
		$timeStamp = microtime(true);
		if ($country instanceof BundleEntity\Country) {
			return new ModelResponse($country, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
		}
		$result = null;
		switch ($country) {
			case is_numeric($country):
				$result = $this->em->getRepository($this->entity['u']['name'])->findOneBy(array('id' => $country));
				break;
			case is_string($country):
				$result = $this->em->getRepository($this->entity['u']['name'])->findOneBy(array('code' => $country));
				if (is_null($result)) {
					$response = $this->getCountryByUrlKey($country);
					if (!$response->error->exist) {
						$result = $response->result->set;
					}
				}
				unset($response);
				break;
		}
		if (is_null($result)) {
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param string $urlKey
	 * @param null|mixed   $language
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCountryByUrlKey(string $urlKey, $language = null) {
		$timeStamp = microtime(true);
		if (!is_string($urlKey)) {
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
		$filter[] = array(
			'glue'      => 'and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array('column' => $this->entity['ul']['alias'] . '.url_key', 'comparison' => '=', 'value' => $urlKey),
				)
			)
		);
		if (!is_null($language)) {
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if (!$response->error->exist) {
				$filter[] = array(
					'glue'      => 'and',
					'condition' => array(
						array(
							'glue'      => 'and',
							'condition' => array('column' => $this->entity['ul']['alias'] . '.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
		$response = $this->listCountries($filter, null, array('start' => 0, 'count' => 1));
		if ($response->error->exist) {
			return $response;
		}
		$response->result->set = $response->result->set[0];
		$response->stats->execution->start = $timeStamp;

		return $response;
	}

	/**
	 * @param mixed $country
	 *
	 * @return array
	 */
	public function insertCountry($country) {
		return $this->insertCountries(array($country));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertCountryLocalizations(array $collection) {
		$timeStamp = microtime(true);
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countInserts = 0;
		$insertedItems = [];
		foreach ($collection as $item) {
			if ($item instanceof BundleEntity\CountryLocalization) {
				$entity = $item;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else {
				$country = $item['entity'];
				foreach ($item['localizations'] as $locale => $translation) {
					$entity = new BundleEntity\CountryLocalization;
					$entity->setCountry($country);
					$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
					$response = $lModel->getLanguage($locale);
					if ($response->error->exist) {
						return $response;
					}
					$entity->setLanguage($response->result->set);
					unset($response);
					foreach ($translation as $column => $value) {
						$set = 'set' . $this->translateColumnName($column);
						switch ($column) {
							default:
								if (is_object($value) || is_array($value)) {
									$value = json_encode($value);
								}
								$entity->$set($value);
								break;
						}
					}
					$this->em->persist($entity);
					$insertedItems[] = $entity;
					$countInserts++;
				}
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertCountries(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = [];
		$localizations = [];
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Country) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$localizations = [];
				$entity = new BundleEntity\Country;
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations[$countInserts]['localizations'] = $value;
							$localeSet = true;
							$countLocalizations++;
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
		}
		/** Now handle localizations */
		if ($countInserts > 0 && $countLocalizations > 0) {
			$this->insertCountryLocalizations($localizations);
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCountries(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT ' . $this->entity['ul']['alias'] . ', ' . $this->entity['u']['alias']
			. ' FROM ' . $this->entity['ul']['name'] . ' ' . $this->entity['ul']['alias']
			. ' JOIN ' . $this->entity['ul']['alias'] . '.country ' . $this->entity['u']['alias'];

		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'country':
					case 'state':
					case 'code':
						$column = $this->entity['u']['alias'] . '.' . $column;
						break;
					case 'name':
					case 'url_key':
						$column = $this->entity['ul']['alias'] . '.' . $column;
						break;
					default:
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}

		$qStr .= $wStr . $gStr . $oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);
		$result = $q->getResult();

		$entities = [];
		foreach ($result as $entry) {
			$id = $entry->getCountry()->getId();
			if (!isset($unique[$id])) {
				$entities[] = $entry->getCountry();
				$unique[$id] = '';
			}
		}
		unset($unique);
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $country
	 *
	 * @return array
	 */
	public function updateCountry($country) {
		return $this->updateCountries(array($country));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateCountries(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Country) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if (!property_exists($data, 'id') || !is_numeric($data->id)) {
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				$response = $this->getCountry($data->id);
				if ($response->error->exist) {
					return $response;
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = [];
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\Country();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode);
									if ($response->error->exist) {
										return $response;
									}
									$localization->setLanguage($response->result->set);
									unset($response);
									$localization->setCountry($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if ($countUpdates > 0) {
			$this->em->flush();

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $state
	 *
	 * @return array
	 */
	public function deleteState($state) {
		return $this->deleteStates(array($state));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteStates(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach ($collection as $entry) {
			if ($entry instanceof BundleEntity\State) {
				$this->em->remove($entry);
				$countDeleted++;
			}
			else {
				$response = $this->getState($entry);
				if (!$response->error->exist) {
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if ($countDeleted < 0) {
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, microtime(true));
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $state
	 * @param bool $bypass
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse|bool
	 */
	public function doesStateExist($state, bool $bypass = false) {
		$response = $this->getState($state);
		$exist = true;
		if ($response->error->exist) {
			$exist = false;
			$response->result->set = false;
		}
		if ($bypass) {
			return $exist;
		}

		return $response;
	}

	/**
	 * @param mixed $state
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getState($state) {
		$timeStamp = microtime(true);
		if ($state instanceof BundleEntity\State) {
			return new ModelResponse($state, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
		}
		$result = null;
		switch ($state) {
			case is_numeric($state):
				$result = $this->em->getRepository($this->entity['s']['name'])->findOneBy(array('id' => $state));
				break;
			case is_string($state):
				$result = $this->em->getRepository($this->entity['s']['name'])->findOneBy(array('code_iso' => $state));
				if (is_null($result)) {
					$response = $this->getStateByUrlKey($state);
					if (!$response->error->exist) {
						$result = $response->result->set;
					}
				}
				unset($response);
				break;
		}
		if (is_null($result)) {
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param string $urlKey
	 * @param null|mixed   $language
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getStateByUrlKey(string $urlKey, $language = null) {
		$timeStamp = microtime(true);
		if (!is_string($urlKey)) {
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
		$filter[] = array(
			'glue'      => 'and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array('column' => $this->entity['sl']['alias'] . '.url_key', 'comparison' => '=', 'value' => $urlKey),
				)
			)
		);
		if (!is_null($language)) {
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if (!$response->error->exist) {
				$filter[] = array(
					'glue'      => 'and',
					'condition' => array(
						array(
							'glue'      => 'and',
							'condition' => array('column' => $this->entity['sl']['alias'] . '.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
		$response = $this->listStates($filter, null, array('start' => 0, 'count' => 1));

		return $response;
	}

	/**
	 * @param $mixed state
	 *
	 * @return array
	 */
	public function insertState($state) {
		return $this->insertStates(array($state));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertStates(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = [];
		$localizations = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\State) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$localizations = [];
				$entity = new BundleEntity\State;
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations[$countInserts]['localizations'] = $value;
							$localeSet = true;
							$countLocalizations++;
							break;
						case 'country':
							$response = $this->getCountry($value);
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							unset($response);
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
		}
		/** Now handle localizations */
		if ($countInserts > 0 && $countLocalizations > 0) {
			$this->insertStateLocalizations($localizations);
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertStateLocalizations(array $collection) {
		$timeStamp = microtime(true);
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countInserts = 0;
		$insertedItems = [];
		foreach ($collection as $item) {
			if ($item instanceof BundleEntity\StateLocalization) {
				$entity = $item;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else {
				$state = $item['entity'];
				foreach ($item['localizations'] as $locale => $translation) {
					$entity = new BundleEntity\StateLocalization;
					$entity->setState($state);
					$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
					$response = $lModel->getLanguage($locale);
					if ($response->error->exist) {
						return $response;
					}
					$entity->setLanguage($response->result->set);
					unset($response);
					foreach ($translation as $column => $value) {
						$set = 'set' . $this->translateColumnName($column);
						switch ($column) {
							default:
								if (is_object($value) || is_array($value)) {
									$value = json_encode($value);
								}
								$entity->$set($value);
								break;
						}
					}
					$this->em->persist($entity);
					$insertedItems[] = $entity;
					$countInserts++;
				}
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogs(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';
		$qStr = 'SELECT ' . $this->entity['cil']['alias']
			. ' FROM ' . $this->entity['cil']['name'] . ' ' . $this->entity['cil']['alias'];

		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'date_checkin':
					case 'date_checkout':
					case 'lat_checkin':
					case 'lon_checkin':
					case 'lat_checkout':
					case 'lon_checkout':
					case 'date_added':
					case 'date_updated':
					case 'date_removed':
						$column = $this->entity['cil']['alias'] . '.' . $column;
						break;
					default:
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}
		$qStr .= $wStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);
		$result = $q->getResult();

		unset($unique);
		$totalRows = count($result);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param float      $lat
	 * @param float      $lon
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsByCheckinCoordinates(float $lat, float $lon, array $sortOrder = null, array $limit = null) {
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.lat_checkin',
						'comparison' => '=',
						'value'      => $lat
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.lon_checkin',
						'comparison' => '=',
						'value'      => $lon
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param float      $lat
	 * @param float      $lon
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsByCheckoutCoordinates (float $lat, float $lon, array $sortOrder = null, array $limit = null) {
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.lat_checkout',
						'comparison' => '=',
						'value'      => $lat
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.lon_checkout',
						'comparison' => '=',
						'value'      => $lon
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $member
	 * @param mixed $office
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfMemberByOffice ($member, $office, array $sortOrder = null, array $limit = null) {
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.office',
						'comparison' => '=',
						'value'      => $office->getId()
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $office
	 * @param mixed $member
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfOfficeByMember ($office, $member, array $sortOrder = null, array $limit = null) {
		return $this->listCheckinLogsOfMemberByOffice($member, $office, $sortOrder, $limit);
	}

	/**
	 * @param mixed $member
	 * @param mixed $office
	 * @param array      $dateRange
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfOfficeByMemberCheckedInDuring ($member, $office, array $dateRange = [], array $sortOrder = null, array $limit = null) {
		$timeStamp = micromicrotime(true);
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
			}
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		switch(count($dateRange)){
			case 0:
				$dateEnd = $now;
				$dateStart = $now->modify('-1 month');
				break;
			case 1:
				$dateEnd = $dateRange[0];
				$dateStart = $dateRange[0]->modify('-1 month');
				break;
			case 2:
				$dateStart = $dateRange[0];
				$dateEnd = $dateRange[1];
				break;
			default:
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
				break;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.office',
						'comparison' => '=',
						'value'      => $office->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkin',
						'comparison' => '>=',
						'value'      => $dateStart
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkin',
						'comparison' => '<=',
						'value'      => $dateEnd
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $member
	 * @param mixed $office
	 * @param array      $dateRange
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfOfficeByMemberCheckedOutDuring ($member, $office, array $dateRange = [], array $sortOrder = null, array $limit = null) {
		$timeStamp = micromicrotime(true);
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
			}
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		switch(count($dateRange)){
			case 0:
				$dateEnd = $now;
				$dateStart = $now->modify('-1 month');
				break;
			case 1:
				$dateEnd = $dateRange[0];
				$dateStart = $dateRange[0]->modify('-1 month');
				break;
			case 2:
				$dateStart = $dateRange[0];
				$dateEnd = $dateRange[1];
				break;
			default:
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
				break;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.office',
						'comparison' => '=',
						'value'      => $office->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '>=',
						'value'      => $dateStart
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '<=',
						'value'      => $dateEnd
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}
	/**
	 * @param mixed $office
	 * @param array      $dateRange
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfOfficeCheckedInDuring($office, array $dateRange = [], array $sortOrder = null, array $limit = null) {
		$timeStamp = micromicrotime(true);
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
			}
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		switch(count($dateRange)){
			case 0:
				$dateEnd = $now;
				$dateStart = $now->modify('-1 month');
				break;
			case 1:
				$dateEnd = $dateRange[0];
				$dateStart = $dateRange[0]->modify('-1 month');
				break;
			case 2:
				$dateStart = $dateRange[0];
				$dateEnd = $dateRange[1];
				break;
			default:
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
				break;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.office',
						'comparison' => '=',
						'value'      => $office->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkin',
						'comparison' => '>=',
						'value'      => $dateStart
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkin',
						'comparison' => '<=',
						'value'      => $dateEnd
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $member
	 * @param array      $dateRange
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfMemberCheckedOutDuring ($member, array $dateRange = [], array $sortOrder = null, array $limit = null) {
		$timeStamp = micromicrotime(true);
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
			}
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		switch(count($dateRange)){
			case 0:
				$dateEnd = $now;
				$dateStart = $now->modify('-1 month');
				break;
			case 1:
				$dateEnd = $dateRange[0];
				$dateStart = $dateRange[0]->modify('-1 month');
				break;
			case 2:
				$dateStart = $dateRange[0];
				$dateEnd = $dateRange[1];
				break;
			default:
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
				break;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '>=',
						'value'      => $dateStart
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '<=',
						'value'      => $dateEnd
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param            $office
	 * @param array      $dateRange
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfOfficeCheckedOutDuring($office, array $dateRange = [], array $sortOrder = null, array $limit = null) {
		$timeStamp = micromicrotime(true);
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
			}
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		switch(count($dateRange)){
			case 0:
				$dateEnd = $now;
				$dateStart = $now->modify('-1 month');
				break;
			case 1:
				$dateEnd = $dateRange[0];
				$dateStart = $dateRange[0]->modify('-1 month');
				break;
			case 2:
				$dateStart = $dateRange[0];
				$dateEnd = $dateRange[1];
				break;
			default:
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
				break;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.office',
						'comparison' => '=',
						'value'      => $office->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '>=',
						'value'      => $dateStart
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '<=',
						'value'      => $dateEnd
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $member
	 * @param array      $dateRange
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfMemberCheckedInDuring ($member, array $dateRange = [], array $sortOrder = null, array $limit = null) {
		$timeStamp = micromicrotime(true);
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
			}
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		switch(count($dateRange)){
			case 0:
				$dateEnd = $now;
				$dateStart = $now->modify('-1 month');
				break;
			case 1:
				$dateEnd = $dateRange[0];
				$dateStart = $dateRange[0]->modify('-1 month');
				break;
			case 2:
				$dateStart = $dateRange[0];
				$dateEnd = $dateRange[1];
				break;
			default:
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, micromicrotime(true));
				break;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '>=',
						'value'      => $dateStart
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.date_checkout',
						'comparison' => '<=',
						'value'      => $dateEnd
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $member
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfMember($member, array $sortOrder = null, array $limit = null) {
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $office
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listCheckinLogsOfOffice($office, array $sortOrder = null, array $limit = null) {
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}

		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['cil']['alias'] . '.office',
						'comparison' => '=',
						'value'      => $office->getId()
					)
				)
			)
		);

		return $this->listCheckinLogs($filter, $sortOrder, $limit);
	}

	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listStates(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';
		$qStr = 'SELECT ' . $this->entity['sl']['alias']
			. ' FROM ' . $this->entity['sl']['name'] . ' ' . $this->entity['sl']['alias']
			. ' JOIN ' . $this->entity['sl']['alias'] . '.state ' . $this->entity['s']['alias'];

		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'country':
					case 'code':
						$column = $this->entity['s']['alias'] . '.' . $column;
						break;
					case 'name':
					case 'url_key':
						$column = $this->entity['sl']['alias'] . '.' . $column;
						break;
					default:
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}
		$qStr .= $wStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);
		$result = $q->getResult();

		$entities = [];
		foreach ($result as $entry) {
			$id = $entry->getState()->getId();
			if (!isset($unique[$id])) {
				$entities[] = $entry->getState();
				$unique[$id] = '';
			}
		}
		unset($unique);
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $country
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listStatesOfCountry($country, array $sortOrder = null, array $limit = null) {
		$response = $this->getCountry($country);
		if ($response->error->exist) {
			return $response;
		}
		$country = $response->result->set;
		unseT($response);
		$filter = [];
		$filter[] = array(
			'glue'      => 'and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['s']['alias'] . '.country',
						'comparison' => '=', 'value' => $country->getId()),
				)
			)
		);

		return $this->listStates($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $cLog
	 *
	 * @return array
	 */
	public function updateCheckinLog($cLog) {
		return $this->updateCheckinLogs(array($cLog));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateCheckinLogs(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\CheckinLogs) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if (!property_exists($data, 'id') || !is_numeric($data->id)) {
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				if (!property_exists($data, 'date_updated')) {
					$data->date_updated = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
				}
				if (property_exists($data, 'date_added')) {
					unset($data->date_added);
				}
				$response = $this->getCheckinLog($data->id);
				if ($response->error->exist) {
					return $response;
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'member':
							$mModel = $this->kernel->getContainer()->get('membermanagement.model');
							$response = $mModel->getMember($value, 'id');
							if (!$response->error->exist) {
								return $response;
							}
							$oldEntity->$set($response->result->set);
							unset($response, $sModel);
							break;
						case 'office':
							$get = 'get' . $this->translateColumnName($column);
							$response = $this->$get($value);
							if (!$response->error->exist) {
								return $response;
							}
							$oldEntity->$set($response->result->set);
							unset($response, $sModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if ($countUpdates > 0) {
			$this->em->flush();

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $state
	 *
	 * @return array
	 */
	public function updateState($state) {
		return $this->updateStates(array($state));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateStates(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\State) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if (!property_exists($data, 'id') || !is_numeric($data->id)) {
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				$response = $this->getCountry($data->id);
				if ($response->error->exist) {
					return $response;
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = [];
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\StateLocalization();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode);
									if ($response->error->exist) {
										return $response;
									}
									$localization->setLanguage($response->result->set);
									unset($response);
									$localization->setCountry($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'country':
							$response = $this->getCountry($value, 'id');
							if ($response->error->exist) {
								return $response;
							}
							$oldEntity->$set($oldEntity);
							unset($response, $fModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if ($countUpdates > 0) {
			$this->em->flush();
		}
		if ($countUpdates > 0) {
			$this->em->flush();

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array $collection
	 *
	 * @return array
	 */
	public function deleteOffice(array $collection) {
		return $this->deleteOffices(array($collection));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteOffices(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach ($collection as $office) {
			if ($office instanceof BundleEntity\State) {
				$this->em->remove($office);
				$countDeleted++;
			}
			else {
				$response = $this->getOffice($office);
				if (!$response->error->exist) {
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if ($countDeleted < 0) {
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, microtime(true));
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $office
	 * @param bool $bypass
	 *
	 * @return array|bool
	 */
	public function doesOfficeExist($office, bool $bypass = false) {
		$response = $this->getOffice($office);
		$exist = true;
		if ($response->error->exist) {
			$exist = false;
			$response->result->set = false;
		}
		if ($bypass) {
			return $exist;
		}

		return $response;
	}

	/**
	 * @param mixed $office
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */

	public function getOffice($office) {
		$timeStamp = microtime(true);
		if ($office instanceof BundleEntity\Office) {
			return new ModelResponse($office, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
		}
		$result = null;
		switch ($office) {
			case is_numeric($office):
				$result = $this->em->getRepository($this->entity['o']['name'])->findOneBy(array('id' => $office));
				break;
			case is_string($office):
				$result = $this->em->getRepository($this->entity['o']['name'])->findOneBy(array('url_key' => $office));
				unset($response);
				break;
		}
		if (is_null($result)) {
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed  $cLog
	 *
	 * @return array
	 */
	public function insertCheckinLog($cLog) {
		return $this->insertCheckinLogs(array($cLog));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertCheckinLogs(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\CheckinLogs) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$entity = new BundleEntity\CheckinLogs();
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'memner':
							$mModel = $this->kernel->getContainer()->get('membermanagement.model');
							$response = $mModel->getMember($value, 'id');
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							/** Free up some memory */
							unset($response, $SMModel);
							break;
						case 'office':
							$response = $this->getOffice($value);
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							unset($response, $SMModel);
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param mixed $office
	 *
	 * @return array
	 */
	public function insertOffice($office) {
		return $this->insertOffices(array($office));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertOffices(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Office) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$entity = new BundleEntity\Office;
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'site':
							$SMModel = new SMMService\SiteManagementModel($this->kernel, $this->dbConnection, $this->orm);
							$response = $SMModel->getSite($value);
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							/** Free up some memory */
							unset($response, $SMModel);
							break;
						case 'city':
							$response = $this->getCity($value);
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							unset($response, $SMModel);
							break;
						case 'country':
							$response = $this->getCountry($value);
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							unset($response, $SMModel);
							break;
						case 'state':
							$response = $this->getState($value);
							if ($response->error->exist) {
								return $response;
							}
							$entity->$set($response->result->set);
							unset($response, $SMModel);
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, microtime(true));
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, microtime(true));
	}

	/**
	 * @param array|null $filter
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOffices(array $filter = null, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if (!is_array($sortOrder) && !is_null($sortOrder)) {
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT ' . $this->entity['o']['alias']
			. ' FROM ' . $this->entity['o']['name'] . ' ' . $this->entity['o']['alias'];
		if (!is_null($sortOrder)) {
			foreach ($sortOrder as $column => $direction) {
				switch ($column) {
					case 'id':
					case 'country':
					case 'state':
					case 'url_key':
					case 'lat':
					case 'lon':
					case 'phone':
					case 'fax':
					case 'email':
					case 'name':
					case 'member':
						$column = $this->entity['o']['alias'] . '.' . $column;
						break;
					default:
						break;
				}
				$oStr .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
		}

		if (!is_null($filter)) {
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE ' . $fStr;
		}
		$qStr .= $wStr . $gStr . $oStr;

		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);
		$result = $q->getResult();
		$totalRows = count($result);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, microtime(true));

	}

	/**
	 * @param float $lat
	 * @param float $lon
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesByCoordinates(float $lat, float $lon) {
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.lat',
						'comparison' => '=',
						'value'      => $lat
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.lon',
						'comparison' => '=',
						'value'      => $lon
					)
				)
			)
		);

		return $this->listOffices($filter);
	}

	/**
	 * @param array      $coordinates
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesWithinCoordinates(array $coordinates, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		if(!isset($coordinates['from']) || !isset($coordinates['to'])){
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'Invalid coordinate', $timeStamp, microtime(true));
		}
		$coordinateFrom = $coordinates['from'];
		$coordinateTo = $coordinates['to'];
		unset($coordinates);
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.lat',
						'comparison' => '<',
						'value'      => $coordinateFrom->lat
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.lon',
						'comparison' => '>',
						'value'      => $coordinateFrom->lon
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.lat',
						'comparison' => '>',
						'value'      => $coordinateTo->lat
					)
				),
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.lon',
						'comparison' => '<',
						'value'      => $coordinateTo->lon
					)
				)
			)
		);

		return $this->listOffices($filter, $sortOrder, $limit);
	}

	/**
	 * @param string $type
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesByType(string $type) {
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.type',
						'comparison' => '=',
						'value'      => $type
					)
				)
			)
		);

		return $this->listOffices($filter);
	}

	/**
	 * @param string $name
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesByName(string $name) {
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.name',
						'comparison' => 'contains',
						'value'      => $name
					)
				)
			)
		);

		return $this->listOffices($filter);
	}

	/**
	 * @param array      $cities
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesInCities(array $cities, array $sortOrder = null, array $limit = null) {
		$timeStamp = microtime(true);
		$in = [];
		foreach($cities as $city){
			$response = $this->getCity($city);
			if(!$response->error->exist){
				$city = $response->result->set;
				$in[] = $city->getId();
			}
		}
		unset($response);
		if(count($in) < 1){
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, microtime(true));
		}

		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.city',
						'comparison' => 'in',
						'value'      => $in
					)
				)
			)
		);

		return $this->listOffices($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $city
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesInCity($city, array $sortOrder = null, array $limit = null) {
		$response = $this->getCity($city);
		if ($response->error->exist) {
			return $response;
		}
		$city = $response->result->set;
		unset($response);
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.city',
						'comparison' => '=',
						'value'      => $city->getId()
					)
				)
			)
		);
		return $this->listOffices($filter, $sortOrder, $limit);
	}

	/**
	 * @param            $member
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesOfMember($member, array $sortOrder = null, array $limit = null) {
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		/**
		 * ModelResponse
		 */
		$response = $mModel->getMember($member);
		if ($response->error->exist) {
			return $response;
		}
		$member = $response->result->set;
		unset($response);
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.member',
						'comparison' => '=',
						'value'      => $member->getId()
					)
				)
			)
		);
		return $this->listOffices($filter, $sortOrder, $limit);
	}
	/**
	 * @param mixed $country
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesInCountry($country, array $sortOrder = null, array $limit = null) {
		$response = $this->getCountry($country);
		if ($response->error->exist) {
			return $response;
		}
		$country = $response->result->set;
		unset($response);
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.country',
						'comparison' => '=',
						'value'      => $country->getId()
					)
				)
			)
		);

		return $this->listOffices($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $state
	 * @param array|null $sortOrder
	 * @param array|null $limit
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesInState($state, array $sortOrder = null, array $limit = null) {
		$response = $this->getState($state);
		if ($response->error->exist) {
			return $response;
		}
		$state = $response->result->set;
		unset($response);
		$filter[] = array(
			'glue'      => ' and',
			'condition' => array(
				array(
					'glue'      => 'and',
					'condition' => array(
						'column'     => $this->entity['o']['alias'] . '.state',
						'comparison' => '=',
						'value'      => $state->getId()
					)
				)
			)
		);

		return $this->listOffices($filter, $sortOrder, $limit);
	}

	/**
	 * @param mixed $office
	 *
	 * @return array
	 */
	public function updateOffice($office) {
		return $this->updateOffices(array($office));
	}

	/**
	 * @param array $collection
	 *
	 * @return \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateOffices(array $collection) {
		$timeStamp = microtime(true);
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = [];
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Office) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if (!property_exists($data, 'id') || !is_numeric($data->id)) {
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				if (!property_exists($data, 'date_updated')) {
					$data->date_updated = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
				}
				if (property_exists($data, 'date_added')) {
					unset($data->date_added);
				}
				if (!property_exists($data, 'site')) {
					$data->site = 1;
				}
				$response = $this->getOffice($data->id);
				if ($response->error->exist) {
					return $response;
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value, 'id');
							if (!$response->error->exist) {
								return $response;
							}
							$oldEntity->$set($response->result->set);
							unset($response, $sModel);
							break;
						case 'city':
						case 'state':
						case 'country':
							$get = 'get' . $this->translateColumnName($column);
							$response = $this->$get($value);
							if (!$response->error->exist) {
								return $response;
							}
							$oldEntity->$set($response->result->set);
							unset($response, $sModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if ($countUpdates > 0) {
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, microtime(true));
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, microtime(true));
	}
}

<?php
/**
 * @package	    LocationManagementBundle
 * @subpackage	Services
 * @name	    LocationManagementModel
 *
 * @author      Can Berkol
 * @author      Said İmamoğlu
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.1.2
 *
 * @date        01.11.2015
 */

namespace BiberLtd\Bundle\LocationManagementBundle\Services;

/** Extends CoreModel */
use BiberLtd\Bundle\CoreBundle\CoreModel;
/** Entities to be used */
use BiberLtd\Bundle\CoreBundle\Responses\ModelResponse;
use BiberLtd\Bundle\LocationManagementBundle\Entity as BundleEntity;
/** Helper Models */
use BiberLtd\Bundle\PhpOrientBundle\Odm\Types\DateTime;
use BiberLtd\Bundle\SiteManagementBundle\Services as SMMService;
use BiberLtd\Bundle\MultiLanguageSupportBundle\Entity as MLSEntity;
/** Core Service */
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
			'm' => array('name' => 'MemberManagementBundle:Member', 'alias' => 'm'),
			'o'  => array('name' => 'LocationManagementBundle:Office', 'alias' => 'o'),
			's'  => array('name' => 'LocationManagementBundle:State', 'alias' => 's'),
			'sl' => array('name' => 'LocationManagementBundle:StateLocalization', 'alias' => 'sl'),
			'u'  => array('name' => 'LocationManagementBundle:Country', 'alias' => 'u'),
			'ul' => array('name' => 'LocationManagementBundle:CountryLocalization', 'alias' => 'ul'),
		);
	}

	/**
	 * @name            __destruct ()
	 *
	 * @author          Said İmamoğlu
	 *
	 * @since           1.0.0
	 * @version         1.0.0
	 *
	 */
	public function __destruct() {
		foreach ($this as $property => $value) {
			$this->$property = null;
		}
	}

	/**
	 * @name            deleteCheckinLog()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed   $cLog
	 *
	 * @return          array   $response
	 *
	 */
	public function deleteCheckinLog($cLog) {
		return $this->deleteCheckinLogs(array($cLog));
	}

	/**
	 * @name            deleteCheckinLogs()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @use             $this->createException()
	 *
	 * @param           array       $collection
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCheckinLogs($collection) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}
	/**
	 * @name            deleteCity ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $city
	 *
	 * @return          array   $response
	 *
	 */
	public function deleteCity($city) {
		return $this->deleteCities(array($city));
	}

	/**
	 * @name                  deleteCities ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @use             $this->createException()
	 *
	 * @param           array $collection
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteCities($collection) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

	/**
	 * @name                  doesCityExist ()
	 *
	 * @since           1.0.0
	 * @version         1.0.0
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->getCity()
	 *
	 * @param           mixed $city
	 * @param           bool  $bypass
	 *
	 * @return          array   $response
	 *
	 */
	public function doesCityExist($city, $bypass = false) {
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
	 * @name            getCheckinLog()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed       $cLog
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCheckinLog($cLog) {
		$timeStamp = time();
		if ($cLog instanceof BundleEntity\CheckinLogs) {
			return new ModelResponse(CheckinLogs, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch (CheckinLogs) {
			case is_numeric($cLog):
				$result = $this->em->getRepository($this->entity['cil']['name'])->findOneBy(array('id' => $cLog));
				break;
		}
		if (is_null($result)) {
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            getCity ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $city
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCity($city) {
		$timeStamp = time();
		if ($city instanceof BundleEntity\City) {
			return new ModelResponse($city, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
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
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name                   getCityByUrlKey ()
	 *
	 * @since           1.0.6
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 *
	 * @param           mixed  $urlKey
	 * @param            mixed $language
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCityByUrlKey($urlKey, $language = null) {
		$timeStamp = time();
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
		$response->stats->execution->end = time();

		return $response;
	}

	/**
	 * @name                  insertCity ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->insertCities()
	 *
	 * @param           mixed $city
	 *
	 * @return          array   $response
	 *
	 */
	public function insertCity($city) {
		return $this->insertCities(array($city));
	}

	/**
	 * @name                  insertCityLocalizations ()
	 *
	 * @since           1.0.3
	 * @version         1.0.6
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array $collection
	 *
	 * @return          array           $response
	 */
	public function insertCityLocalizations($collection) {
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countInserts = 0;
		$insertedItems = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name                  insertCities ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           array $collection
	 *
	 * @return          array   $response
	 *
	 */
	public function insertCities($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = array();
		$localizations = array();
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\City) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$localizations = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            listCities ()
	 *
	 * @since           1.0.0
	 * @version         1.0.7
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           array $filter    Multi dimensional array
	 * @param           array $sortOrder 'coloumn' => 'asc|desc'
	 * @param           array $limit     start,count
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 *
	 */
	public function listCities($filter = null, $sortOrder = null, $limit = null, $query_str = null, $returnLocal = false) {
		$timeStamp = time();
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
		$unique = array();

		$entities = array();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());

	}

	/**
	 * @name            listCitiesOfCountry ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 * @use             $this->listCities()
	 *
	 * @param           mixed $country
	 * @param           array $ortOrder
	 * @param           array $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCitiesOfCountry($country, $ortOrder = null, $limit = null) {
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
	 * @name                  listCitiesOfState ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 * @use             $this->listCities()
	 *
	 * @param           mixed $state
	 * @param           array $ortOrder
	 * @param           array $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCitiesOfState($state, $ortOrder = null, $limit = null) {
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

		return $this->listCities($filter, $ortOrder, $limit);
	}

	/**
	 * @name                  updateCity ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->updateCities()
	 *
	 * @param           mixed $city
	 *
	 * @return          array   $response
	 *
	 */
	public function updateCity($city) {
		return $this->updateCities(array($city));
	}

	/**
	 * @name                  updateCities ()
	 *                                     Updates one or more cities from database.
	 *
	 * @since           1.0.0
	 * @version         1.0.3
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $collection  Entity or post data
	 *
	 * @return          array   $response
	 *
	 */
	public function updateCities($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
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
							$localizations = array();
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

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}

	/**
	 * @name                  deleteCountry ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $country
	 *
	 * @return          array   $response
	 *
	 */

	public function deleteCountry($country) {
		return $this->deleteCountries(array($country));
	}

	/**
	 * @name                  deleteCities ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @param           mixed $collection  Entity or post data
	 *
	 * @return          array   $response
	 *
	 */
	public function deleteCountries($collection) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

	/**
	 * @name                  doesCountryExist ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $country
	 * @param           bool  $bypass
	 *
	 * @return          array   $response
	 *
	 */
	public function doesCountryExist($country, $bypass = false) {
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
	 * @name                  getCountry ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $country
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCountry($country) {
		$timeStamp = time();
		if ($country instanceof BundleEntity\Country) {
			return new ModelResponse($country, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
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
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name                   getCountryByUrlKey ()
	 *
	 * @since           1.0.6
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 *
	 * @param           mixed  $urlKey
	 * @param            mixed $language
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getCountryByUrlKey($urlKey, $language = null) {
		$timeStamp = time();
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
	 * @name                  insertCountry ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->insertCountries()
	 *
	 * @param           mixed $country
	 *
	 * @return          array   $response
	 *
	 */

	public function insertCountry($country) {
		return $this->insertCountries(array($country));
	}

	/**
	 * @name                  insertCountryLocalizations ()
	 *
	 * @since           1.0.3
	 * @version         1.0.6
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array $collection
	 *
	 * @return          array           $response
	 */
	public function insertCountryLocalizations($collection) {
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countInserts = 0;
		$insertedItems = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name                  insertCountries ()
	 *                                        Inserts one or more country into database
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $collection     Entity or post data
	 *
	 * @return          array   $response
	 */
	public function insertCountries($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = array();
		$localizations = array();
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Country) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$localizations = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name                  listCountries ()
	 *                                      Lists countries from database
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 * @use             $this->addLimit()
	 *
	 *
	 * @param           array $filter
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array   $response
	 *
	 */

	public function listCountries($filter = null, $sortOrder = null, $limit = null, $query_str = null, $returnLocal = false) {
		$timeStamp = time();
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

		$entities = array();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name                  updateCountry ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Said İmamoğlu
	 * @author          Can Berkol
	 *
	 * @use             $this->updateCountries()
	 *
	 * @param           mixed $country
	 *
	 * @return          array   $response
	 *
	 */

	public function updateCountry($country) {
		return $this->updateCountries(array($country));
	}

	/**
	 * @name                  updateCountries ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $collection     Entity or post data
	 *
	 * @return          array   $response
	 *
	 */
	public function updateCountries($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
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
							$localizations = array();
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

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}

	/**
	 * @name                  deleteState ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->deleteStates()
	 *
	 * @param           mixed $state
	 *
	 * @return          array   $response
	 *
	 */
	public function deleteState($state) {
		return $this->deleteStates(array($state));
	}

	/**
	 * @name                  deleteStates ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 * @use             $this->doesStateExist()
	 *
	 *
	 * @param           mixed $collection  Entity or post date
	 *
	 * @return          array   $response
	 *
	 */

	public function deleteStates($collection) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

	/**
	 * @name                  doesStateExist ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $state
	 * @param           bool  $bypass
	 *
	 * @return          array   $response
	 *
	 */
	public function doesStateExist($state, $bypass = false) {
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
	 * @name                  getState ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $state
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getState($state) {
		$timeStamp = time();
		if ($state instanceof BundleEntity\State) {
			return new ModelResponse($state, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
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
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name                   getStateByUrlKey ()
	 *
	 * @since           1.0.6
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 *
	 * @param           mixed  $urlKey
	 * @param            mixed $language
	 *
	 * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getStateByUrlKey($urlKey, $language = null) {
		$timeStamp = time();
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
	 * @name                  insertState ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->insertStates()
	 *
	 * @param           mixed $state
	 *
	 * @return          array   $response
	 *
	 */

	public function insertState($state) {
		return $this->insertStates(array($state));
	}

	/**
	 * @name                  insertStates ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $collection
	 *
	 * @return          array   $response
	 *
	 */
	public function insertStates($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = array();
		$localizations = array();
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\State) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$localizations = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name                  insertStateLocalizations ()
	 *
	 * @since           1.0.3
	 * @version         1.0.6
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array $collection
	 *
	 * @return          array           $response
	 */
	public function insertStateLocalizations($collection) {
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countInserts = 0;
		$insertedItems = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}
	/**
	 * @name            listCheckinLogs()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @param           array   $filter
	 * @param           array   $sortOrder
	 * @param           array   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogs($filter = null, $sortOrder = null, $limit = null) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}

		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
	/**
	 * @name            listCheckinLogsByCheckinCoordinates ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           decimal $lat                     X Coordinate
	 * @param           decimal $lon                     Y Coordinate
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsByCheckinCoordinates($lat, $lon, $sortOrder = null, $limit = null) {
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
	 * @name            listCheckinLogsByCheckoutCoordinates ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           decimal $lat                     X Coordinate
	 * @param           decimal $lon                     Y Coordinate
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsByCheckoutCoordinates ($lat, $lon, $sortOrder = null, $limit = null) {
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
	 * @name            listCheckinLogsOfMemberByOffice ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $member
	 * @param           mixed   $office
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfMemberByOffice ($member, $office, $sortOrder = null, $limit = null) {
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
	 * @name            listCheckinLogsOfOfficeByMember ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $office
	 * @param           mixed   $member
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfOfficeByMember ($office, $member, $sortOrder = null, $limit = null) {
		return $this->listCheckinLogsOfMemberByOffice($member, $office, $sortOrder, $limit);
	}
	/**
	 * @name            listCheckinLogsOfOfficeByMemberCheckedInDuring ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $member
	 * @param           mixed   $office
	 * @param           array   $dateRange
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfOfficeByMemberCheckedInDuring ($member, $office, array $dateRange = array(), $sortOrder = null, $limit = null) {
		$timeStamp = microtime();
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, microtime());
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, microtime());
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
	 * @name            listCheckinLogsOfOfficeByMemberCheckedInDuring ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $member
	 * @param           mixed   $office
	 * @param           array   $dateRange
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfOfficeByMemberCheckedOutDuring ($member, $office, array $dateRange = array(), $sortOrder = null, $limit = null) {
		$timeStamp = microtime();
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, microtime());
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, microtime());
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
	 * @name            listCheckinLogsOfOfficeCheckedInDuring ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $office
	 * @param           array   $dateRange
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfOfficeCheckedInDuring($office, array $dateRange = array(), $sortOrder = null, $limit = null) {
		$timeStamp = microtime();
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, microtime());
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, microtime());
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
	 * @name            listCheckinLogsOfMemberCheckedInDuring ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $member
	 * @param           array   $dateRange
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfMemberCheckedOutDuring ($member, array $dateRange = array(), $sortOrder = null, $limit = null) {
		$timeStamp = microtime();
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, microtime());
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, microtime());
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
	 * @name            listCheckinLogsOfOfficeCheckedInDuring ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $office
	 * @param           array   $dateRange
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfOfficeCheckedOutDuring($office, array $dateRange = array(), $sortOrder = null, $limit = null) {
		$timeStamp = microtime();
		$response = $this->getOffice($office);
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, microtime());
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, microtime());
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
	 * @name            listCheckinLogsOfMemberCheckedInDuring ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $member
	 * @param           array   $dateRange
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfMemberCheckedInDuring ($member, array $dateRange = array(), $sortOrder = null, $limit = null) {
		$timeStamp = microtime();
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member, 'id');
		if($response->error->exist){
			return $response;
		}
		foreach($dateRange as $dateTimeObj){
			if(!$dateTimeObj instanceof \DateTime){
				return new ModelResponse(null, 0, 0, null, true, 'E:T:001', 'DateTime object is exptected.', $timeStamp, microtime());
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
				return new ModelResponse(null, 0, 0, null, true, 'E:T:002', 'DateTime object is exptected.', $timeStamp, microtime());
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
	 * @name            listCheckinLogsOfMemberByOffice ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $member
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfMember($member, $sortOrder = null, $limit = null) {
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
	 * @name            listCheckinLogsOfOffice ()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listCheckinLogs()
	 *
	 * @param           mixed   $office
	 * @param           mixed   $sortOrder
	 * @param           mixed   $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listCheckinLogsOfOffice($office, $sortOrder = null, $limit = null) {
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
	 * @name            listStates ()
	 *
	 * @since           1.0.0
	 * @version         1.0.9
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @param           array $filter
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listStates($filter = null, $sortOrder = null, $limit = null) {
		$timeStamp = time();
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

		$entities = array();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}

		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name                  listStatesOfCountry ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 * @use             $this->listCities()
	 *
	 * @param           mixed $country
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array   $response
	 *
	 */
	public function listStatesOfCountry($country, $sortOrder = null, $limit = null) {
		$response = $this->getCountry($country);
		if ($response->error->exist) {
			return $response;
		}
		$country = $response->result->set;
		unseT($response);
		$filter = array();
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
	 * @name            updateCheckinLog()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->updateCheckinLogs()
	 *
	 * @param           mixed   $cLog
	 *
	 * @return          array   $response
	 *
	 */
	public function updateCheckinLog($cLog) {
		return $this->updateCheckinLogs(array($cLog));
	}

	/**
	 * @name            updateCheckinLogs()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @param           mixed   $collection
	 *
	 * @return          array   $response
	 *
	 */
	public function updateCheckinLogs($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
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

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}

	/**
	 * @name            updateState ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->updateStates()
	 *
	 * @param           mixed $state
	 *
	 * @return          array   $response
	 *
	 */
	public function updateState($state) {
		return $this->updateStates(array($state));
	}

	/**
	 * @name                  updateStates ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException()
	 *
	 * @param           mixed $collection
	 *
	 * @return          array   $response
	 *
	 */
	public function updateStates($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
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
							$localizations = array();
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

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}

	/**
	 * @name            deleteOffice ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->deleteOffices()
	 *
	 * @param           mixed $collection
	 *
	 * @return          array   $response
	 *
	 */

	public function deleteOffice($collection) {
		return $this->deleteOffices(array($collection));
	}

	/**
	 * @name                  deleteOffices ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 *
	 * @param           array $collection
	 *
	 * @return          array   $response
	 *
	 */

	public function deleteOffices($collection) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

	/**
	 * @name                  doesOfficeExist ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->getOffice()
	 *
	 * @param           mixed $office
	 * @param           bool  $bypass
	 *
	 * @return          array   $response
	 *
	 */
	public function doesOfficeExist($office, $bypass = false) {
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
	 * @name                  getOffice ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @param           mixed $office
	 *
	 * @return          array   $response
	 *
	 */

	public function getOffice($office) {
		$timeStamp = time();
		if ($office instanceof BundleEntity\Office) {
			return new ModelResponse($office, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
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
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            insertCheckinLog()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->insertCheckinLogs()
	 *
	 * @param           mixed   $cLog
	 *
	 * @return          array   $response
	 *
	 */

	public function insertCheckinLog($cLog) {
		return $this->insertCheckinLogs(array($cLog));
	}

	/**
	 * @name            insertCheckinLogs()
	 *
	 * @since           1.1.1
	 * @version         1.1.1
	 *
	 * @author          Can Berkol
	 *
	 * @param           mixed   $collection
	 *
	 * @return          array   $response
	 *
	 */
	public function insertCheckinLogs($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            insertOffice ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->insertOffices()
	 *
	 * @param           mixed $office
	 *
	 * @return          array   $response
	 *
	 */

	public function insertOffice($office) {
		return $this->insertOffices(array($office));
	}

	/**
	 * @name            insertOffices ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @param           mixed $collection
	 *
	 * @return          array   $response
	 *
	 */
	public function insertOffices($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = array();
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

			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            listOffices ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @param           array $filter
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array   $response
	 *
	 */

	public function listOffices($filter = null, $sortOrder = null, $limit = null) {
		$timeStamp = time();
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
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}

		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());

	}

	/**
	 * @name            listOfficesByCoordinates ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->listOffices()
	 *
	 * @param           decimal $lat                     X Coordinate
	 * @param           decimal $lon                     Y Coordinate
	 *
	 * @return          array   $response
	 *
	 */
	public function listOfficesByCoordinates($lat, $lon) {
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
	 * @param array $coordinates
	 * @param null $sortOrder
	 * @param null $limit
	 * @return ModelResponse
	 */
	public function listOfficesWithinCoordinates(array $coordinates, $sortOrder = null, $limit = null) {
		$timeStamp = time();
		if(!isset($coordinates['from']) || !isset($coordinates['to'])){
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'Invalid coordinate', $timeStamp, time());
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
	 * @param      $member
	 * @param null $sortOrder
	 * @param null $limit
	 *
	 * @return array|\BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listOfficesOfMember($member, $sortOrder = null, $limit = null) {
		$timeStamp = time();
		$mModel = $this->kernel->getContainer()->get('membermanagement.model');
		$response = $mModel->getMember($member);
		if($response->error->exist){
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		$member = $response->result->set;

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
	 * @name                   listOfficesByType ()
	 *
	 * @since           1.0.5
	 * @version         1.0.6
	 *
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->listOffices()
	 *
	 *
	 * @param           string $type
	 *
	 * @return          array   $response
	 *
	 */
	public function listOfficesByType($type) {
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

	public function listOfficesByName($name) {
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
	 * @name            listOfficesInCities()
	 *
	 * @since           1.0.7
	 * @version         1.0.7
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listOffices()
	 *
	 *
	 * @param           array $cities
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array       $response
	 *
	 */
	public function listOfficesInCities(array $cities, $sortOrder = null, $limit = null) {
		$timeStamp = time();
		$in = array();
		foreach($cities as $city){
			$response = $this->getCity($city);
			if(!$response->error->exist){
				$city = $response->result->set;
				$in[] = $city->getId();
			}
		}
		unset($response);
		if(count($in) < 1){
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
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
	 * @name            listOfficesInCity ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listOffices()
	 *
	 *
	 * @param           mixed $city
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array       $response
	 *
	 */
	public function listOfficesInCity($city, $sortOrder = null, $limit = null) {
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
	 * @name            listOfficesInCCountry ()
	 *
	 *
	 * @since           1.0.0
	 * @version         1.0.4
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listOffices()
	 *
	 *
	 * @param           mixed $country
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array       $response
	 *
	 */
	public function listOfficesInCountry($country, $sortOrder = null, $limit = null) {
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
	 * @name                  listOfficesInState ()
	 *                                           Lists offices located in a state.
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->listOffices()
	 *
	 *
	 * @param           mixed $state
	 * @param           array $sortOrder
	 * @param           array $limit
	 *
	 * @return          array       $response
	 *
	 */
	public function listOfficesInState($state, $sortOrder = null, $limit = null) {
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
	 * @name                  updateOffice ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->updateOffice()
	 *
	 * @param           mixed $office
	 *
	 * @return          array   $response
	 *
	 */

	public function updateOffice($office) {
		return $this->updateOffices(array($office));
	}

	/**
	 * @name                  updateOffices ()
	 *
	 * @since           1.0.0
	 * @version         1.0.6
	 *
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 *
	 * @param           mixed $collection   Entity or post data
	 *
	 * @return          array   $response
	 *
	 */
	public function updateOffices($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
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

			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}

		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
}


/**
 * Change Log
 * **************************************
 * v1.1.1                      04.11.2015
 * Can Berkol
 * **************************************
 * BF :: ->exists replaced with ->exist
 *
 * **************************************
 * v1.1.0                      26.08.2015
 * Can Berkol
 * **************************************
 * BF :: filter relatedproblems fixed.
 *
 * **************************************
 * v1.0.9                      23.07.2015
 * Can Berkol
 * **************************************
 * BF :: listStates function was not handling WHERE and ORDER BY correctly. Fixed.
 * BF :: insertOffices() copy paste errors fixed.
 *
 * **************************************
 * v1.0.8                      22.07.2015
 * Can Berkol
 * **************************************
 * BF :: list functions now return unique values.
 *
 * **************************************
 * v1.0.7                      15.07.2015
 * Can Berkol
 * **************************************
 * BF :: listStates() was trying to return country object. Fixed.
 * BF :: listCities() "city" changed to "c".
 * FR :: listOfficesInCities().
 *
 * **************************************
 * v1.0.6                      23.06.2015
 * Can Berkol
 * **************************************
 * FR :: Made compatible with Core 3.3
 *
 * **************************************
 * v1.0.5                   Said İmamoğlu
 * 17.04.2015
 * **************************************
 * A listOfficesBySite()
 * A listOfficesOfSiteBySite()
 *
 * **************************************
 * v1.0.4                      Can Berkol
 * 20.03.2014
 * **************************************
 * A listOfficesInCity()
 * A listOfficesInCountry()
 * A listOfficesInState()
 * U listOfficesByCoordinates()
 *
 * **************************************
 * v1.0.3                      Can Berkol
 * 13.03.2014
 * **************************************
 * A insertCityLocalization()
 * A insertCountryLocalization()
 * A insertStateLocalization()
 * U insertCity()
 * U insertCities()
 * U insertCountry()
 * U insertCountries()
 * U insertState()
 * U insertStates()
 * U updateCity()
 * U updateCities()
 * U updateCountry()
 * U updateCountries()
 * U updateState()
 * U updateStates()
 *
 * **************************************
 * v1.0.2                      Can Berkol
 * 04.03.2014
 * **************************************
 * A listStatesOfCountry()
 * U listCities()
 * U listCountries()
 * U listStates
 * U updateOffice()
 * U updateOffices()
 *
 */
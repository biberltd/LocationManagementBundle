<?php

/**
 * LocationManagementModel Class
 *
 * This class acts as a database proxy model for ProductManagementBundle functionalities.
 *
 * @package	    Core\Bundles\LocationManagementBundle
 * @subpackage	Services
 * @name	    LocationManagementModel
 *
 * @author      Can Berkol
 * @author      Said İmamoğlu
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.4
 *
 * @date        20.03.2014
 *
 * =============================================================================================================
 * !! INSTRUCTIONS ON IMPORTANT ASPECTS OF MODEL METHODS !!!
 *
 * Each model function must return a $response ARRAY.
 * The array must contain the following keys and corresponding values.
 *
 * $response = array(
 *              'result'    =>   An array that contains the following keys:
 *                               'set'         Actual result set returned from ORM or null
 *                               'total_rows'  0 or number of total rows
 *                               'last_insert_id' The id of the item that is added last (if insert action)
 *              'error'     =>   true if there is an error; false if there is none.
 *              'code'      =>   null or a semantic and short English string that defines the error concanated
 *                               with dots, prefixed with err and the initials of the name of model class.
 *                               EXAMPLE: err.amm.action.not.found success messages have a prefix called scc..
 *
 *                               NOTE: DO NOT FORGET TO ADD AN ENTRY FOR ERROR CODE IN BUNDLE'S
 *                               RESOURCES/TRANSLATIONS FOLDER FOR EACH LANGUAGE.
 * =============================================================================================================
 * TODOs:
 *
 * NOTE that this set of entities do have a strict pattern; therefore you can write wrap-up methods such as
 * list_locations etc. and all the below functions may work by calling those wrap-up methods.
 */

namespace BiberLtd\Bundle\LocationManagementBundle\Services;

/** Extends CoreModel */
use BiberLtd\Core\CoreModel;
/** Entities to be used */
use BiberLtd\Bundle\LocationManagementBundle\Entity as BundleEntity;
/** Helper Models */
use BiberLtd\Bundle\SiteManagementBundle\Services as SMMService;
use BiberLtd\Bundle\MultiLanguageSupportBundle\Entity as MLSEntity;
/** Core Service */
use BiberLtd\Core\Services as CoreServices;

class LocationManagementModel extends CoreModel {
    /**
     * @name            _Construct()
     *                  Constructor.
     *
     * @author          Said İmamoğlu
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           object          $kernel
     * @param           string          $dbConnection  Database connection key as set in app/config.yml
     * @param           string          $orm            ORM that is used.
     */
    public function __construct($kernel, $dbConnection = 'default', $orm = 'doctrine') {
        parent::__construct($kernel, $dbConnection, $orm);
        /**
         * Register entity names for easy reference.
         */
        $this->entity = array(
            'city' => array('name' => 'LocationManagementBundle:City', 'alias' => 'c'),
            'city_localization' => array('name' => 'LocationManagementBundle:CityLocalization', 'alias' => 'cl'),
            'country' => array('name' => 'LocationManagementBundle:Country', 'alias' => 'u'),
            'country_localization' => array('name' => 'LocationManagementBundle:CountryLocalization', 'alias' => 'ul'),
            'office' => array('name' => 'LocationManagementBundle:Office', 'alias' => 'o'),
            'state' => array('name' => 'LocationManagementBundle:State', 'alias' => 's'),
            'state_localization' => array('name' => 'LocationManagementBundle:StateLocalization', 'alias' => 'sl'),
        );
        $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
        $response = $mlsModel->listAllLanguages();
        if(!$response['error']){
            $this->languages = $response['result']['set'];
        }
        unset($mlsModel);
    }

    /** @var $by_opitons handles by options */
    public $by_opts = array('entity', 'id', 'code', 'url_key', 'post');

    /**
     * @name            __destruct()
     *                  Destructor.
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
     * @name            deleteCity()
     *                  Deletes single city
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */
    public function deleteCity($data) {
        return $this->deleteCities(array($data));
    }
    /**
     * @name            deleteCities()
     *                  Deletes single or multiple cities of given post data or entity
     * 
     * @since           1.0.0
     * @version         1.0.3
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->doesCityExist()
     * 
     * @throw           InvalidParameterException
     * @throw           InvalidByOptionException
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */
    public function deleteCities($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterValue', 'Array', 'err.invalid.parameter.collection');
        }
        $countDeleted = 0;
        foreach ($collection as $entry) {
            if ($entry instanceof BundleEntity\City) {
                $this->em->remove($entry);
                $countDeleted++;
            } else {
                switch ($entry) {
                    case is_numeric($entry):
                        $response = $this->getCity($entry, 'id');
                        break;
                }
                if ($response['error']) {
                    $this->createException('EntryDoesNotExist', $entry, 'err.invalid.entry');
                }
                $entry = $response['result']['set'];
                $this->em->remove($entry);
                unset($response);
                $countDeleted++;
            }
        }
        if ($countDeleted < 0) {
            $this->response['error'] = true;
            $this->response['code'] = 'err.db.fail.delete';

            return $this->response;
        }
        $this->em->flush();
        $this->response = array(
            'rowCount' => 0,
            'result' => array(
                'set' => null,
                'total_rows' => $countDeleted,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.deleted',
        );
        return $this->response;
    }

    /**
     * @name            doesCityExist()
     * Checks if city exist
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->getCity()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */
    public function doesCityExist($collection, $by = 'entity') {
        $this->resetResponse();
        $exist = false;
        $code = 'err.db.record.notfound';
        $error = false;

        $response = $this->getCity($collection, $by);

        if (!$response['error']) {
            $exist = true;
            $code = 'scc.db.record.found';
        } else {
            $error = true;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => $response['result']['total_rows'],
                'last_insert_id' => null,
            ),
            'error' => $error,
            'code' => $code,
        );
        return $this->response;
    }

    /**
     * @name            getCity()
     * Returns details of given city.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->listCities()
     * 
     * @throw           InvalidByOptionException
     * @throw           InvalidParameterException
     * @throw           InvalidEntityException
     * 
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */
    public function getCity($city, $by = 'id') {
        $this->resetResponse();
        if (!in_array($by, $this->by_opts)) {
            return $this->createException('InvalidByOptionException', implode(',', $this->by_opts), 'err.invalid.parameter.by');
        }

        if (!is_object($city) && !is_numeric($city) && !is_string($city)) {
            return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.file');
        }
        if ($by == 'entity') {
            if (is_object($city)) {
                if (!$city instanceof BundleEntity\City) {
                    return $this->createException('InvalidEntityException', 'BundleEntity\City', 'err.invalid.parameter.file');
                }
                /**
                 * Prepare and Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => $city,
                        'total_rows' => 1,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.entity.found'
                );
            } else {
                return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.file');
            }
        } elseif ($by == 'id') {
            $filter[] = array(
                'glue' => '',
                'condition' => array('column' => $this->entity['city']['alias'] . '.' . $by, 'comparison' => '=', 'value' => $city)
            );
            $response = $this->listCities($filter, null, array('start' => 0, 'count' => 1));
            if ($response['error']) {
                return $response;
            }

            $city = $response['result']['set'];

            /**
             * Prepare and Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $city[0],
                    'total_rows' => count($city),
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.entity.found',
            );
        }

        return $this->response;
    }

    /**
     * @name 		getProductLocalization()
     * Gets a specific city's localization values from database.
     *
     * @since		1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     *
     * @param           BundleEntity\Product           $product
     * @param           MLSEntity\Language             $language
     *
     * @return          array           $response
     */
    public function getCityLocalization($city, $language) {
        $this->resetResponse();
        if (!$city instanceof BundleEntity\Product) {
            return $this->createException('InvalidParameterException', 'City', 'err.invalid.parameter.product');
        }
        /** Parameter must be an array */
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'Language', 'err.invalid.parameter.language');
        }
        $q_str = 'SELECT ' . $this->entity['city_localization']['alias'] . ' FROM ' . $this->entity['city_localization']['name'] . ' ' . $this->entity['city_localization']['alias']
                . ' WHERE ' . $this->entity['city_localization']['alias'] . '.city = ' . $city->getId()
                . ' AND ' . $this->entity['city_localization']['alias'] . '.language = ' . $language->getId();

        $query = $this->em->createQuery($q_str);
        /**
         * 6. Run query
         */
        $result = $query->getResult();
        /**
         * Prepare & Return Response
         */
        $total_rows = count($result);

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist.',
        );
        return $this->response;
    }

    /**
     * @name            insertCity()
     *                  Inserts one city into database
     * 
     * @since           1.0.0
     * @version         1.0.3
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->insertCities()
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */
    public function insertCity($data) {
        return $this->insertCities(array($data));
    }

    /**
     * @name            insertCityLocalizations ()
     *                  Inserts one or more city localizations into database.
     *
     * @since           1.0.3
     * @version         1.0.3
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of entities or post data.
     *
     * @return          array           $response
     */
    public function insertCityLocalizations($collection){
        $this->resetResponse();
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
            } else {
                foreach ($item['localizations'] as $language => $data) {
                    $entity = new BundleEntity\CityLocalization;
                    $entity->setCity($item['entity']);
                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                    $response = $mlsModel->getLanguage($language, 'iso_code');
                    if (!$response['error']) {
                        $entity->setLanguage($response['result']['set']);
                    } else {
                        break 1;
                    }
                    foreach ($data as $column => $value) {
                        $set = 'set' . $this->translateColumnName($column);
                        $entity->$set($value);
                    }
                    $this->em->persist($entity);
                }
                $insertedItems[] = $entity;
                $countInserts++;
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            insertCities()
     *                  Inserts one or more cities into database
     * 
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */
    public function insertCities($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $countLocalizations = 0;
        $insertedItems = array();
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
                    $set = 'set'.$this->translateColumnName($column);
                    switch ($column) {
                        case 'local':
                            $localizations[$countInserts]['localizations'] = $value;
                            $localeSet = true;
                            $countLocalizations++;
                            break;
                        case 'country':
                        case 'state':
                            $get = 'get'.$this->translateColumnName($column);
                            $response = $this->$get($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            }
                            else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response);
                            break;
                        default:
                            if(property_exists($entity, $column)){
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
            else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        /** Now handle localizations */
        if ($countInserts > 0 && $countLocalizations > 0) {
            $this->insertCityLocalizations($localizations);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => $entity->getId(),
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            listCities()
     * List cities from database
     * 
     * @since           1.0.0
     * @version         1.0.2
     *
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * @param           bool    $returnLocal    if set to true returns localization object alongside with entity.
     * 
     * @return          array   $response
     * 
     */

    public function listCities($filter = null, $sortorder = null, $limit = null, $query_str = null, $returnLocal = false) {
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidSortOrder', '', 'err.invalid.parameter.sortorder');
        }

        /**
         * Add filter check to below to set join_needed to true
         */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        $filter_str = '';

        /**
         * Start creating the query
         *
         * Note that if no custom select query is provided we will use the below query as a start
         */
        if (is_null($query_str)) {
            $query_str = 'SELECT '. $this->entity['city_localization']['alias']. ', '.$this->entity['city_localization']['alias']
                . ' FROM '.$this->entity['city_localization']['name'].' '.$this->entity['city_localization']['alias']
                . ' JOIN '.$this->entity['city_localization']['alias'].'.city ' .$this->entity['city']['alias'];
        }
        /**
         * Prepare ORDER BY section of query
         */
        if (!is_null($sortorder)) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'id':
                    case 'country':
                    case 'state':
                    case 'code':
                        $column = $this->entity['city']['alias'] . '.' . $column;
                        break;
                    case 'language':
                    case 'city':
                    case 'name':
                    case 'url_key':
                        $column = $this->entity['city_localization']['alias'] . '.' . $column;
                        break;
                }
                $order_str .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }

        /**
         * Prepare WHERE section of query
         */
        if (!is_null($filter)) {
            $filter_str = $this->prepareWhere($filter);
            $where_str = ' WHERE ' . $filter_str;
        }

        $query_str .= $where_str . $group_str . $order_str;

        $query = $this->em->createQuery($query_str);

        /**
         * Prepare LIMIT section of query
         */
        if (!is_null($limit)) {
            $query = $this->addLimit($query, $limit);
        }
        $result = $query->getResult();
        $cities = array();
        $unique = array();
        if($returnLocal){
            $allLangCodes = array();
            foreach($this->languages as $language){
                $allLangCodes[] = $language->getIsoCode();
            }
            foreach ($result as $entry) {
                $id = $entry->getCity()->getId();
                $cities[$id]['entity'] = $entry->getCity();
                $cities[$id]['localization'][$entry->getLanguage()->getIsoCode()] = $entry;
            }
            foreach ($cities as $detail) {
                $entityLangCodes = array_keys($detail['localization']);
                $missingLangCodes = array_diff($allLangCodes, $entityLangCodes);
                foreach($missingLangCodes as $langCode){
                    if(!isset($detail['localization'][$langCode])){
                        $iCount = 0;
                        foreach($detail['localization'] as $localization){
                            if($iCount > 0){
                                break;
                            }
                            $cities[$detail['entity']->getId()]['localization'][$langCode] = $localization;
                            $iCount++;
                        }
                    }
                }
            }
            $total_rows = count($cities);
        }
        else{
            foreach ($result as $entry) {
                $id = $entry->getCity()->getId();
                if (!isset($unique[$id])) {
                    $cities[] = $entry->getCity();
                    $unique[$id] = $entry->getCity();
                }
            }
            $total_rows = count($cities);
        }
        unset($unique);

        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $cities,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );

        return $this->response;
    }

    /**
     * @name            listCitiesOfCountry()
     *                  Lists cities of country
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->listCities()
     * 
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * @param           bool    $returnLocal    If set to true returns localized values.
     * 
     * @return          array   $response
     * 
     */

    public function listCitiesOfCountry($country, $sortorder = null, $limit = null, $query_str = null, $returnLocal = false) {
        if ($country instanceof BundleEntity\Country) {
            $country = $country->getId();
        }
        $filter = array();
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['city']['alias'] . '.country',
                        'comparison' => '=', 'value' => $country),
                )
            )
        );

        return $this->listCities($filter, $sortorder, $limit, $query_str, $returnLocal);
    }

    /**
     * @name            listCitiesOfState()
     *                  Lists cities of state
     * 
     * @since           1.0.0
     * @version         1.0.4
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->listCities()
     * 
     * @throw           InvalidIdException
     * 
     * @param           mixed   $state          id or entity
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * 
     * @return          array   $response
     * 
     */

    public function listCitiesOfState($state, $sortorder = null, $limit = null, $query_str = null) {
        if ($state instanceof BundleEntity\State){
            $state = $state->getId();
        }
        else{
            $state = (int) $state;
        }
        $filter[] = array(
            'glue' => ' and',
            'condition' => array(
                'column' => $this->entity['city']['alias'] . '.state',
                'comparison' => '=',
                'value' => $state
            )
        );

        return $this->listCities($filter, $sortorder, $limit, $query_str);
    }

    /**
     * @name            updateCity()
     * Updates single city of given post data or entity
     * 
     * @since           1.0.0
     * @version         1.0.3
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->updateCities()
     * 
     * @param           mixed   $data           Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function updateCity($data) {
        return $this->updateCities(array($data));
    }
    /**
     * @name            updateCities()
     *                  Updates one or more cities from database.
     *
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     *
     * @param           mixed   $collection     Entity or post data
     *
     * @return          array   $response
     *
     */
    public function updateCities($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
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
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                $response = $this->getCity($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'City with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                            if($column == 'state' && (is_null($value) || $value == -1)){
                                break;
                            }
                            $get = 'get'.$this->translateColumnName($column);
                            $response = $this->$get($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteCountry()
     *                  Updates single folder of given post data or entity
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function deleteCountry($collection) {
        return $this->deleteCountries($collection);
    }

    /**
     * @name            deleteCountries()
     *                  Updates single folder of given post data or entity
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    /**
     * @name            deleteCities()
     *                  Deletes single or multiple cities of given post data or entity
     *
     * @since           1.0.0
     * @version         1.0.3
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     * @use             $this->doesCityExist()
     *
     * @throw           InvalidParameterException
     * @throw           InvalidByOptionException
     *
     * @param           mixed   $collection     Entity or post data
     *
     * @return          array   $response
     *
     */
    public function deleteCountries($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterValue', 'Array', 'err.invalid.parameter.collection');
        }
        $countDeleted = 0;
        foreach ($collection as $entry) {
            if ($entry instanceof BundleEntity\Country) {
                $this->em->remove($entry);
                $countDeleted++;
            } else {
                switch ($entry) {
                    case is_numeric($entry):
                        $response = $this->getCountry($entry, 'id');
                        break;
                }
                if ($response['error']) {
                    $this->createException('EntryDoesNotExist', $entry, 'err.invalid.entry');
                }
                $entry = $response['result']['set'];
                $this->em->remove($entry);
                unset($response);
                $countDeleted++;
            }
        }
        if ($countDeleted < 0) {
            $this->response['error'] = true;
            $this->response['code'] = 'err.db.fail.delete';

            return $this->response;
        }
        $this->em->flush();
        $this->response = array(
            'rowCount' => 0,
            'result' => array(
                'set' => null,
                'total_rows' => $countDeleted,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.deleted',
        );
        return $this->response;
    }

    /**
     * @name            doesCountryExist()
     *                  Updates single folder of given post data or entity
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function doesCountryExist($collection, $by = 'entity') {
        $this->resetResponse();
        $exist = false;
        $code = 'err.db.record.notfound';
        $error = false;

        $response = $this->getCountry($collection, $by);

        if (!$response['error']) {
            $exist = true;
            $code = 'scc.db.record.found';
        } else {
            $error = true;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => $response['result']['total_rows'],
                'last_insert_id' => null,
            ),
            'error' => $error,
            'code' => $code,
        );
        return $this->response;
    }

    /**
     * @name            getCountry()
     *                  Updates single folder of given post data or entity
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function getCountry($country, $by = 'id') {
        $this->resetResponse();
        $this->by_opts[] = 'code_iso';
        if (!in_array($by, $this->by_opts)) {
            return $this->createException('InvalidByOptionException', implode(',', $this->by_opts), 'err.invalid.parameter.by');
        }
        if (!is_object($country) && !is_numeric($country) && !is_string($country)) {
            return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.file');
        }
        if ($by == 'entity') {
            if (is_object($country)) {
                if (!$country instanceof BundleEntity\Country) {
                    return $this->createException('InvalidEntityException', 'BundleEntity\Country', 'err.invalid.parameter.file');
                }
                /**
                 * Prepare and Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => $country,
                        'total_rows' => 1,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.entity.found'
                );
            } else {
                return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.file');
            }
        } else {
            $filter[] = array(
                'glue' => '',
                'condition' => array('column' => $this->entity['country']['alias'] . '.' . $by, 'comparison' => '=', 'value' => $country)
            );
            $response = $this->listCountries($filter, null, array('start' => 0, 'count' => 1));
            if ($response['error']) {
                return $response;
            }

            $country = $response['result']['set'];

            /**
             * Prepare and Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $country[0],
                    'total_rows' => count($country),
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.entity.found',
            );
        }

        return $this->response;
    }

    /**
     * @name 		getCountryLocalization()
     * Gets a specific country's localization values from database.
     *
     * @since		1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     *
     * @param           BundleEntity\Product           $product
     * @param           MLSEntity\Language             $language
     *
     * @return          array           $response
     */
    public function getCountryLocalization($country, $language) {
        $this->resetResponse();
        if (!$country instanceof BundleEntity\Product) {
            return $this->createException('InvalidParameterException', 'Country', 'err.invalid.parameter.product');
        }
        /** Parameter must be an array */
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'Language', 'err.invalid.parameter.language');
        }
        $q_str = 'SELECT ' . $this->entity['country_localization']['alias'] . ' FROM ' . $this->entity['country_localization']['name'] . ' ' . $this->entity['country_localization']['alias']
                . ' WHERE ' . $this->entity['country_localization']['alias'] . '.country = ' . $country->getId()
                . ' AND ' . $this->entity['country_localization']['alias'] . '.language = ' . $language->getId();

        $query = $this->em->createQuery($q_str);
        /**
         * 6. Run query
         */
        $result = $query->getResult();
        /**
         * Prepare & Return Response
         */
        $total_rows = count($result);

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist.',
        );
        return $this->response;
    }

    /**
     * @name            insertCountry()
     *                  Inserts single country into database
     * 
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->insertCountries()
     * 
     * @param           mixed   $country     Entity or post data
     * 
     * @return          array   $response
     * 
     */

    public function insertCountry($country) {
        return $this->insertCountries(array($country));
    }

    /**
     * @name            insertCountryLocalizations ()
     *                  Inserts one or more country localizations into database.
     *
     * @since           1.0.3
     * @version         1.0.3
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array           $collection Collection of entities or post data.
     *
     * @return          array           $response
     */
    public function insertCountryLocalizations($collection){
        $this->resetResponse();
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
                foreach ($item['localizations'] as $language => $data) {
                    $entity = new BundleEntity\CountryLocalization;
                    $entity->setCountry($item['entity']);
                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                    $response = $mlsModel->getLanguage($language, 'iso_code');
                    if (!$response['error']) {
                        $entity->setLanguage($response['result']['set']);
                    } else {
                        break 1;
                    }
                    foreach ($data as $column => $value) {
                        $set = 'set' . $this->translateColumnName($column);
                        $entity->$set($value);
                    }
                    $this->em->persist($entity);
                }
                $insertedItems[] = $entity;
                $countInserts++;
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }
    /**
     * @name            insertCountries()
     *                  Inserts one or more country into database
     * 
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     */
    public function insertCountries($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $countLocalizations = 0;
        $insertedItems = array();
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
                    $set = 'set'.$this->translateColumnName($column);
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
            else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        /** Now handle localizations */
        if ($countInserts > 0 && $countLocalizations > 0) {
            $this->insertCountryLocalizations($localizations);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => $entity->getId(),
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            listCountries()
     *                  Lists countries from database
     * 
     * @since           1.0.0
     * @version         1.0.2
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->addLimit()
     * 
     * @throw           InvalidSortOrderException
     * @throw           InvalidLimitException
     * 
     * 
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * 
     * @return          array   $response
     * 
     */

    public function listCountries($filter = null, $sortorder = null, $limit = null, $query_str = null, $returnLocal = false) {
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidSortOrderException', '', 'err.invalid.parameter.sortorder');
        }

        /**
         * Add filter check to below to set join_needed to true
         */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        $filter_str = '';

        if (is_null($query_str)) {
            $query_str = 'SELECT '. $this->entity['country_localization']['alias']. ', '.$this->entity['country']['alias']
                            . ' FROM '.$this->entity['country_localization']['name'].' '.$this->entity['country_localization']['alias']
                            . ' JOIN '.$this->entity['country_localization']['alias'].'.country ' .$this->entity['country']['alias'];
        }
        /**
         * Prepare ORDER BY section of query
         */
        if (!is_null($sortorder)) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'id':
                    case 'country':
                    case 'state':
                    case 'code':
                        $column = $this->entity['country']['alias'].'.'.$column;
                        break;
                    case 'name':
                    case 'url_key':
                        $column = $this->entity['country_localization']['alias'].'.'.$column;
                        break;
                    default:
                        break;
                }
                $order_str .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }

        /**
         * Prepare WHERE section of query
         */

        if (!is_null($filter)) {
            $filter_str = $this->prepareWhere($filter);
            $where_str = ' WHERE ' . $filter_str;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);
        /**
         * Prepare LIMIT section of query
         */
        if (!is_null($limit)) {
            $query = $this->addLimit($query, $limit);
        }
        $result = $query->getResult();
        $countries = array();
        $unique = array();
        if($returnLocal){
            $allLangCodes = array();
            foreach($this->languages as $language){
                $allLangCodes[] = $language->getIsoCode();
            }
            foreach ($result as $entry) {
                $id = $entry->getCountry()->getId();
                $countries[$id]['entity'] = $entry->getCountry();
                $countries[$id]['localization'][$entry->getLanguage()->getIsoCode()] = $entry;
            }
            foreach ($countries as $countryDetail) {
                $entityLangCodes = array_keys($countryDetail['localization']);
                $missingLangCodes = array_diff($allLangCodes, $entityLangCodes);
                foreach($missingLangCodes as $langCode){
                    if(!isset($countryDetail['localization'][$langCode])){
                        $iCount = 0;
                        foreach($countryDetail['localization'] as $localization){
                            if($iCount > 0){
                                break;
                            }
                            $countries[$countryDetail['entity']->getId()]['localization'][$langCode] = $localization;
                            $iCount++;
                        }
                    }
                }
            }
            $total_rows = count($countries);
        }
        else{
            foreach ($result as $entry) {
                $id = $entry->getCountry()->getId();
                if (!isset($unique[$id])) {
                    $countries[] = $entry->getCountry();
                    $unique[$id] = $entry->getCountry();
                }
            }
        }
        unset($unique);
        $total_rows = count($countries);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $countries,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );

        return $this->response;
    }

    /**
     * @name            updateCountry()
     *                  Updates single country from database
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * @author          Can Berkol
     * 
     * @use             $this->updateCountries()
     * 
     * @param           mixed   $dara           Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function updateCountry($data) {
        return $this->updateCountries(array($data));
    }

    /**
     * @name            updateCountries)
     *                  Updates one or more countries from database.
     *
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     *
     * @param           mixed   $collection     Entity or post data
     *
     * @return          array   $response
     *
     */
    public function updateCountries($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
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
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                $response = $this->getCountry($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'Country with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $localization = new BundleEntity\CountryLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteState()
     * Deletes single state from database
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->deleteStates()
     * 
     * @param           mixed   $collection     Entity or post data
     *
     * @return          array   $response
     * 
     */

    public function deleteState($collection) {
        return $this->deleteStates($collection);
    }

    /**
     * @name            deleteStates()
     * Deletes one or more state from the database
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     * @use             $this->doesStateExist()
     *
     * 
     * @param           mixed   $collection     Entity or post date
     * 
     * @return          array   $response
     * 
     */

    /**
     * @name            deleteCities()
     *                  Deletes single or multiple cities of given post data or entity
     *
     * @since           1.0.0
     * @version         1.0.3
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     * @use             $this->doesCityExist()
     *
     * @throw           InvalidParameterException
     * @throw           InvalidByOptionException
     *
     * @param           mixed   $collection     Entity or post data
     *
     * @return          array   $response
     *
     */
    public function deleteStates($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterValue', 'Array', 'err.invalid.parameter.collection');
        }
        $countDeleted = 0;
        foreach ($collection as $entry) {
            if ($entry instanceof BundleEntity\State) {
                $this->em->remove($entry);
                $countDeleted++;
            } else {
                switch ($entry) {
                    case is_numeric($entry):
                        $response = $this->getState($entry, 'id');
                        break;
                }
                if ($response['error']) {
                    $this->createException('EntryDoesNotExist', $entry, 'err.invalid.entry');
                }
                $entry = $response['result']['set'];
                $this->em->remove($entry);
                unset($response);
                $countDeleted++;
            }
        }
        if ($countDeleted < 0) {
            $this->response['error'] = true;
            $this->response['code'] = 'err.db.fail.delete';

            return $this->response;
        }
        $this->em->flush();
        $this->response = array(
            'rowCount' => 0,
            'result' => array(
                'set' => null,
                'total_rows' => $countDeleted,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.deleted',
        );
        return $this->response;
    }

    /**
     * @name            doesStateExist()
     * Checks if state exist in database
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->getState()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */
    public function doesStateExist($collection, $by = 'entity') {
        $this->resetResponse();
        $exist = false;
        $code = 'err.db.record.notfound';
        $error = false;

        $response = $this->getState($collection, $by);

        if (!$response['error']) {
            $exist = true;
            $code = 'scc.db.record.found';
        } else {
            $error = true;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => $response['result']['total_rows'],
                'last_insert_id' => null,
            ),
            'error' => $error,
            'code' => $code,
        );
        return $this->response;
    }

    /**
     * @name            getState()
     * Returns state details from the database
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->listStates()
     * 
     * @throws          InvalidByOptionException
     * @throws          InvalidEntityException
     * 
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function getState($collection, $by = 'entity') {
        $this->resetResponse();
        if (!in_array($by, $this->by_opts)) {
            return $this->createException('InvalidByOptionException', implode(',', $this->by_opts), 'err.invalid.parameter.by');
        }

        if (!is_object($collection) && !is_numeric($collection) && !is_string($collection)) {
            return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.file');
        }
        if ($by == 'entity') {
            if (is_object($collection)) {
                if (!$collection instanceof BundleEntity\State) {
                    return $this->createException('InvalidEntityException', 'BundleEntity\State', 'err.invalid.parameter.file');
                }
                /**
                 * Prepare and Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => $collection,
                        'total_rows' => 1,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.entity.found'
                );
            } else {
                return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.file');
            }
        } elseif ($by == 'id') {
            $filter[] = array(
                'glue' => '',
                'condition' => array('column' => $this->entity['state']['alias'] . '.' . $by, 'comparison' => '=', 'value' => $collection)
            );
            $response = $this->listStates($filter, null, array('start' => 0, 'count' => 1));
            if ($response['error']) {
                return $response;
            }

            $collection = $response['result']['set'];

            /**
             * Prepare and Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $collection[0],
                    'total_rows' => count($collection),
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.entity.found',
            );
        }

        return $this->response;
    }

    /**
     * @name 		getCountryLocalization()
     * Gets a specific country's localization values from database.
     *
     * @since		1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     *
     * @use             $this->createException()
     *
     * @param           BundleEntity\Product           $product
     * @param           MLSEntity\Language             $language
     *
     * @return          array           $response
     */
    public function getStateLocalization($state, $language) {
        $this->resetResponse();
        if (!$state instanceof BundleEntity\Product) {
            return $this->createException('InvalidParameterException', 'State', 'err.invalid.parameter.product');
        }
        /** Parameter must be an array */
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'Language', 'err.invalid.parameter.language');
        }
        $q_str = 'SELECT ' . $this->entity['state_localization']['alias'] . ' FROM ' . $this->entity['state_localization']['name'] . ' ' . $this->entity['state_localization']['alias']
                . ' WHERE ' . $this->entity['state_localization']['alias'] . '.city = ' . $state->getId()
                . ' AND ' . $this->entity['state_localization']['alias'] . '.language = ' . $language->getId();

        $query = $this->em->createQuery($q_str);
        /**
         * 6. Run query
         */
        $result = $query->getResult();
        /**
         * Prepare & Return Response
         */
        $total_rows = count($result);

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist.',
        );
        return $this->response;
    }

    /**
     * @name            insertState()
     *                  Inserts one state into database.
     * 
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->insertStates()
     * 
     * @param           mixed   $data     Entity or post data
     * 
     * @return          array   $response
     * 
     */

    public function insertState($data) {
        return $this->insertStates(array($data));
    }

    /**
     * @name            insertStates()
     *                  Inserts one or more states into database.
     * 
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function insertStates($collection, $by = 'post') {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $countLocalizations = 0;
        $insertedItems = array();
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
                    $set = 'set'.$this->translateColumnName($column);
                    switch ($column) {
                        case 'local':
                            $localizations[$countInserts]['localizations'] = $value;
                            $localeSet = true;
                            $countLocalizations++;
                            break;
                        case 'country':
                            $response = $this->getCountry($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            }
                            else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
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
            else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        /** Now handle localizations */
        if ($countInserts > 0 && $countLocalizations > 0) {
            $this->insertStateLocalizations($localizations);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => $entity->getId(),
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }
    /**
     * @name            insertStateLocalizations ()
     *                  Inserts one or more state localizations into database.
     *
     * @since           1.0.3
     * @version         1.0.3
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array           $collection Collection of entities or post data.
     *
     * @return          array           $response
     */
    public function insertStateLocalizations($collection){
        $this->resetResponse();
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
                foreach ($item['localizations'] as $language => $data) {
                    $entity = new BundleEntity\StateLocalization;
                    $entity->setState($item['entity']);
                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                    $response = $mlsModel->getLanguage($language, 'iso_code');
                    if (!$response['error']) {
                        $entity->setLanguage($response['result']['set']);
                    } else {
                        break 1;
                    }
                    foreach ($data as $column => $value) {
                        $set = 'set' . $this->translateColumnName($column);
                        $entity->$set($value);
                    }
                    $this->em->persist($entity);
                }
                $insertedItems[] = $entity;
                $countInserts++;
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }
    /**
     * @name            listStates()
     *                  Returns state details from database.
     * 
     * @since           1.0.0
     * @version         1.0.2
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->addLimit()
     * 
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * @param           bool    $returnLocal
     * 
     * @return          array   $response
     * 
     */

    public function listStates($filter = null, $sortorder = null, $limit = null, $query_str = null, $returnLocal = false) {
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidSortOrderException', '', 'err.invalid.parameter.sortorder');
        }

        /**
         * Add filter check to below to set join_needed to true
         */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        $filter_str = '';

        if (is_null($query_str)) {
            $query_str = 'SELECT '. $this->entity['state_localization']['alias']. ', '.$this->entity['state']['alias']
                . ' FROM '.$this->entity['state_localization']['name'].' '.$this->entity['state_localization']['alias']
                . ' JOIN '.$this->entity['state_localization']['alias'].'.state ' .$this->entity['state']['alias'];
        }
        /**
         * Prepare ORDER BY section of query
         */
        if (!is_null($sortorder)) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'id':
                    case 'country':
                    case 'code':
                        $column = $this->entity['state']['alias'].'.'.$column;
                        break;
                    case 'name':
                    case 'url_key':
                        $column = $this->entity['state_localization']['alias'].'.'.$column;
                        break;
                    default:
                        break;
                }
                $order_str .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }

        /**
         * Prepare WHERE section of query
         */

        if (!is_null($filter)) {
            $filter_str = $this->prepareWhere($filter);
            $where_str = ' WHERE ' . $filter_str;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);

        /**
         * Prepare LIMIT section of query
         */
        if (!is_null($limit)) {
            $query = $this->addLimit($query, $limit);
        }
        $result = $query->getResult();
        $states = array();
        $unique = array();
        if($returnLocal){
            $allLangCodes = array();
            foreach($this->languages as $language){
                $allLangCodes[] = $language->getIsoCode();
            }
            foreach ($result as $entry) {
                $id = $entry->getState()->getId();
                $states[$id]['entity'] = $entry->getState();
                $states[$id]['localization'][$entry->getLanguage()->getIsoCode()] = $entry;
            }
            foreach ($states as $detail) {
                $entityLangCodes = array_keys($detail['localization']);
                $missingLangCodes = array_diff($allLangCodes, $entityLangCodes);
                foreach($missingLangCodes as $langCode){
                    if(!isset($detail['localization'][$langCode])){
                        $iCount = 0;
                        foreach($detail['localization'] as $localization){
                            if($iCount > 0){
                                break;
                            }
                            $states[$detail['entity']->getId()]['localization'][$langCode] = $localization;
                            $iCount++;
                        }
                    }
                }
            }
            $total_rows = count($states);
        }
        else{
            foreach ($result as $entry) {
                $id = $entry->getState()->getId();
                if (!isset($unique[$id])) {
                    $states[] = $entry->getState();
                    $unique[$id] = $entry->getState();
                }
            }
            $total_rows = count($states);
        }
        unset($unique);

        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $states,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );

        return $this->response;
    }
    /**
     * @name            listStatesOfCountry()
     *                  Lists states of country
     *
     * @since           1.0.0
     * @version         1.0.2
     * @author          Can Berkol
     *
     * @use             $this->createException()
     * @use             $this->listCities()
     *
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'column' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * @param           bool    $returnLocal    If set to true returns localized values.
     *
     * @return          array   $response
     *
     */
    public function listStatesOfCountry($country, $sortorder = null, $limit = null, $query_str = null, $returnLocal = false) {
        $this->resetResponse();
        if($country instanceof BundleEntity\Country) {
            $id = $country->getId();
        }
        else if(is_numeric($country)){
            $id = (int) $country;
        }
        else{
            return $this->createException('InvalidParameter', 'country', 'err.invalid.country');
        }
        $filter = array();
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['state']['alias'] . '.country',
                        'comparison' => '=', 'value' => $id),
                )
            )
        );

        return $this->listStates($filter, $sortorder, $limit, $query_str, $returnLocal);
    }
    /**
     * @name            updateState()
     * Updates one state from database
     * 
     * @since           1.0.0
     * @version         1.0.3
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->updateStates()
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */

    public function updateState($data) {
        return $this->updateStates(array($data));
    }

    /**
     * @name            updateStates()
     *                  Updates one or more states from database.
     * 
     * @since           1.0.0
     * @version         1.0.3
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */
    public function updateStates($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
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
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                $response = $this->getState($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'State with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
                                    $localization->setState($oldEntity);
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
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteOffice()
     * Deletes single office form the database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->deleteOffices()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function deleteOffice($collection, $by = 'id') {
        return $this->deleteOffices(array($collection), $by);
    }

    /**
     * @name            deleteOffices()
     * Deletes one or more office from the database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->doesOfficeExist()
     * 
     * @throws          InvalidParameterException
     * @throws          InvalidByOptionException
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function deleteOffices($offices, $by = 'entity') {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'username', 'email');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterException', implode(',', $by_opts), 'err.invalid.parameter.by');
        }
        /** Parameter must be an array */
        if (!is_array($offices)) {
            return $this->createException('InvalidParameterException', 'Array', 'err.invalid.parameter.collection');
        }
        $entries = array();
        /** Loop through languages and collect values. */
        foreach ($offices as $office) {
            $value = '';
            if (is_object($office)) {
                if (!$office instanceof BundleEntity\Office) {
                    return $this->createException('InvalidParameterException', 'Office', 'err.invalid.parameter.collection');
                }
                $value = $office->getId();
            } else if (is_numeric($office) || is_string($office)) {
                $value = $office;
            } else {
                /** If array values are not numeric nor object */
                return $this->createException('InvalidParameterException', 'Integer, String, or Member Entity', 'err.invalid.parameter.member');
            }
            /**
             * Check if member exits in database.
             */
            if ($this->doesOfficeExist($value, 'id')) {
                $entries[] = $value;
            } else {
                new CoreExceptions\MemberDoesNotExistException($this->kernel, $value);
            }
        }
        /**
         * Control if there is any member id in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'Array', 'err.invalid.parameter.collection');
        }
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                $by = 'id';
            case 'id':
                $values = implode(',', $entries);
                break;
        }
        $query_str = 'DELETE '
                . ' FROM ' . $this->entity['office']['name'] . ' ' . $this->entity['office']['alias']
                . ' WHERE ' . $this->entity['office']['alias'] . '.' . $by . ' IN('.$values.')';
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($query_str);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $offices,
                'total_rows' => count($offices),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
    }

    /**
     * @name            doesOfficeExist()
     * Checks if office exist in database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->getOffice()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function doesOfficeExist($collection, $by = 'entity', $bypass = false) {
        $this->resetResponse();
        $exist = false;
        $code = 'err.db.record.notfound';
        $error = false;

        $response = $this->getOffice($collection, $by);

        if (!$response['error']) {
            $exist = true;
            $code = 'scc.db.record.found';
        } else {
            $error = true;
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => $response['result']['total_rows'],
                'last_insert_id' => null,
            ),
            'error' => $error,
            'code' => $code,
        );
        return $this->response;
    }

    /**
     * @name            getOffice()
     * Returns office details from database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->listOffices()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @throws          InvalidByOptionException
     * @throws          InvalidEntityException
     * @throws          InvalidParameterException
     * 
     * @return          array   $response
     * 
     */

    public function getOffice($collection, $by = 'id') {
        $this->resetResponse();
        if (!in_array($by, $this->by_opts)) {
            return $this->createException('InvalidByOptionException', implode(',', $this->by_opts), 'err.invalid.parameter.by');
        }

        if (!is_object($collection) && !is_numeric($collection) && !is_string($collection)) {
            return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.office');
        }
        if ($by == 'entity') {
            if (is_object($collection)) {
                if (!$collection instanceof BundleEntity\Office) {
                    return $this->createException('InvalidEntityException', 'BundleEntity\State', 'err.invalid.parameter.office');
                }
                /**
                 * Prepare and Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => $collection,
                        'total_rows' => 1,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.entity.found'
                );
            } else {
                return $this->createException('InvalidParameterException', 'object,numeric or string', 'err.invalid.parameter.office');
            }
        } elseif ($by == 'id') {
            $filter[] = array(
                'glue' => '',
                'condition' => array('column' => $this->entity['office']['alias'] . '.' . $by, 'comparison' => '=', 'value' => $collection)
            );
            $response = $this->listOffices($filter, null, array('start' => 0, 'count' => 1));
            if ($response['error']) {
                return $response;
            }

            $collection = $response['result']['set'];

            /**
             * Prepare and Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $collection[0],
                    'total_rows' => count($collection),
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.entity.found',
            );
        }

        return $this->response;
    }

    /**
     * @name            insertOffice()
     * inserts one office into database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->insertOffices()
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function insertOffice($collection, $by = 'post') {
        return $this->insertOffices($collection, $by);
    }

    /*
     * @name            insertOffices()
     * Inserts one or more offices into database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * 
     * @throws          InvalidParameterException
     * @throws          InvalidMethodException
     * 
     * @param           mixed   $collection     Entity or post data
     * @param           string  $by             entity or post
     * 
     * @return          array   $response
     * 
     */

    public function insertOffices($collection, $by = 'post') {
        /* Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'array() or Integer', 'err.invalid.parameter.collection');
        }

        if (!in_array($by, $this->by_opts)) {
            return $this->createException('InvalidParameterException', implode(',', $this->by_opts), 'err.invalid.parameter.by.collection');
        }

        if ($by == 'entity') {
            $sub_response = $this->insert_entities($collection, 'BundleEntity\\Office');
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $collection,
                    'total_rows' => count($sub_response['result']['set']),
                    'last_insert_id' => $sub_response->getId(),
                ),
                'error' => false,
                'code' => 'scc.db.insert.done',
            );

            return $this->response;
        } else {
            $entity = new BundleEntity\Office();
            foreach ($collection as $item) {
                foreach ($item as $column => $value) {
                    switch ($column) {
                        case 'site':
                            $SMModel = new SMMService\SiteManagementModel($this->kernel, $this->db_connection, $this->orm);
                            $response = $SMModel->getSite($value, 'id');
                            if ($response['error']) {
                                new CoreExceptions\InvalidSiteException($this->kernel, $value);
                                break;
                            }
                            $value = $response['result']['set'];
                            /** Free up some memory */
                            unset($response, $SMModel);
                            break;
                        case 'city':
                            $response = $this->getCity($value, 'id');
                            if ($response['error']) {
                                return $this->createException('InvalidParameterException', 'City', 'err.invalid.parameter.collection');
                            }
                            $value = $response['result']['set'];
                            $entity->setCountry($value->getCountry());
                            break;
                        default:
                            break;
                    }
                    $MethodSet = 'set' . $this->translateColumnName($column);
                    $entity->$MethodSet($value);
                }
                $this->em->persist($entity);
            }

            $this->em->flush();
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $collection,
                    'total_rows' => count($collection),
                    'last_insert_id' => $entity->getId(),
                ),
                'error' => false,
                'code' => 'scc.db.insert.done',
            );

            return $this->response;
        }
    }

    /**
     * @name            listOffices()
     * Lists office details from database.
     * 
     * @since           1.0.0
     * @version         1.0.0
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->addLimit()
     * 
     * @throws          InvalidSortOrderException
     * @throws          InvalidLimitException
     * 
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * 
     * @return          array   $response
     * 
     */

    public function listOffices($filter = null, $sortorder = null, $limit = null, $query_str = null) {
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidSortOrderException', '', 'err.invalid.parameter.sortorder');
        }

        /**
         * Add filter check to below to set join_needed to true
         */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        $filter_str = '';


        /**
         * Start creating the query
         *
         * Note that if no custom select query is provided we will use the below query as a start
         */
        if (is_null($query_str)) {
            $query_str = 'SELECT ' . $this->entity['office']['alias']
                    . ' FROM ' . $this->entity['office']['name'] . ' ' . $this->entity['office']['alias'];
        }
        /*
         * Prepare ORDER BY section of query
         */
        if (!is_null($sortorder)) {
            foreach ($sortorder as $column => $direction) {
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
                        $column = $this->entity['office']['alias'].'.'.$column;
                }
                $order_str .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }

        /*
         * Prepare WHERE section of query
         */

        if (!is_null($filter)) {
            $filter_str = $this->prepareWhere($filter);
            $where_str = ' WHERE ' . $filter_str;
        }

        $query_str .= $where_str . $group_str . $order_str;

        $query = $this->em->createQuery($query_str);

        /*
         * Prepare LIMIT section of query
         */

        if (!is_null($limit) && is_numeric($limit)) {
            /*
             * if limit is set
             */
            if (isset($limit['start']) && isset($limit['count'])) {
                $query = $this->addLimit($query, $limit);
            } else {
                $this->createException('InvalidLimitException', '', 'err.invalid.limit');
            }
        }
        //print_r($query->getSql()); die;
        /*
         * Prepare and Return Response
         */

        $files = $query->getResult();


        $total_rows = count($files);
        if ($total_rows < 1) {
            $this->response['error'] = true;
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $files,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );

        return $this->response;
    }

    /**
     * @name            listOfficesByCoordinates()
     * Lists offices details with given coordinates.
     * 
     * @since           1.0.0
     * @version         1.0.4
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->listOffices()
     * 
     * 
     * @param           decimal     $lat        X Coordinate
     * @param           decimal     $lon        Y Coordinate
     * 
     * @return          array   $response
     * 
     */

    public function listOfficesByCoordinates($lat, $lon) {

        $filter[] = array(
            'glue' => ' and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['office']['alias'] . '.lat',
                        'comparison' => '=',
                        'value' => $lat
                    )
                ),
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['office']['alias'] . '.lon',
                        'comparison' => '=',
                        'value' => $lon
                    )
                )
            )
        );
        return $this->listOffices($filter);
    }

    /**
     * @name            listOfficesInCity()
     *                  Lists offices located in a city.
     *
     * @since           1.0.0
     * @version         1.0.4
     *
     * @author          Can Berkol
     *
     * @use             $this->listOffices()
     *
     *
     * @param           mixed       $city
     * @param           array       $sortorder
     * @param           array       $limit
     *
     * @return          array       $response
     *
     */

    public function listOfficesInCity($city,  $sortorder = null, $limit = null) {
        if($city instanceof BundleEntity\City){
            $city = $city->getId();
        }
        $filter[] = array(
            'glue' => ' and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['office']['alias'] . '.city',
                        'comparison' => '=',
                        'value' => $city
                    )
                )
            )
        );
        return $this->listOffices($filter, $sortorder, $limit);
    }
    /**
     * @name            listOfficesInCCountry()
     *                  Lists offices located in a country.
     *
     * @since           1.0.0
     * @version         1.0.4
     *
     * @author          Can Berkol
     *
     * @use             $this->listOffices()
     *
     *
     * @param           mixed       $country
     * @param           array       $sortorder
     * @param           array       $limit
     *
     * @return          array       $response
     *
     */
    public function listOfficesInCountry($country, $sortorder = null, $limit = null) {
        if($country instanceof BundleEntity\Country){
            $country = $country->getId();
        }
        $filter[] = array(
            'glue' => ' and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['office']['alias'] . '.country',
                        'comparison' => '=',
                        'value' => $country
                    )
                )
            )
        );
        return $this->listOffices($filter, $sortorder, $limit);
    }
    /**
     * @name            listOfficesInState()
     *                  Lists offices located in a state.
     *
     * @since           1.0.0
     * @version         1.0.4
     *
     * @author          Can Berkol
     *
     * @use             $this->listOffices()
     *
     *
     * @param           mixed       $state
     * @param           array       $sortorder
     * @param           array       $limit
     *
     * @return          array       $response
     *
     */

    public function listOfficesInState($state,  $sortorder = null, $limit = null) {
        if($state instanceof BundleEntity\Country){
            $state = $state->getId();
        }
        $filter[] = array(
            'glue' => ' and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array(
                        'column' => $this->entity['office']['alias'] . '.state',
                        'comparison' => '=',
                        'value' => $state
                    )
                )
            )
        );
        return $this->listOffices($filter, $sortorder, $limit);
    }
    /**
     * @name            listOfficesOfSite()
     * Lists office details of given site.
     * 
     * @since           1.0.0
     * @version         1.0.4
     * @author          Said İmamoğlu
     * 
     * @use             $this->listOffices()
     * 
     * @param           array   $filter         Multi dimensional array
     * @param           array   $sortorder      'coloumn' => 'asc|desc'
     * @param           array   $limit          start,count
     * @param           string  $query_str      If a custom query string needs to be defined.
     * 
     * @return          array   $response
     * 
     */
    public function listOfficesOfSite($site) {
        $filter[] = array(
            'glue' => ' and',
            'condition' => array(
                'column' => $this->entity['office']['alias'] . '.site',
                'comparison' => '=',
                'value' => $site
            )
        );
        return $this->listOffices($filter);
    }

    /**
     * @name            updateOffice()
     *                  Updates one office from database.
     * 
     * @since           1.0.0
     * @version         1.0.2
     * @author          Said İmamoğlu
     * 
     * @use             $this->updateOffice()
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */

    public function updateOffice($collection) {
        return $this->updateOffices($collection);
    }

    /**
     * @name            updateOffices()
     *                  Updates one or more office from database.
     * 
     * @since           1.0.0
     * @version         1.0.2
     *
     * @author          Can Berkol
     * @author          Said İmamoğlu
     * 
     * @use             $this->createException()
     * @use             $this->listOffices()
     * 
     * @throws          InvalidParameterException
     * 
     * @param           mixed   $collection     Entity or post data
     * 
     * @return          array   $response
     * 
     */

    public function updateOffices($collection) {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach($collection as $data){
            if($data instanceof BundleEntity\Office){
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            }
            else if(is_object($data)){
                if(!property_exists($data, 'id') || !is_numeric($data->id)){
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                if(!property_exists($data, 'date_updated')){
                    $data->date_updated = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
                }
                if(property_exists($data, 'date_added')){
                    unset($data->date_added);
                }
                if(!property_exists($data, 'site')){
                    $data->site = 1;
                }
                $response = $this->getOffice($data->id, 'id');
                if($response['error']){
                    return $this->createException('EntityDoesNotExist', 'Product with id '.$data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
                foreach($data as $column => $value){
                    $set = 'set'.$this->translateColumnName($column);
                    switch($column){
                        case 'site':
                            $sModel = $this->kernel->getContainer()->get('sitemanagement.model');
                            $response = $sModel->getSite($value, 'id');
                            if(!$response['error']){
                                $oldEntity->$set($response['result']['set']);
                            }
                            else{
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $sModel);
                            break;
                        case 'city':
                        case 'state':
                        case 'country':
                            $get = 'get'.$this->translateColumnName($column);
                            $response = $this->$get($value, 'id');
                            if(!$response['error']){
                                $oldEntity->$set($response['result']['set']);
                            }
                            else{
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $sModel);
                            break;
                        case 'id':
                            break;
                        default:
                            $oldEntity->$set($value);
                            break;
                    }
                    if($oldEntity->isModified()){
                        $this->em->persist($oldEntity);
                        $countUpdates++;
                        $updatedItems[] = $oldEntity;
                    }
                }
            }
            else{
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if($countUpdates > 0){
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }
}

/**
* Change Log
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
 * **************************************
 * v1.0.0                     Said İmamoğlu
 * 27.10.2013
 * **************************************
 * A deleteCities()
 * A deleteCity()
 * A deleteCountries()
 * A deleteCountry()
 * A deleteOffice()
 * A deleteOffices()
 * A deleteState()
 * A deleteStates()
 * A doesCityExist()
 * A doesCountryExist()
 * A doesOfficeExist()
 * A doesStateExist()
 * A getCity()
 * A getCityLocalization()
 * A getCountry()
 * A getCountryLocalization()
 * A getOffice()
 * A getState()
 * A getStateLocalization()
 * A insertCities()
 * A insertCity()
 * A insertCountries()
 * A insertCountry()
 * A insertOffices()
 * A insertOffice()
 * A insertStates()
 * A insertState()
 * A listCities()
 * A listCitiesOfCountry()
 * A listCitiesOfState()
 * A listCountries()
 * A listOffices()
 * A listOfficesOfSite()
 * A listOfficesByCoordinates()
 * A listStates()
 * A updateCities()
 * A updateCity()
 * A updateCountries()
 * A updateCountry()
 * A updateOffices()
 * A updateOffice()
 * A updateStates()
 * A updateState()
 *
 * **************************************
 * v1.0.0                     Can Berkol
 * 20.10.2013
 * **************************************
 * Initial setup of class has been added.
 *
 *
 */
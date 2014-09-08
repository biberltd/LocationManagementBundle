<?php

/**
 * TestController
 *
 * This controller is used to install default / test values to the system.
 * The controller can only be accessed from allowed IP address.
 *
 * @package		MemberManagementBundleBundle
 * @subpackage	Controller
 * @name	    TestController
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 *
 */

namespace BiberLtd\Bundle\LocationManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception,
    Symfony\Component\HttpFoundation\Response,
    BiberLtd\Core\CoreController;

class IndexController extends CoreController {

    /**
     * @name 			init()
     *  				Each controller must call this function as its first statement.27
     *                  This function acts as a constructor and initializes default values of this controller.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     */
    public function __construct() {
        
    }

    protected function init() {
        $this->init_defaults();
        if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || !in_array(@$_SERVER['REMOTE_ADDR'], unserialize(APP_DEV_IPS))
        ) {
            header('HTTP/1.0 403 Forbidden');
            exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
        }
        /**         * ***************** */
        $this->request = $this->getRequest();
        $this->session = $this->get('session');
        $this->locale = $this->request->getLocale();
        $this->translator = $this->get('translator');
    }

    /**
     * @name 			test_modelAction()
     *  				DOMAIN/test/product/model
     *                  Used to test member localizations.
     *
     * @since			1.0.0
     * @version         1.0.0
     * @author          Can Berkol
     *
     */
    public function testAction() {
        /** Initialize */
        $this->init();
        $this->model = $this->get('core_location_management_bundle.model');
//        $filter[] = array(
//            'glue' => 'and',
//            'condition' => array(
//                            array(
//                                'glue' => 'and',
//                                'condition' => array('column' => 'p.id', 'comparison' => 'in', 'value' => array(3,4,5,6)),
//                            )
//                        )
//        );
//        $filter[] = array(
//            'glue' => 'and',
//            'condition' => array(
//                array(
//                    'glue' => 'or',
//                    'condition' => array('column' => 'p.status', 'comparison' => 'eq', 'value' => 'a'),
//                ),
//                array(
//                    'glue' => 'and',
//                    'condition' => array('column' => 'p.price', 'comparison' => '<', 'value' => 500),
//                ),
//            )
//        );
        $post =
        $response = $this->insertCity();
        echo '<pre>'; print_r($response); die;
        if (!$response['error']) {
            $entries = $response['result']['set'];
            $message = '';
            foreach ($entries as $entry) {
                //$sitis  = $entry->getCities();
                echo '<pre>';print_r($entry->getName()); die;
                echo $sitis[0]->getLocalization('tr')->getName(); die;
                $message .= $entry->getName() . '<br>';
            }
            $html = '<html><head></head><body>' . $message . '</body></html>';
            return new Response($html);
        }
        $html = '<html><head></head><body>Not found!</body></html>';
        return new Response($html);
    }

    public function listCities() {
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => 'c.id', 'comparison' => '=', 'value' => 1),
                )
            )
        );
        return $this->model->listCities($filter);
    }
    
    public function getCity(){
        return $this->model->getCity(2,'id');
    }
    public function getCountry(){
        return $this->model->getCountry(1,'id');
    }
    
    public function getState(){
        return $this->model->getState(1,'id');
    }
    
    public function insertCity(){
        $city = array(
            'city' => array(
                'code'      =>'',
                'language'  =>1,
                'name'      => 'Bitlis',
                'url_key'   => 'btlis'
            ),
            'country'       => array(
                ''
            ),
            'state'         => array(
                
            )
        );
        
        return $this->model->insertCity($city,'post');
    }
    
    

}

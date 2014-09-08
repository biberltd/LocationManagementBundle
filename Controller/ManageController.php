<?php
/**
 * ManageController
 *
 * This controller is used to manage locatıons in database
 *
 * @package		LocatıonManagementBundle
 * @subpackage	Controller
 * @name	    ManageController
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 *
 */

namespace BiberLtd\Bundle\LocationManagementBundle\Controller;

use BiberLtd\Core\CoreController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception,
    Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ManageController extends CoreController
{
    /**
     * @name            deleteCityAction()
     *                  DOMAIN/{_locale}/manage/city/delete/{$singleId}
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           integer         $singleId
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCityAction($singleId = -1) {
        /** START INITIALIZATION */
        /** @todo multi site support */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/city/delete'));
        unset($response);
        /** END :: INITIALIZATION */
        $request = $this->get('request');
        /** Multi Delete Mode */
        $form = $request->get('modalForm');

        if($form['data']['csfr'] != $this->session->get('_csfr')){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            /** $response[$code] must have a corresponding translation */
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.security.csfr', array(), 'admin'));

            return new RedirectResponse($this->url['base_l'] . '/manage/city/list');
        }

        $locModel = $this->get('locationmanagement.model');

        $toDelete = explode(',', trim($form['data']['entities'],','));

        $locModel->deleteCities($toDelete);

        $this->session->getFlashBag()->add('msg.status', true);
        $this->session->getFlashBag()->add('msg.type', 'success');
        $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.success.delete', array(), 'admin'));
        return new RedirectResponse($this->url['base_l'] . '/manage/city/list');
    }
    /**
     * @name            deleteCountryAction ()
     *                  DOMAIN/{_locale}/manage/country/delete/{$singleId}
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           integer         $singleId
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCountryAction($singleId = -1) {
        /** START INITIALIZATION */
        /** @todo multi site support */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/country/delete'));
        unset($response);
        /** END :: INITIALIZATION */
        $request = $this->get('request');
        /** Multi Delete Mode */
        $form = $request->get('modalForm');

        if($form['data']['csfr'] != $this->session->get('_csfr')){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            /** $response[$code] must have a corresponding translation */
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.security.csfr', array(), 'admin'));

            return new RedirectResponse($this->url['base_l'] . '/manage/city/list');
        }

        $locModel = $this->get('locationmanagement.model');

        $toDelete = explode(',', trim($form['data']['entities'],','));

        $locModel->deleteCountries($toDelete);

        $this->session->getFlashBag()->add('msg.status', true);
        $this->session->getFlashBag()->add('msg.type', 'success');
        $this->sessino->getFlashBag()->add('msg.content', $this->translator->trans('msg.success.delete', array(), 'admin'));
        return new RedirectResponse($this->url['base_l'] . '/manage/country/list');
    }
    /**
     * @name            deleteStateAction ()
     *                  DOMAIN/{_locale}/manage/state/delete/{$singleId}
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           integer         $singleId
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function deleteStateAction($singleId = -1) {
        /** START INITIALIZATION */
        /** @todo multi site support */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/state/delete'));
        unset($response);
        /** END :: INITIALIZATION */
        $request = $this->get('request');
        /** Multi Delete Mode */
        $form = $request->get('modalForm');

        if($form['data']['csfr'] != $this->session->get('_csfr')){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            /** $response[$code] must have a corresponding translation */
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.security.csfr', array(), 'admin'));

            return new RedirectResponse($this->url['base_l'] . '/manage/city/list');
        }

        $locModel = $this->get('locationmanagement.model');

        $toDelete = explode(',', trim($form['data']['entities'],','));

        $locModel->deleteStates($toDelete);

        $this->session->getFlashBag()->add('msg.status', true);
        $this->session->getFlashBag()->add('msg.type', 'success');
        $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.success.delete', array(), 'admin'));
        return new RedirectResponse($this->url['base_l'] . '/manage/state/list');
    }
    /**
     * @name            editCityAction()
     *                  DOMAIN/{_locale}/manage/city/edit/{id}
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           integer             $id
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function editCityAction($id) {
        /** START INITILALIZATION */
        $this->init(1, 'cmsEditCity');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/city/edit'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/city/edit'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $locModel->getCity($id, 'id');
        if($response['error']){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            /** $response[$code] must have a corresponding translation */
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.invalid.record', array(), 'admin'));
            /** csfr error */
            return new RedirectResponse($this->url['base_l'] . '/manage/city/list');
        }
        $currentLocation = $response['result']['set'];
        unset($response);

        $response = $mlsModel->listAllLanguages();
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all countries */
        $countries = array();
        $response = $locModel->listCountries(null, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $countries = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all states that belong to city's country */
        $states = array();
        $response = $locModel->listStatesOfCountry($currentLocation->getCountry(), array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $states = $response['result']['set'];
        }
        unset($response);
        /**
         * Render Multi Language Form
         */
        $renderedPageDetailsWidget = '';
        $title = $this->translator->trans('title.city.detail', array(), 'admin');
        $languages = array();
        foreach ($siteLanguages as $entity) {
            $languages[] = array('code' => $entity->getIsoCode(), 'name' => $entity->getName());
        }
        $widgetSettings = array(
            'defaultLanguageCode' => $this->site->getLanguage()->getIsoCode(),
            'showActions' => true,
            'showSwitch' => true,
            'showSwitchInfo' => true,
        );
        $switchDetails = array(
            'actions' => array(
                array('id' => 'main-form-action-save', 'style' => 'primary pull-right', 'type' => 'button', 'lbl' => $this->translator->trans('btn.save', array(), 'admin'), 'elementType' => 'button'),
                array(
                    'id' => 'main-form-action-delete',
                    'style' => 'danger pull-left',
                    'type' => 'button',
                    'lbl' =>  $this->translator->trans('btn.delete', array(), 'admin'),
                    'elementType' => 'a',
                    'link' => '#modal-delete',
                    'attributes' => array('role="button"', 'data-toggle="modal"'),
                ),
            ),
            'info' => $this->translator->trans('msg.info.translate', array(), 'admin'),
            'lbl' => array(
                'title' => $this->translator->trans('lbl.translate', array(), 'admin'),
            ),
            'option' => array(
                'off' => array(
                    'name' => $this->translator->trans('lbl.option.off', array(), 'admin'),
                ),
                'on' => array(
                    'name' => $this->translator->trans('lbl.option.on', array(), 'admin'),
                ),
            ),
            'state' => '',
        );
        $localLocationDetails = array();
        foreach ($languages as $language) {
            $localLocationDetails['name'][$language['code']] = $currentLocation->getLocalization($language['code'])->getName();
        }
        $inputs = array(
            array(
                'type' => 'textInput',
                'id' => 'name',
                'label' => $this->translator->trans('lbl.name.item', array(), 'admin'),
                'name' => 'name',
                'values' => $localLocationDetails['name'],
            ),
        );
        /**
         * Prepare country options
         */
        $countryOptions = array(
            array(
                'name' => $this->translator->trans('lbl.select_default', array(), 'admin'),
                'selected' => false,
                'value' => -1,
            ),
        );
        foreach ($countries as $country) {
               $countryOptions[] = array(
                    'name' => $country['localization'][$this->locale]->getName(),
                    'selected' => ($country['entity']->getId() == $currentLocation->getCountry()->getId()) ? true : false,
                    'value' => $country['entity']->getId(),
                );
        }
        /**
         * Prepare state options
         */
        $stateOptions = array(
            array(
                'name' => $this->translator->trans('lbl.select_default', array(), 'admin'),
                'selected' => false,
                'value' => -1,
            ),
        );
        foreach ($states as $state) {
            $stateOptions[] = array(
                'name' => $state['localization'][$this->locale]->getName(),
                'selected' => ($state['entity']->getId() == $currentLocation->getState()->getId()) ? true : false,
                'value' => $state['entity']->getId(),
            );
        }
        $formDetails = array(
            'csfr' => $this->generateCSFR($this->session),
            'inputs' => $inputs,
            'title' => array(
                'defaultLanguage' => $this->site->getLanguage()->getName(),
            ),
            'extraInputs' => array(
                array(
                    'id' => 'country',
                    'label' => $this->translator->trans('lbl.country', array(), 'admin'),
                    'name' => 'country',
                    'classes' => array('updateState'),
                    'settings' => array('rowSize' => 12),
                    'size'=>12,
                    'type' => 'dropDown',
                    'options' => $countryOptions,
                ),
                array(
                    'id' => 'state',
                    'label' => $this->translator->trans('lbl.state', array(), 'admin'),
                    'name' => 'state',
                    'settings' => array('rowSize' => 12),
                    'classes' => array('targetState'),
                    'size'=>12,
                    'type' => 'dropDown',
                    'options' => $stateOptions,
                ),
            )
        );

        $icon = $this->url['domain'] . '/themes/' . $this->page['entity']->getLayout()->getTheme()->getFolder() . '/img/icons/light-sh/globe.png';
        $renderedPageDetailsWidget = $coreRender->renderMultiLanguageWidget($title, 'location[]', $icon, $languages, $core, $widgetSettings, $formDetails, $switchDetails);
        unset($inputs, $icon);

        /** Prepare Delete Modal */
        $widgetSettings = array(
            'confirmType'   => 'a',
        );
        $widgetActions = array(
            'cancel' => array(
                'text'  => $this->translator->trans('btn.cancel', array(), 'admin'),
            ),
            'confirm' => array(
                'text'  => $this->translator->trans('btn.confirm', array(), 'admin'),
                'url'   => $this->url['base_l'].'/manage/city/delete/'.$currentLocation->getId(),
            ),
        );
        $renderedModal = $coreRender->renderModalBoxView($widgetActions, $core, 'modal-delete', $this->translator->trans('msg.prompt.confirm.delete', array(), 'admin'), $this->translator->trans('lbl.confirm.delete', array(), 'admin'), $settings = array());
        unset($widgetSettings, $widgetActions);
        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'entity_id'             => $currentLocation->getId(),
            'stateUpdate'           => $this->url['manage'].'/state/update',
            'mlsForm'               => $renderedPageDetailsWidget,
            'renderedModal'         => $renderedModal,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/city/process/edit/'.$id, 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
        );
        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            editCountryAction()
     *                  DOMAIN/{_locale}/manage/country/edit/{id}
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           integer             $id
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function editCountryAction($id) {
        /** START INITILALIZATION */
        $this->init(1, 'cmsEditCountry');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/country/edit'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/country/edit'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $locModel->getCountry($id, 'id');
        if($response['error']){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            /** $response[$code] must have a corresponding translation */
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.invalid.record', array(), 'admin'));
            /** csfr error */
            return new RedirectResponse($this->url['base_l'] . '/manage/city/list');
        }
        $currentLocation = $response['result']['set'];
        unset($response);

        $response = $mlsModel->listAllLanguages();
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all countries */
        $countries = array();
        $response = $locModel->listCountries(null, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $countries = $response['result']['set'];
        }
        unset($response);
        /**
         * Render Multi Language Form
         */
        $renderedPageDetailsWidget = '';
        $title = $this->translator->trans('title.country.detail', array(), 'admin');
        $languages = array();
        foreach ($siteLanguages as $entity) {
            $languages[] = array('code' => $entity->getIsoCode(), 'name' => $entity->getName());
        }
        $widgetSettings = array(
            'defaultLanguageCode' => $this->site->getLanguage()->getIsoCode(),
            'showActions' => true,
            'showSwitch' => true,
            'showSwitchInfo' => true,
        );
        $switchDetails = array(
            'actions' => array(
                array('id' => 'main-form-action-save', 'style' => 'primary pull-right', 'type' => 'submit', 'lbl' => $this->translator->trans('btn.save', array(), 'admin'), 'elementType' => 'button'),
                array(
                    'id' => 'main-form-action-delete',
                    'style' => 'danger pull-left',
                    'type' => 'button',
                    'lbl' =>  $this->translator->trans('btn.delete', array(), 'admin'),
                    'elementType' => 'a',
                    'link' => '#modal-delete',
                    'attributes' => array('role="button"', 'data-toggle="modal"'),
                ),
            ),
            'info' => $this->translator->trans('msg.info.translate', array(), 'admin'),
            'lbl' => array(
                'title' => $this->translator->trans('lbl.translate', array(), 'admin'),
            ),
            'option' => array(
                'off' => array(
                    'name' => $this->translator->trans('lbl.option.off', array(), 'admin'),
                ),
                'on' => array(
                    'name' => $this->translator->trans('lbl.option.on', array(), 'admin'),
                ),
            ),
            'state' => '',
        );
        $localLocationDetails = array();
        foreach ($languages as $language) {
            $localLocationDetails['name'][$language['code']] = $currentLocation->getLocalization($language['code'])->getName();
        }
        $inputs = array(
            array(
                'type' => 'textInput',
                'id' => 'name',
                'label' => $this->translator->trans('lbl.name.item', array(), 'admin'),
                'name' => 'name',
                'values' => $localLocationDetails['name'],
            ),
        );

        $formDetails = array(
            'csfr' => $this->generateCSFR($this->session),
            'inputs' => $inputs,
            'title' => array(
                'defaultLanguage' => $this->site->getLanguage()->getName(),
            ),
        );

        $icon = $this->url['domain'] . '/themes/' . $this->page['entity']->getLayout()->getTheme()->getFolder() . '/img/icons/light-sh/globe.png';
        $renderedPageDetailsWidget = $coreRender->renderMultiLanguageWidget($title, 'location[]', $icon, $languages, $core, $widgetSettings, $formDetails, $switchDetails);
        unset($inputs, $icon);

        /** Prepare Delete Modal */
        $widgetSettings = array(
            'confirmType'   => 'a',
        );
        $widgetActions = array(
            'cancel' => array(
                'text'  => $this->translator->trans('btn.cancel', array(), 'admin'),
            ),
            'confirm' => array(
                'text'  => $this->translator->trans('btn.confirm', array(), 'admin'),
                'url'   => $this->url['base_l'].'/manage/country/delete/'.$currentLocation->getId(),
            ),
        );
        $renderedModal = $coreRender->renderModalBoxView($widgetActions, $core, 'modal-delete', $this->translator->trans('msg.prompt.confirm.delete', array(), 'admin'), $this->translator->trans('lbl.confirm.delete', array(), 'admin'), $settings = array());
        unset($widgetSettings, $widgetActions);
        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'entity_id'             => $currentLocation->getId(),
            'mlsForm'               => $renderedPageDetailsWidget,
            'renderedModal'         => $renderedModal,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/country/process/edit/'.$id, 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
        );
        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            editStateAction()
     *                  DOMAIN/{_locale}/manage/state/edit/{id}
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @param           integer             $id
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function editStateAction($id) {
        /** START INITILALIZATION */
        $this->init(1, 'cmsEditState');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/state/edit'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/state/edit'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $locModel->getState($id, 'id');
        if($response['error']){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            /** $response[$code] must have a corresponding translation */
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.invalid.record', array(), 'admin'));
            /** csfr error */
            return new RedirectResponse($this->url['base_l'] . '/manage/state/list');
        }
        $currentLocation = $response['result']['set'];
        unset($response);

        $response = $mlsModel->listAllLanguages();
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all countries */
        $countries = array();
        $response = $locModel->listCountries(null, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $countries = $response['result']['set'];
        }
        unset($response);

        /**
         * Render Multi Language Form
         */
        $renderedPageDetailsWidget = '';
        $title = $this->translator->trans('title.state.detail', array(), 'admin');
        $languages = array();
        foreach ($siteLanguages as $entity) {
            $languages[] = array('code' => $entity->getIsoCode(), 'name' => $entity->getName());
        }
        $widgetSettings = array(
            'defaultLanguageCode' => $this->site->getLanguage()->getIsoCode(),
            'showActions' => true,
            'showSwitch' => true,
            'showSwitchInfo' => true,
        );
        $switchDetails = array(
            'actions' => array(
                array('id' => 'main-form-action-save', 'style' => 'primary pull-right', 'type' => 'button', 'lbl' => $this->translator->trans('btn.save', array(), 'admin'), 'elementType' => 'button'),
                array(
                    'id' => 'main-form-action-delete',
                    'style' => 'danger pull-left',
                    'type' => 'button',
                    'lbl' =>  $this->translator->trans('btn.delete', array(), 'admin'),
                    'elementType' => 'a',
                    'link' => '#modal-delete',
                    'attributes' => array('role="button"', 'data-toggle="modal"'),
                ),
            ),
            'info' => $this->translator->trans('msg.info.translate', array(), 'admin'),
            'lbl' => array(
                'title' => $this->translator->trans('lbl.translate', array(), 'admin'),
            ),
            'option' => array(
                'off' => array(
                    'name' => $this->translator->trans('lbl.option.off', array(), 'admin'),
                ),
                'on' => array(
                    'name' => $this->translator->trans('lbl.option.on', array(), 'admin'),
                ),
            ),
            'state' => '',
        );
        $localLocationDetails = array();
        foreach ($languages as $language) {
            $localLocationDetails['name'][$language['code']] = $currentLocation->getLocalization($language['code'])->getName();
        }
        $inputs = array(
            array(
                'type' => 'textInput',
                'id' => 'name',
                'label' => $this->translator->trans('lbl.name.item', array(), 'admin'),
                'name' => 'name',
                'values' => $localLocationDetails['name'],
            ),
        );
        /**
         * Prepare country options
         */
        $countryOptions = array(
            array(
                'name' => $this->translator->trans('lbl.select_default', array(), 'admin'),
                'selected' => false,
                'value' => -1,
            ),
        );
        foreach ($countries as $country) {
            $countryOptions[] = array(
                'name' => $country['localization'][$this->locale]->getName(),
                'selected' => ($country['entity']->getId() == $currentLocation->getCountry()->getId()) ? true : false,
                'value' => $country['entity']->getId(),
            );
        }
        $formDetails = array(
            'csfr' => $this->generateCSFR($this->session),
            'inputs' => $inputs,
            'title' => array(
                'defaultLanguage' => $this->site->getLanguage()->getName(),
            ),
            'extraInputs' => array(
                array(
                    'id' => 'country',
                    'label' => $this->translator->trans('lbl.country', array(), 'admin'),
                    'name' => 'country',
                    'settings' => array('rowSize' => 12),
                    'size'=> 12,
                    'type' => 'dropDown',
                    'options' => $countryOptions,
                ),
            )
        );

        $icon = $this->url['domain'] . '/themes/' . $this->page['entity']->getLayout()->getTheme()->getFolder() . '/img/icons/light-sh/globe.png';
        $renderedPageDetailsWidget = $coreRender->renderMultiLanguageWidget($title, 'location[]', $icon, $languages, $core, $widgetSettings, $formDetails, $switchDetails);
        unset($inputs, $icon);

        /** Prepare Delete Modal */
        $widgetSettings = array(
            'confirmType'   => 'a',
        );
        $widgetActions = array(
            'cancel' => array(
                'text'  => $this->translator->trans('btn.cancel', array(), 'admin'),
            ),
            'confirm' => array(
                'text'  => $this->translator->trans('btn.confirm', array(), 'admin'),
                'url'   => $this->url['base_l'].'/manage/state/delete/'.$currentLocation->getId(),
            ),
        );
        $renderedModal = $coreRender->renderModalBoxView($widgetActions, $core, 'modal-delete', $this->translator->trans('msg.prompt.confirm.delete', array(), 'admin'), $this->translator->trans('lbl.confirm.delete', array(), 'admin'), $settings = array());
        unset($widgetSettings, $widgetActions);
        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'entity_id'             => $currentLocation->getId(),
            'mlsForm'               => $renderedPageDetailsWidget,
            'renderedModal'         => $renderedModal,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/state/process/edit/'.$id, 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
        );
        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            newCityAction()
     *                  DOMAIN/{_locale}/manage/city/new
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function newCityAction() {
        /** START INITILALIZATION */
        $this->init(1, 'cmsNewCity');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/city/new'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/city/new'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $mlsModel->listAllLanguages();
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all countries */
        $countries = array();
        $response = $locModel->listCountries(null, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $countries = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all states that belong to city's country */
        $states = array();
        /**
         * Render Multi Language Form
         */
        $renderedPageDetailsWidget = '';
        $title = $this->translator->trans('title.city.detail', array(), 'admin');
        $languages = array();
        foreach ($siteLanguages as $entity) {
            $languages[] = array('code' => $entity->getIsoCode(), 'name' => $entity->getName());
        }
        $widgetSettings = array(
            'defaultLanguageCode' => $this->site->getLanguage()->getIsoCode(),
            'showActions' => true,
            'showSwitch' => true,
            'showSwitchInfo' => true,
        );
        $switchDetails = array(
            'actions' => array(
                array('id' => 'main-form-action-save', 'style' => 'primary pull-right', 'type' => 'button', 'lbl' => $this->translator->trans('btn.save', array(), 'admin'), 'elementType' => 'button'),
                array(
                    'id' => 'main-form-action-delete',
                    'style' => 'danger pull-left',
                    'type' => 'button',
                    'lbl' =>  $this->translator->trans('btn.delete', array(), 'admin'),
                    'elementType' => 'a',
                    'link' => '#modal-delete',
                    'attributes' => array('role="button"', 'data-toggle="modal"'),
                ),
            ),
            'info' => $this->translator->trans('msg.info.translate', array(), 'admin'),
            'lbl' => array(
                'title' => $this->translator->trans('lbl.translate', array(), 'admin'),
            ),
            'option' => array(
                'off' => array(
                    'name' => $this->translator->trans('lbl.option.off', array(), 'admin'),
                ),
                'on' => array(
                    'name' => $this->translator->trans('lbl.option.on', array(), 'admin'),
                ),
            ),
            'state' => '',
        );
        $inputs = array(
            array(
                'type' => 'textInput',
                'id' => 'name',
                'label' => $this->translator->trans('lbl.name.item', array(), 'admin'),
                'name' => 'name',
            ),
        );
        /**
         * Prepare country options
         */
        $countryOptions = array(
            array(
                'name' => $this->translator->trans('lbl.select_default', array(), 'admin'),
                'selected' => false,
                'value' => -1,
            ),
        );
        foreach ($countries as $country) {
            $countryOptions[] = array(
                'name' => $country['localization'][$this->locale]->getName(),
                'selected' => $country['entity']->getId() == 1 ? true : false,
                'value' => $country['entity']->getId(),
            );
        }
        /**
         * Prepare state options
         */
        $stateOptions = array(
            array(
                'name' => $this->translator->trans('lbl.select_default', array(), 'admin'),
                'selected' => false,
                'value' => -1,
            ),
        );
        $formDetails = array(
            'csfr' => $this->generateCSFR($this->session),
            'inputs' => $inputs,
            'title' => array(
                'defaultLanguage' => $this->site->getLanguage()->getName(),
            ),
            'extraInputs' => array(
                array(
                    'id' => 'country',
                    'label' => $this->translator->trans('lbl.country', array(), 'admin'),
                    'name' => 'country',
                    'classes' => array('updateState'),
                    'settings' => array('rowSize' => 12),
                    'size'=>12,
                    'type' => 'dropDown',
                    'options' => $countryOptions,
                ),
                array(
                    'id' => 'state',
                    'label' => $this->translator->trans('lbl.state', array(), 'admin'),
                    'name' => 'state',
                    'classes' => array('targetState'),
                    'settings' => array('rowSize' => 12),
                    'size'=>12,
                    'type' => 'dropDown',
                    'options' => $stateOptions,
                ),
            )
        );

        $icon = $this->url['domain'] . '/themes/' . $this->page['entity']->getLayout()->getTheme()->getFolder() . '/img/icons/light-sh/globe.png';
        $renderedPageDetailsWidget = $coreRender->renderMultiLanguageWidget($title, 'location[]', $icon, $languages, $core, $widgetSettings, $formDetails, $switchDetails);
        unset($inputs, $icon);

        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'stateUpdate'           => $this->url['manage'].'/state/update',
            'mlsForm'               => $renderedPageDetailsWidget,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/city/process/new', 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
        );
        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            listCitiesAction()
     *                  DOMAIN/{_locale}/manage/city/list
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function listCitiesAction() {
        /** START INITILALIZATION */
        $this->init(1, 'cmsListCities');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/city/new'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/city/new'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $mlsModel->listAllLanguages();

        /** Get all languages of site */
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);

        /** Render Data Table */
        $dtTitle = $this->translator->trans('title.city.list', array(), 'admin');
        $dtSettings = array(
            'ajax'          => false,
            'editable'      => true,
        );
        $dtData = array();
        $dtHeaders = array(
            array('code' => 'id', 'name'        => $this->translator->trans('lbl.id', array(), 'admin')),
            array('code' => 'name', 'name'      => $this->translator->trans('lbl.name.item', array(), 'admin')),
            array('code' => 'state', 'name'     => $this->translator->trans('lbl.state', array(), 'admin')),
            array('code' => 'country', 'name'   => $this->translator->trans('lbl.country', array(), 'admin')),
            array('code' => 'action', 'name'    => ''),
        );
        $dtItems = array();
        $editTxt = $this->translator->trans('btn.edit', array(), 'admin');
        /** Get list of cities */
        $response = $locModel->listCities(null, array('name' => 'asc'));
        $cities = array();
        if(!$response['error']){
            $cities = $response['result']['set'];
        }
        foreach($cities as $aCity){
            $item = new \stdClass();
            $item->DbId = $aCity->getId();
            $item->id = $aCity->getId();
            $item->name = $aCity->getLocalization($this->locale)->getName();
            $item->state = $aCity->getState() == null ? '---' : $aCity->getState()->getLocalization($this->locale)->getName();
            $item->country = $aCity->getCountry() == null ? '---' : $aCity->getCountry()->getLocalization($this->locale)->getName();
            $item->action = '<a href="'.$this->url['base_l'].'/manage/city/edit/'.$aCity->getId().'">'.$editTxt.'</a>';
            $dtItems[] = $item;
        }
        $dtData['headers'] = $dtHeaders;
        $dtData['items'] = $dtItems;
        $dtData['options'] = array(
            array('name' => $this->translator->trans('lbl.delete', array(), 'admin'), 'value' => 'delete'),
        );
        $dtData['modals'] = array(
            array(
                'btn'   => array(
                    'cancel'    => $this->translator->trans('btn.cancel', array(), 'admin'),
                    'confirm'   => $this->translator->trans('btn.confirm', array(), 'admin'),
                ),
                'id'    => 'delete',
                'msg'   =>  $this->translator->trans('msg.prompt.confirm.delete.record', array(), 'admin'),
                'title' =>  $this->translator->trans('lbl.confirm.delete', array(), 'admin'),
            ),
        );
        $dtTxt = array(
            'btn' => array(
                'edit' => $this->translator->trans('btn.edit', array(), 'datatable'),
            ),
            'lbl' => array(
                'find' =>  $this->translator->trans('lbl.find', array(), 'datatable'),
                'first' =>  $this->translator->trans('lbl.first', array(), 'datatable'),
                'info' =>  $this->translator->trans('lbl.info', array(), 'datatable'),
                'last' =>  $this->translator->trans('lbl.last', array(), 'datatable'),
                'limit' =>  $this->translator->trans('lbl.limit', array(), 'datatable'),
                'next' =>  $this->translator->trans('lbl.next', array(), 'datatable'),
                'prev' =>  $this->translator->trans('lbl.prev', array(), 'datatable'),
                'processing' =>  $this->translator->trans('lbl.processing', array(), 'datatable'),
                'recordNotFound' =>  $this->translator->trans('lbl.not_found', array(), 'datatable'),
                'noRecords' =>  $this->translator->trans('lbl.no_records', array(), 'datatable'),
                'numberOfRecords' =>  $this->translator->trans('lbl.number_of_records', array(), 'datatable'),
            ),
        );
        $renderedDataTable = $coreRender->renderDataTable($dtData, $core, $dtTitle, $dtTxt, $dtSettings);
        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'stateUpdate'           => $this->url['manage'].'/state/update',
            'dTable'                => $renderedDataTable,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'modal' => array(
                'form' => array(
                    'action'    => $this->url['base_l'].'/manage/city/delete',
                    'csfr'      => $this->generateCSFR($this->session),
                    'method'    => 'post',
                ),
            ),
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/city/delete', 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
            'js/plugins/datatable/datatable.js',
        );

        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            listCountries()
     *                  DOMAIN/{_locale}/manage/city/list
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function listCountriesAction() {
        /** START INITILALIZATION */
        $this->init(1, 'cmsListCountries');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/country/new'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/country/new'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        /** Render Data Table */
        $dtTitle = $this->translator->trans('title.country.list', array(), 'admin');
        $dtSettings = array(
            'ajax'          => false,
            'editable'      => true,
        );
        $dtData = array();
        $dtHeaders = array(
            array('code' => 'id', 'name'        => $this->translator->trans('lbl.id', array(), 'admin')),
            array('code' => 'name', 'name'      => $this->translator->trans('lbl.name.item', array(), 'admin')),
            array('code' => 'action', 'name'    => ''),
        );
        $dtItems = array();
        $editTxt = $this->translator->trans('btn.edit', array(), 'admin');
        /** Get list of cities */
        $response = $locModel->listCountries(null, array('name' => 'asc'));
        $countries = array();
        if(!$response['error']){
            $countries = $response['result']['set'];
        }
        foreach($countries as $aCountry){
            $item = new \stdClass();
            $item->DbId = $aCountry->getId();
            $item->id = $aCountry->getId();
            $item->name = $aCountry->getLocalization($this->locale)->getName();
            $item->action = '<a href="'.$this->url['base_l'].'/manage/country/edit/'.$aCountry->getId().'">'.$editTxt.'</a>';
            $dtItems[] = $item;
        }
        $dtData['headers'] = $dtHeaders;
        $dtData['items'] = $dtItems;
        $dtData['options'] = array(
            array('name' => $this->translator->trans('lbl.delete', array(), 'admin'), 'value' => 'delete'),
        );
        $dtData['modals'] = array(
            array(
                'btn'   => array(
                    'cancel'    => $this->translator->trans('btn.cancel', array(), 'admin'),
                    'confirm'   => $this->translator->trans('btn.confirm', array(), 'admin'),
                ),
                'id'    => 'delete',
                'msg'   =>  $this->translator->trans('msg.prompt.confirm.delete.record', array(), 'admin'),
                'title' =>  $this->translator->trans('lbl.confirm.delete', array(), 'admin'),
            ),
        );
        $dtTxt = array(
            'btn' => array(
                'edit' => $this->translator->trans('btn.edit', array(), 'datatable'),
            ),
            'lbl' => array(
                'find' =>  $this->translator->trans('lbl.find', array(), 'datatable'),
                'first' =>  $this->translator->trans('lbl.first', array(), 'datatable'),
                'info' =>  $this->translator->trans('lbl.info', array(), 'datatable'),
                'last' =>  $this->translator->trans('lbl.last', array(), 'datatable'),
                'limit' =>  $this->translator->trans('lbl.limit', array(), 'datatable'),
                'next' =>  $this->translator->trans('lbl.next', array(), 'datatable'),
                'prev' =>  $this->translator->trans('lbl.prev', array(), 'datatable'),
                'processing' =>  $this->translator->trans('lbl.processing', array(), 'datatable'),
                'recordNotFound' =>  $this->translator->trans('lbl.not_found', array(), 'datatable'),
                'noRecords' =>  $this->translator->trans('lbl.no_records', array(), 'datatable'),
                'numberOfRecords' =>  $this->translator->trans('lbl.number_of_records', array(), 'datatable'),
            ),
        );
        $renderedDataTable = $coreRender->renderDataTable($dtData, $core, $dtTitle, $dtTxt, $dtSettings);
        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'stateUpdate'           => $this->url['manage'].'/state/update',
            'dTable'                => $renderedDataTable,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'modal' => array(
                'form' => array(
                    'action'    => $this->url['base_l'].'/manage/country/delete',
                    'csfr'      => $this->generateCSFR($this->session),
                    'method'    => 'post',
                ),
            ),
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/country/delete', 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
            'js/plugins/datatable/datatable.js',
        );

        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            listStatesAction()
     *                  DOMAIN/{_locale}/manage/state/list
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function listStatesAction() {
        /** START INITILALIZATION */
        $this->init(1, 'cmsListStates');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/state/list'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/state/list'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $mlsModel->listAllLanguages();

        /** Get all languages of site */
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);

        /** Render Data Table */
        $dtTitle = $this->translator->trans('title.state.list', array(), 'admin');
        $dtSettings = array(
            'ajax'          => false,
            'editable'      => true,
        );
        $dtData = array();
        $dtHeaders = array(
            array('code' => 'id', 'name'        => $this->translator->trans('lbl.id', array(), 'admin')),
            array('code' => 'name', 'name'      => $this->translator->trans('lbl.name.item', array(), 'admin')),
            array('code' => 'country', 'name'   => $this->translator->trans('lbl.country', array(), 'admin')),
            array('code' => 'action', 'name'    => ''),
        );
        $dtItems = array();
        $editTxt = $this->translator->trans('btn.edit', array(), 'admin');
        /** Get list of cities */
        $response = $locModel->listStates(null, array('name' => 'asc'));
        $states = array();
        if(!$response['error']){
            $states = $response['result']['set'];
        }
        foreach($states as $aState){
            $item = new \stdClass();
            $item->DbId = $aState->getId();
            $item->id = $aState->getId();
            $item->name = $aState->getLocalization($this->locale)->getName();
            $item->country = $aState->getCountry()->getLocalization($this->locale)->getName();
            $item->action = '<a href="'.$this->url['base_l'].'/manage/state/edit/'.$aState->getId().'">'.$editTxt.'</a>';
            $dtItems[] = $item;
        }
        $dtData['headers'] = $dtHeaders;
        $dtData['items'] = $dtItems;
        $dtData['options'] = array(
            array('name' => $this->translator->trans('lbl.delete', array(), 'admin'), 'value' => 'delete'),
        );
        $dtData['modals'] = array(
            array(
                'btn'   => array(
                    'cancel'    => $this->translator->trans('btn.cancel', array(), 'admin'),
                    'confirm'   => $this->translator->trans('btn.confirm', array(), 'admin'),
                ),
                'id'    => 'delete',
                'msg'   =>  $this->translator->trans('msg.prompt.confirm.delete.record', array(), 'admin'),
                'title' =>  $this->translator->trans('lbl.confirm.delete', array(), 'admin'),
            ),
        );
        $dtTxt = array(
            'btn' => array(
                'edit' => $this->translator->trans('btn.edit', array(), 'datatable'),
            ),
            'lbl' => array(
                'find' =>  $this->translator->trans('lbl.find', array(), 'datatable'),
                'first' =>  $this->translator->trans('lbl.first', array(), 'datatable'),
                'info' =>  $this->translator->trans('lbl.info', array(), 'datatable'),
                'last' =>  $this->translator->trans('lbl.last', array(), 'datatable'),
                'limit' =>  $this->translator->trans('lbl.limit', array(), 'datatable'),
                'next' =>  $this->translator->trans('lbl.next', array(), 'datatable'),
                'prev' =>  $this->translator->trans('lbl.prev', array(), 'datatable'),
                'processing' =>  $this->translator->trans('lbl.processing', array(), 'datatable'),
                'recordNotFound' =>  $this->translator->trans('lbl.not_found', array(), 'datatable'),
                'noRecords' =>  $this->translator->trans('lbl.no_records', array(), 'datatable'),
                'numberOfRecords' =>  $this->translator->trans('lbl.number_of_records', array(), 'datatable'),
            ),
        );
        $renderedDataTable = $coreRender->renderDataTable($dtData, $core, $dtTitle, $dtTxt, $dtSettings);
        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'stateUpdate'           => $this->url['manage'].'/state/update',
            'dTable'                => $renderedDataTable,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'modal' => array(
                'form' => array(
                    'action'    => $this->url['base_l'].'/manage/state/delete',
                    'csfr'      => $this->generateCSFR($this->session),
                    'method'    => 'post',
                ),
            ),
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/state/delete', 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
            'js/plugins/datatable/datatable.js',
        );

        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            newStateAction()
     *                  DOMAIN/{_locale}/manage/city/new
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function newStateAction() {
        /** START INITILALIZATION */
        $this->init(1, 'cmsNewState');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/state/new'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/state/new'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $mlsModel->listAllLanguages();
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all countries */
        $countries = array();
        $response = $locModel->listCountries(null, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $countries = $response['result']['set'];
        }
        unset($response);
        /**
         * Render Multi Language Form
         */
        $renderedPageDetailsWidget = '';
        $title = $this->translator->trans('title.state.detail', array(), 'admin');
        $languages = array();
        foreach ($siteLanguages as $entity) {
            $languages[] = array('code' => $entity->getIsoCode(), 'name' => $entity->getName());
        }
        $widgetSettings = array(
            'defaultLanguageCode' => $this->site->getLanguage()->getIsoCode(),
            'showActions' => true,
            'showSwitch' => true,
            'showSwitchInfo' => true,
        );
        $switchDetails = array(
            'actions' => array(
                array('id' => 'main-form-action-save', 'style' => 'primary pull-right', 'type' => 'button', 'lbl' => $this->translator->trans('btn.save', array(), 'admin'), 'elementType' => 'button'),
                array(
                    'id' => 'main-form-action-delete',
                    'style' => 'danger pull-left',
                    'type' => 'button',
                    'lbl' =>  $this->translator->trans('btn.delete', array(), 'admin'),
                    'elementType' => 'a',
                    'link' => '#modal-delete',
                    'attributes' => array('role="button"', 'data-toggle="modal"'),
                ),
            ),
            'info' => $this->translator->trans('msg.info.translate', array(), 'admin'),
            'lbl' => array(
                'title' => $this->translator->trans('lbl.translate', array(), 'admin'),
            ),
            'option' => array(
                'off' => array(
                    'name' => $this->translator->trans('lbl.option.off', array(), 'admin'),
                ),
                'on' => array(
                    'name' => $this->translator->trans('lbl.option.on', array(), 'admin'),
                ),
            ),
            'state' => '',
        );
        $inputs = array(
            array(
                'type' => 'textInput',
                'id' => 'name',
                'label' => $this->translator->trans('lbl.name.item', array(), 'admin'),
                'name' => 'name',
            ),
        );
        /**
         * Prepare country options
         */
        $countryOptions = array(
            array(
                'name' => $this->translator->trans('lbl.select_default', array(), 'admin'),
                'selected' => false,
                'value' => -1,
            ),
        );
        foreach ($countries as $country) {
            $countryOptions[] = array(
                'name' => $country['localization'][$this->locale]->getName(),
                'selected' => $country['entity']->getId() == 1 ? true : false,
                'value' => $country['entity']->getId(),
            );
        }
        $formDetails = array(
            'csfr' => $this->generateCSFR($this->session),
            'inputs' => $inputs,
            'title' => array(
                'defaultLanguage' => $this->site->getLanguage()->getName(),
            ),
            'extraInputs' => array(
                array(
                    'id' => 'country',
                    'label' => $this->translator->trans('lbl.country', array(), 'admin'),
                    'name' => 'country',
                    'classes' => array('updateState'),
                    'settings' => array('rowSize' => 12),
                    'size'=>12,
                    'type' => 'dropDown',
                    'options' => $countryOptions,
                ),
            )
        );

        $icon = $this->url['domain'] . '/themes/' . $this->page['entity']->getLayout()->getTheme()->getFolder() . '/img/icons/light-sh/globe.png';
        $renderedPageDetailsWidget = $coreRender->renderMultiLanguageWidget($title, 'location[]', $icon, $languages, $core, $widgetSettings, $formDetails, $switchDetails);
        unset($inputs, $icon);

        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'mlsForm'               => $renderedPageDetailsWidget,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/state/process/new', 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
        );
        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            newCountryAction()
     *                  DOMAIN/{_locale}/manage/country/new
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function newCountryAction() {
        /** START INITILALIZATION */
        $this->init(1, 'cmsNewCountry');
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/city/new'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/city/new'));
        unset($response);
        /** get sidebar & topbar information */
        $sidebar = $this->prepareManagementSidebar();
        $topbar = $this->prepareManagementTopbar();
        /** END :: INITIALIZATION */

        $core = array(
            'locale'    => $this->get('session')->get('_locale'),
            'kernel'    => $this->get('kernel'),
            'theme'     => $this->page['entity']->getLayout()->getTheme()->getFolder(),
            'url'       => $this->url,
        );
        $coreRender = $this->get('corerender.model');
        $mlsModel = $this->get('multilanguagesupport.model');
        $locModel = $this->get('locationmanagement.model');

        $response = $mlsModel->listAllLanguages();
        $siteLanguages = array();
        if(!$response['error']){
            $siteLanguages = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all countries */
        $countries = array();
        $response = $locModel->listCountries(null, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $countries = $response['result']['set'];
        }
        unset($response);
        /** Get a list of all states that belong to city's country */
        $states = array();
        /**
         * Render Multi Language Form
         */
        $renderedPageDetailsWidget = '';
        $title = $this->translator->trans('title.country.detail', array(), 'admin');
        $languages = array();
        foreach ($siteLanguages as $entity) {
            $languages[] = array('code' => $entity->getIsoCode(), 'name' => $entity->getName());
        }
        $widgetSettings = array(
            'defaultLanguageCode' => $this->site->getLanguage()->getIsoCode(),
            'showActions' => true,
            'showSwitch' => true,
            'showSwitchInfo' => true,
        );
        $switchDetails = array(
            'actions' => array(
                array('id' => 'main-form-action-save', 'style' => 'primary pull-right', 'type' => 'button', 'lbl' => $this->translator->trans('btn.save', array(), 'admin'), 'elementType' => 'button'),
            ),
            'info' => $this->translator->trans('msg.info.translate', array(), 'admin'),
            'lbl' => array(
                'title' => $this->translator->trans('lbl.translate', array(), 'admin'),
            ),
            'option' => array(
                'off' => array(
                    'name' => $this->translator->trans('lbl.option.off', array(), 'admin'),
                ),
                'on' => array(
                    'name' => $this->translator->trans('lbl.option.on', array(), 'admin'),
                ),
            ),
            'state' => '',
        );
        $inputs = array(
            array(
                'type' => 'textInput',
                'id' => 'name',
                'label' => $this->translator->trans('lbl.name.item', array(), 'admin'),
                'name' => 'name',
            ),
        );
        $formDetails = array(
            'csfr' => $this->generateCSFR($this->session),
            'inputs' => $inputs,
            'title' => array(
                'defaultLanguage' => $this->site->getLanguage()->getName(),
            )
        );

        $icon = $this->url['domain'] . '/themes/' . $this->page['entity']->getLayout()->getTheme()->getFolder() . '/img/icons/light-sh/globe.png';
        $renderedPageDetailsWidget = $coreRender->renderMultiLanguageWidget($title, 'location[]', $icon, $languages, $core, $widgetSettings, $formDetails, $switchDetails);
        unset($inputs, $icon);

        /**
         * START :: PREPERATION & RENDERING
         */
        $vars = array(
            'stateUpdate'           => $this->url['manage'].'/state/update',
            'mlsForm'               => $renderedPageDetailsWidget,
            'renderedProjectLogo'   => $sidebar['projectLogo'],
            'renderedSidebarNavigation' => $sidebar['navigation'],
            'renderedSidebarSeparator' => $sidebar['separator'],
            'renderedTopNavigation' => $topbar['navigation'],
            'page' => array('form' => array('method' => 'post', 'action' => $this->url['manage'].'/country/process/new', 'csfr' => $this->generateCSFR($this->session))),
        );

        $css = array('css/style.css', 'css/bootstrap-switch.css');
        $js = array(
            'js/libs/modernizr-2.6.2.min.js',
            'js/libs/jquery-1.10.2.js',
            'js/libs/json2.js',
            'js/libs/bootstrap.js',
            'js/plugins/validate/jquery.validate.1.11.1.js',
            'js/plugins/collapsible/collapsible.js',
            'js/plugins/switch/bootstrap-switch.min.js',
            /** Form 2 Json */
            'js/plugins/form2js/form2js.js',
            'js/plugins/form2js/jquery.toObject.js',
            'js/plugins/form2js/js2js',
        );
        return $this->renderPage($vars, $css, $js);

        /**
         * END :: PREPERATION & RENDERING
         */
    }
    /**
     * @name            processCityAction()
     *                  DOMAIN/{_locale}/manage/city/process/{processType/{id}
     *
     *                  Prepares settings screen.
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function processCityAction($processType, $id = -1){
        /** START INITIALIZATION */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        $request = $this->get('request');
        $form = $request->get('mainForm');
        $postedData = json_decode($form['data']['json']);
        /** Check for CSFR */
        if($postedData[0]->csfr != $this->session->get('_csfr')) {
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.security.csfr', array(), 'admin'));
            switch($processType){
                case 'edit':
                    $return = '/edit/'.$id;
                    break;
                case 'new':
                    $return = '/new';
                    break;
            }
            return new RedirectResponse($this->url['base_l'] . '/manage/city'.$return);
        }
        $locModel = $this->get('locationmanagement.model');

        $locationData = $postedData[0]->location[0];

        switch($processType){
            case 'edit':
                $locationData->id = $postedData[0]->entry_id;
                $response = $locModel->updateCity($locationData);
                break;
            case 'new':
                foreach($locationData->local as $localization){
                    $locationData->code_iso = $this->generateUrlKey($localization->name);
                    break;
                }
                if(isset($locationData->id)){
                    unset($locationData->id);
                }
                $response = $locModel->insertCity($locationData);
                break;
        }

        if($response['error']){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.update', array(), 'admin'));
            return new RedirectResponse($this->url['manage'].'/city/new');
        }
        else{
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'success');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.success.update', array(), 'admin'));
            return new RedirectResponse($this->url['manage'].'/city/edit/'.$response['result']['set'][0]->getId());
        }
    }
    /**
     * @name            processCountryAction()
     *                  DOMAIN/{_locale}/manage/country/country/{processType/{id}
     *
     *                  Prepares settings screen.
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function processCountryAction($processType, $id = -1){
        /** START INITIALIZATION */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        $request = $this->get('request');
        $form = $request->get('mainForm');
        $postedData = json_decode($form['data']['json']);
        /** Check for CSFR */
        if($postedData[0]->csfr != $this->session->get('_csfr')) {
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.security.csfr', array(), 'admin'));
            $redirect = '/new';
            if($processType == 'edit'){
                $redirect = '/edit/'.$id;
            }
            return new RedirectResponse($this->url['base_l'] . '/manage/country/'.$redirect);
        }
        $locModel = $this->get('locationmanagement.model');

        $locationData = $postedData[0]->location[0];
        switch($processType){
            case 'edit':
                $locationData->id = $postedData[0]->entry_id;
                $response = $locModel->updateCountry($locationData);
                break;
            case 'new':
                foreach($locationData->local as $localization){
                    $locationData->code_iso = $this->generateUrlKey($localization->name);
                    break;
                }
                if(isset($locationData->id)){
                    unset($locationData->id);
                }
                $response = $locModel->insertCountry($locationData);
                break;
        }

        if($response['error']){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.update', array(), 'admin'));
            return new RedirectResponse($this->url['manage'].'/country/new');
        }
        else{
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'success');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.success.update', array(), 'admin'));
            return new RedirectResponse($this->url['manage'].'/country/edit/'.$response['result']['set'][0]->getId());
        }
    }
    /**
     * @name            processStateAction()
     *                  DOMAIN/{_locale}/manage/state/process/{processType/{id}
     *
     *                  Prepares settings screen.
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function processStateAction($processType, $id = -1){
        /** START INITIALIZATION */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        $request = $this->get('request');
        $form = $request->get('mainForm');
        $postedData = json_decode($form['data']['json']);
        /** Check for CSFR */
        if($postedData[0]->csfr != $this->session->get('_csfr')) {
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.security.csfr', array(), 'admin'));
            switch($processType){
                case 'edit':
                    $return = '/edit/'.$id;
                    break;
                case 'new':
                    $return = '/new';
                    break;
            }
            return new RedirectResponse($this->url['base_l'] . '/manage/state/'.$return);
        }
        $locModel = $this->get('locationmanagement.model');

        $locationData = $postedData[0]->location[0];

        switch($processType){
            case 'edit':
                $locationData->id = $postedData[0]->entry_id;
                $response = $locModel->updateState($locationData);
                break;
            case 'new':
                foreach($locationData->local as $localization){
                    $locationData->code_iso = $this->generateUrlKey($localization->name);
                    break;
                }
                if(isset($locationData->id)){
                    unset($locationData->id);
                }
                $response = $locModel->insertState($locationData);
                break;
        }

        if($response['error']){
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'danger');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.error.update', array(), 'admin'));
            return new RedirectResponse($this->url['manage'].'/state/new');
        }
        else{
            $this->session->getFlashBag()->add('msg.status', true);
            $this->session->getFlashBag()->add('msg.type', 'success');
            $this->session->getFlashBag()->add('msg.content', $this->translator->trans('msg.success.update', array(), 'admin'));
            return new RedirectResponse($this->url['manage'].'/state/edit/'.$response['result']['set'][0]->getId());
        }
    }
    /**
     * @name            updateStatesOnCountryChangeAction()
     *                  DOMAIN/{_locale}/manage/state/update
     *
     * @author          Can Berkol
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function updateStatesOnCountryChangeAction() {
        /** START INITIALIZATION */
        /**
         * @todo multi-site
         */
        $this->init(1);
        /** Redirect if not a manager */
        $response = $this->ifNotManager('login');
        if($response instanceof RedirectResponse){
            return $response;
        }
        /** Redirect if page not found */
        if(!$this->page){
            $this->sm->logAction('page.visit.fail.404', 1, array('route' => '/manage/state/update'));
            $this->redirect('404');
        }
        $this->sm->logAction('page.visit', 1, array('route' => '/manage/state/update'));
        unset($response);
        /** END :: INITIALIZATION */
        $request = $this->get('request');
        $country = (int) $request->get('country');
        $lModel = $this->get('locationmanagement.model');
        $states = array();
        $response = $lModel->listStatesOfCountry($country, array('name' => 'asc'), null, null, true);
        if (!$response['error']) {
            $states = $response['result']['set'];
        }
        $stateOptions = '';
        if(count($states) > 0){
            foreach ($states as $state) {
                $stateOptions .= '<option value="'.$state['entity']->getId().'">'. $state['localization'][$this->locale]->getName().'</option>';
            }
        }
        else{
            $stateOptions .= '<option value="-1">'.$this->translator->trans('msg.error.state.notfound', array(), 'admin').'</option>';
        }
        return new Response($stateOptions);
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Can Berkol
 * 12.03.2014
 * **************************************
 * A deleteCityAction()
 * A deleteCoutnryAction()
 * A deleteStateAction()
 * A editCityAction()
 * A editCountryAction()
 * A editStateAction()
 * A updateStatesOnCountryChangeAction()
 */
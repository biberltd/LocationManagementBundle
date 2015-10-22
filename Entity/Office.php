<?php
/**
 * @name        Office
 * @package		BiberLtd\Bundle\CoreBundle\LocationManagementBundle
 *
 * @author      Can Berkol
 * @author		Murat Ünal
 *
 * @version     1.0.1
 * @date        04.03.2014
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;

/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="office",
 *     indexes={@ORM\Index(name="idx_u_office_url_key", columns={"url_key","site"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_u_office_id", columns={"id"})}
 * )
 */
class Office extends CoreEntity
{   
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $name;

    /** 
     * @ORM\Column(type="string", unique=true, length=155, nullable=false)
     */
    private $url_key;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /** 
     * @ORM\Column(type="decimal", length=10, nullable=true)
     */
    private $lat;

    /** 
     * @ORM\Column(type="decimal", length=10, nullable=true)
     */
    private $lon;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $phone;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $fax;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date_added;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date_updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_removed;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false)
     */
    private $city;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\State")
     * @ORM\JoinColumn(name="state", referencedColumnName="id", onDelete="CASCADE")
     */
    private $state;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $country;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     */
    private $site;
    /**
     * 
     */
    private $extra_info;
    /******************************************************************
     * PUBLIC SET AND GET FUNCTIONS                                   *
     ******************************************************************/

    /**
     * @name            getId()
     *                  Gets $id property.
     * .
     * @author          Murat Ünal
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          integer          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name                  setAddress ()
     *                                   Sets the address property.
     *                                   Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $address
     *
     * @return          object                $this
     */
    public function setAddress($address) {
        if(!$this->setModified('address', $address)->isModified()) {
            return $this;
        }
		$this->address = $address;
		return $this;
    }

    /**
     * @name            getAddress ()
     *                             Returns the value of address property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->address
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @name                  setCity ()
     *                                Sets the city property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $city
     *
     * @return          object                $this
     */
    public function setCity($city) {
        if(!$this->setModified('city', $city)->isModified()) {
            return $this;
        }
		$this->city = $city;
		return $this;
    }

    /**
     * @name            getCity ()
     *                          Returns the value of city property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->city
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @name            setLon ()
     *                  Sets the lon property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $lon
     *
     * @return          object                $this
     */
    public function setLon($lon) {
        if(!$this->setModified('lon', $lon)->isModified()) {
            return $this;
        }
		$this->lon = $lon;
		return $this;
    }

    /**
     * @name            getLon()
     *                  Returns the value of longtitude property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->coor_x
     */
    public function getLon(){
        return $this->lon;
    }

    /**
     * @name            setLat()
     *                  Sets the latitude property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $lat
     *
     * @return          object                $this
     */
    public function setLat($lat) {
        if(!$this->setModified('lat', $lat)->isModified()) {
            return $this;
        }
		$this->lat = $lat;
		return $this;
    }

    /**
     * @name            getLat ()
     *                  Returns the value of coor_y property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->lat
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @name            setCountry ()
     *                  Sets the country property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $country
     *
     * @return          object                $this
     */
    public function setCountry($country) {
        if(!$this->setModified('country', $country)->isModified()) {
            return $this;
        }
		$this->country = $country;
		return $this;
    }

    /**
     * @name            getCountry ()
     *                             Returns the value of country property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @name                  setEmail ()
     *                                 Sets the email property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $email
     *
     * @return          object                $this
     */
    public function setEmail($email) {
        if(!$this->setModified('email', $email)->isModified()) {
            return $this;
        }
		$this->email = $email;
		return $this;
    }

    /**
     * @name            getEmail ()
     *                           Returns the value of email property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @name                  setFax ()
     *                               Sets the fax property.
     *                               Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $fax
     *
     * @return          object                $this
     */
    public function setFax($fax) {
        if(!$this->setModified('fax', $fax)->isModified()) {
            return $this;
        }
		$this->fax = $fax;
		return $this;
    }

    /**
     * @name            getFax ()
     *                         Returns the value of fax property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->fax
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * @name                  setName ()
     *                                Sets the name property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $name
     *
     * @return          object                $this
     */
    public function setName($name) {
        if(!$this->setModified('name', $name)->isModified()) {
            return $this;
        }
		$this->name = $name;
		return $this;
    }

    /**
     * @name            getName ()
     *                          Returns the value of name property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @name                  setPhone ()
     *                                 Sets the phone property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $phone
     *
     * @return          object                $this
     */
    public function setPhone($phone) {
        if(!$this->setModified('phone', $phone)->isModified()) {
            return $this;
        }
		$this->phone = $phone;
		return $this;
    }

    /**
     * @name            getPhone ()
     *                           Returns the value of phone property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->phone
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @name                  setSite ()
     *                                Sets the site property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $site
     *
     * @return          object                $this
     */
    public function setSite($site) {
        if(!$this->setModified('site', $site)->isModified()) {
            return $this;
        }
		$this->site = $site;
		return $this;
    }

    /**
     * @name            getSite ()
     *                          Returns the value of site property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->site
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @name                  setState ()
     *                                 Sets the state property.
     *                                 Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $state
     *
     * @return          object                $this
     */
    public function setState($state) {
        if(!$this->setModified('state', $state)->isModified()) {
            return $this;
        }
		$this->state = $state;
		return $this;
    }

    /**
     * @name            getState ()
     *                           Returns the value of state property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->state
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @name                  setUrlKey ()
     *                                  Sets the url_key property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $url_key
     *
     * @return          object                $this
     */
    public function setUrlKey($url_key) {
        if(!$this->setModified('url_key', $url_key)->isModified()) {
            return $this;
        }
		$this->url_key = $url_key;
		return $this;
    }

    /**
     * @name            getUrlKey ()
     *                            Returns the value of url_key property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->url_key
     */
    public function getUrlKey() {
        return $this->url_key;
    }

    /**
     * @name        getExtraInfo ()
     *
     * @author      Said İmamoğlu
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    /**
     * @name        setExtraInfo ()
     *
     * @author      Said İmamoğlu
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $extra_info
     *
     * @return      $this
     */
    public function setExtraInfo($extra_info)
    {
        if (!$this->setModified('extra_info', $extra_info)->isModified()) {
            return $this;
        }
        $this->extra_info = $extra_info;
        return $this;
    }

}
/**
 * Change Log:
 * **************************************
 * v1.0.2                      Said İmamoğlu
 * 10.07.2015
 * **************************************
 * A getExtraInfo()
 * A setExtraInfo()
 * **************************************
 * v1.0.1                      Can Berkol
 * 04.03.2013
 * **************************************
 * A getLon()
 * A getLan()
 * A setLan()
 * A setLon()
 *
 * **************************************
 * v1.0.0                      Murat Ünal
 * 10.09.2013
 * **************************************
 * A getAddress()
 * A getCity()
 * A getCoorX()
 * A getCoorY()
 * A getCountry()
 * A getEmail()
 * A getFax()
 * A getId()
 * A getName()
 * A getPhone()
 * A getSite()
 * A getState()
 * A getUrlKey()
 *
 * A setAddress()
 * A setCity()
 * A setCoorX()
 * A setCoorY()
 * A setCountry()
 * A setEmail()
 * A setFax()
 * A setName()
 * A setPhone()
 * A setSite()
 * A setState()
 * A setUrlKey()
 *
 */

<?php
/**
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com) (C) 2015
 * @license     GPLv3
 *
 * @date        12.01.2015
 */
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use BiberLtd\Bundle\MemberManagementBundle\Entity\Member;
use BiberLtd\Bundle\SiteManagementBundle\Entity\Site;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;

/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="office",
 *     indexes={@ORM\Index(name="idxUOfficeUrlKey", columns={"url_key","site"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idxUOfficeId", columns={"id"})}
 * )
 */
class Office extends CoreEntity
{   
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     * @var string
     */
    private $name;

    /** 
     * @ORM\Column(type="string", unique=true, length=155, nullable=false)
     * @var string
     */
    private $url_key;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $address;

    /** 
     * @ORM\Column(type="decimal", length=10, nullable=true)
     * @var float
     */
    private $lat;

    /** 
     * @ORM\Column(type="decimal", length=10, nullable=true)
     * @var float
     */
    private $lon;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     * @var string
     */
    private $phone;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     * @var string
     */
    private $fax;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_added;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    public $date_removed;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false)
     * @var City
     */
    private $city;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\State")
     * @ORM\JoinColumn(name="state", referencedColumnName="id", onDelete="CASCADE")
     * @var State
     */
    private $state;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var Country
     */
    private $country;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\SiteManagementBundle\Entity\Site")
     * @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     * @var Site
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MemberManagementBundle\Entity\Member")
     * @ORM\JoinColumn(name="member", referencedColumnName="id", onDelete="CASCADE")
     * @var Member
     */
    private $member;
    /**
     * 
     */
    private $extra_info;

    /**
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress(string $address) {
        if(!$this->setModified('address', $address)->isModified()) {
            return $this;
        }
		$this->address = $address;
		return $this;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\City $city
     *
     * @return $this
     */
    public function setCity(City $city) {
        if(!$this->setModified('city', $city)->isModified()) {
            return $this;
        }
		$this->city = $city;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\City
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param float $lon
     *
     * @return $this
     */
    public function setLon(float $lon) {
        if(!$this->setModified('lon', $lon)->isModified()) {
            return $this;
        }
		$this->lon = $lon;
		return $this;
    }

    /**
     * @return float
     */
    public function getLon(){
        return $this->lon;
    }

    /**
     * @param float $lat
     *
     * @return $this
     */
    public function setLat(float $lat) {
        if(!$this->setModified('lat', $lat)->isModified()) {
            return $this;
        }
		$this->lat = $lat;
		return $this;
    }

    /**
     * @return float
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\Country $country
     *
     * @return $this
     */
    public function setCountry(Country $country) {
        if(!$this->setModified('country', $country)->isModified()) {
            return $this;
        }
		$this->country = $country;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\Country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email) {
        if(!$this->setModified('email', $email)->isModified()) {
            return $this;
        }
		$this->email = $email;
		return $this;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $fax
     *
     * @return $this
     */
    public function setFax(string $fax) {
        if(!$this->setModified('fax', $fax)->isModified()) {
            return $this;
        }
		$this->fax = $fax;
		return $this;
    }

    /**
     * @return string
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name) {
        if(!$this->setModified('name', $name)->isModified()) {
            return $this;
        }
		$this->name = $name;
		return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone) {
        if(!$this->setModified('phone', $phone)->isModified()) {
            return $this;
        }
		$this->phone = $phone;
		return $this;
    }

    /**
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param \BiberLtd\Bundle\SiteManagementBundle\Entity\Site $site
     *
     * @return $this
     */
    public function setSite(Site $site) {
        if(!$this->setModified('site', $site)->isModified()) {
            return $this;
        }
		$this->site = $site;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\SiteManagementBundle\Entity\Site
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\State $state
     *
     * @return $this
     */
    public function setState(State $state) {
        if(!$this->setModified('state', $state)->isModified()) {
            return $this;
        }
		$this->state = $state;
		return $this;
    }

    /**
     * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\State
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param string $url_key
     *
     * @return $this
     */
    public function setUrlKey(string $url_key) {
        if(!$this->setModified('url_key', $url_key)->isModified()) {
            return $this;
        }
		$this->url_key = $url_key;
		return $this;
    }

    /**
     * @return string
     */
    public function getUrlKey() {
        return $this->url_key;
    }
}
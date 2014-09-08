<?php
/**
 * @name        City
 * @package		BiberLtd\Core\LocationManagementBundle
 *
 * @author		Murat Ünal
 * @version     1.0.1
 * @date        10.10.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Core\CoreLocalizableEntity;

/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="city",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_city_id", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idx_u_city_code", columns={"country","state","code"})
 *     }
 * )
 */
class City extends CoreLocalizableEntity
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $code;

    /** 
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\CityLocalization",
     *     mappedBy="city"
     * )
     */
    protected $localizations;

    /** 
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country",
     *     inversedBy="cities"
     * )
     * @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $country;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\State", inversedBy="cities")
     * @ORM\JoinColumn(name="state", referencedColumnName="id", onDelete="CASCADE")
     */
    private $state;
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
     * @return          string          $this->id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name                  setCode ()
     *                                Sets the code property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $code
     *
     * @return          object                $this
     */
    public function setCode($code) {
        if(!$this->setModified('code', $code)->isModified()) {
            return $this;
        }
		$this->code = $code;
		return $this;
    }

    /**
     * @name            getCode ()
     *                          Returns the value of code property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->code
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @name                  setCountry ()
     *                                   Sets the country property.
     *                                   Updates the data only if stored value and value to be set are different.
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
}
/**
 * Change Log:
 * **************************************
 * v1.0.1                      Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCity_localization()
 * A getCode()
 * A getCountry()
 * A getId()
 * A getLocalizations()
 * A getState()
 * A getTaxRates()
 *
 * A setCityLocalization()
 * A setCode()
 * A setCountry()
 * A setLocalizations()
 * A setState()
 * A setTaxRates()
 *
 */

<?php
/**
 * @name        Country
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
namespace BiberLtd\Core\Bundles\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Core\CoreLocalizableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="country",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_country_id", columns={"id"}),
 *         @ORM\UniqueConstraint(name="idx_u_country_code_iso", columns={"code_iso"})
 *     }
 * )
 */
class Country extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=45, nullable=false)
     */
    private $code_iso;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Core\Bundles\LocationManagementBundle\Entity\CountryLocalization",
     *     mappedBy="country"
     * )
     */
    protected $localizations;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Core\Bundles\LocationManagementBundle\Entity\City", mappedBy="country")
     */
    private $cities;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Core\Bundles\LocationManagementBundle\Entity\State", mappedBy="country")
     */
    private $states;

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
     * @name                  setCities ()
     *                                  Sets the cities property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $cities
     *
     * @return          object                $this
     */
    public function setCities($cities) {
        if(!$this->setModified('cities', $cities)->isModified()) {
            return $this;
        }
		$this->cities = $cities;
		return $this;
    }

    /**
     * @name            getCities ()
     *                            Returns the value of cities property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->cities
     */
    public function getCities() {
        return $this->cities;
    }

    /**
     * @name            setCodeIso()
     *                  Sets the code_iso property.
     *                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $code_iso
     *
     * @return          object                $this
     */
    public function setCodeIso($code_iso) {
        if(!$this->setModified('code_iso', $code_iso)->isModified()) {
            return $this;
        }
		$this->code_iso = $code_iso;
		return $this;
    }

    /**
     * @name            getCodeIso()
     *                  Returns the value of code_iso property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->code_iso
     */
    public function getCodeIso() {
        return $this->code_iso;
    }

    /**
     * @name                  setStates ()
     *                                  Sets the states property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $states
     *
     * @return          object                $this
     */
    public function setStates($states) {
        if(!$this->setModified('states', $states)->isModified()) {
            return $this;
        }
		$this->states = $states;
		return $this;
    }

    /**
     * @name            getStates ()
     *                            Returns the value of states property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->states
     */
    public function getStates() {
        return $this->states;
    }
}
/**
 * Change Log:
 * **************************************
 * v1.0.1                      Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCities()
 * A getCodeIso()
 * A getId()
 * A getLocalizations()
 * A getStates()
 *
 * A setCities()
 * A setCodeIso()
 * A setLocalizations()
 * A setStates()
 *
 */

<?php
/**
 * @name        State
 * @package		BiberLtd\Bundle\CoreBundle\LocationManagementBundle
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
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="state",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idx_u_state_code_iso", columns={"code_iso"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="idx_u_state_id", columns={"id"})}
 * )
 */
class State extends CoreLocalizableEntity
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
    private $code_iso;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\StateLocalization",
     *     mappedBy="state"
     * )
     */
    protected $localizations;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City", mappedBy="state")
     */
    private $cities;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country", inversedBy="states")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    private $country;
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
}
/**
 * Change Log:
 * **************************************
 * v1.0.1                      Murat Ünal
 * 10.10.2013
 * **************************************
 * A getCities()
 * A getCodeIso()
 * A getCountry()
 * A getId()
 * A getLocalizations()
 *
 * A setCities()
 * A setCodeIso()
 * A setCountry()
 * A setLocalizations()
 *
 */

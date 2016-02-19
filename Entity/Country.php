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
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;

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
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=45, nullable=false)
     * @var string
     */
    private $code_iso;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\CountryLocalization",
     *     mappedBy="country"
     * )
     * @var array
     */
    protected $localizations;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City", mappedBy="country")
     * @var array
     */
    private $cities;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\State", mappedBy="country")
     * @var array
     */
    private $states;

    /**
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param array $cities
     *
     * @return $this
     */
    public function setCities(array $cities) {
        if(!$this->setModified('cities', $cities)->isModified()) {
            return $this;
        }
		$this->cities = $cities;
		return $this;
    }

    /**
     * @return array
     */
    public function getCities() {
        return $this->cities;
    }

    /**
     * @param string $code_iso
     *
     * @return $this
     */
    public function setCodeIso(string $code_iso) {
        if(!$this->setModified('code_iso', $code_iso)->isModified()) {
            return $this;
        }
		$this->code_iso = $code_iso;
		return $this;
    }

    /**
     * @return string
     */
    public function getCodeIso() {
        return $this->code_iso;
    }

    /**
     * @param array $states
     *
     * @return $this
     */
    public function setStates(array $states) {
        if(!$this->setModified('states', $states)->isModified()) {
            return $this;
        }
		$this->states = $states;
		return $this;
    }

    /**
     * @return array
     */
    public function getStates() {
        return $this->states;
    }
}
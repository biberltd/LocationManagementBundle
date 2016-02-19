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
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @var string
     */
    private $code_iso;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\StateLocalization",
     *     mappedBy="state"
     * )
     * @var array
     */
    protected $localizations;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City", mappedBy="state")
     * @var array
     */
    private $cities;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country", inversedBy="states")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     * @var Country
     */
    private $country;

    /**
     * @return int
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
     * @return mixed
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
}
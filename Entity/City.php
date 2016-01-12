<?php
/**
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com) (C) 2015
 * @license     GPLv3
 *
 * @date        12.01.2016
 */

namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;

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
     * @var int
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=45, nullable=true)
     * @var string
     */
    private $code;

    /** 
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\CityLocalization", mappedBy="city")
     * @var array
     */
    protected $localizations;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country", inversedBy="cities")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var Country
     */
    private $country;

    /** 
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\State", inversedBy="cities")
     * @ORM\JoinColumn(name="state", referencedColumnName="id", onDelete="CASCADE")
     * @var State
     */
    private $state;

    /**
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(\string $code) {
        if(!$this->setModified('code', $code)->isModified()) {
            return $this;
        }
		$this->code = $code;
		return $this;
    }

    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
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
}
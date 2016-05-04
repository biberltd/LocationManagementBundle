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
use BiberLtd\Bundle\CoreBundle\CoreLocalizableEntity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="district",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idxNDistrictZips", columns={"zip"})}
 * )
 */
class District extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $zip;

    /**
     * 
     * @var
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Neighborhood", mappedBy="district") Neighborhood
     */
    private $neighborhood;

    /**
     * 
     * @var array
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\DistrictLocalization",
     *     mappedBy="district"
     * ) array
     */
    protected $localizations;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", onDelete="CASCADE")
     * @var City
     */
    private $city;

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getZip(){
		return $this->zip;
	}

	/**
	 * @param string $zip
	 *
	 * @return $this
	 */
	public function setZip(string $zip){
		if(!$this->setModified('zip', $zip)->isModified()){
			return $this;
		}
		$this->zip = $zip;

		return $this;
	}

	/**
	 * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\Neighborhood
	 */
	public function getNeighborhood(){
		return $this->neighborhood;
	}

	/**
	 * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\Neighborhood $neighborhood
	 *
	 * @return $this
	 */
	public function setNeighborhood(Neighborhood $neighborhood){
		if(!$this->setModified('neighborhood', $neighborhood)->isModified()){
			return $this;
		}
		$this->neighborhood = $neighborhood;

		return $this;
	}

	/**
	 * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\City
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\City $city
	 *
	 * @return $this
	 */
	public function setCity(City $city){
		if(!$this->setModified('city', $city)->isModified()){
			return $this;
		}
		$this->city = $city;

		return $this;
	}
}
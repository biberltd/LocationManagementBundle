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
 * @ORM\Table(name="neighboorhood", options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"})
 */
class Neighborhood extends CoreLocalizableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10, options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @var string
     */
    private $zip;
    /**
     * @var array
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\NeighborhoodLocalization",
     *     mappedBy="neighborhood"
     * ) array
     */
    public $localizations;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\District")
     * @ORM\JoinColumn(name="district", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var District
     */
    private $district;

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
	 * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\District
	 */
	public function getDistrict(){
		return $this->district;
	}

	/**
	 * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\District $district
	 *
	 * @return $this
	 */
	public function setDistrict(District $district){
		if(!$this->setModified('district', $district)->isModified()){
			return $this;
		}
		$this->district = $district;

		return $this;
	}


}
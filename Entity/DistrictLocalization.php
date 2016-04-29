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
use BiberLtd\Bundle\CoreBundle\CoreEntity;
use BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="district_localization", options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"})
 */
class DistrictLocalization extends CoreEntity
{
    /**
     * @ORM\Column(type="string", length=155, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string
     */
    private $url_key;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\District",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="district", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var District
     */
    private $district;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false)
     * @var Language
     */
    private $language;

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name){
        if(!$this->setModified('name', $name)->isModified()){
            return $this;
        }
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlKey(){
        return $this->url_key;
    }

    /**
     * @param string $url_key
     *
     * @return $this
     */
    public function setUrlKey(string $url_key){
        if(!$this->setModified('url_key', $url_key)->isModified()){
            return $this;
        }
        $this->url_key = $url_key;

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

    /**
     * @return \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language
     */
    public function getLanguage(){
        return $this->language;
    }

    /**
     * @param \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language $language
     *
     * @return $this
     */
    public function setLanguage(\BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language $language){
        if(!$this->setModified('language', $language)->isModified()){
            return $this;
        }
        $this->language = $language;

        return $this;
    }
}
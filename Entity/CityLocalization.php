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
 * @ORM\Table(
 *     name="city_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_city_localization", columns={"city","language"}),
 *         @ORM\UniqueConstraint(name="idx_u_city_localization_name", columns={"city","language","name"}),
 *         @ORM\UniqueConstraint(name="idx_u_city_localization_url_key", columns={"city","language","url_key"})
 *     }
 * )
 */
class CityLocalization extends CoreEntity
{
    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     * @var string
     */
    private $name;

    /** 
     * @ORM\Column(type="string", length=155, nullable=false)
     * @var string
     */
    private $url_key;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var Language
     */
    private $language;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\City", inversedBy="localizations")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var City
     */
    private $city;

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
	 * @return mixed
	 */
    public function getCity() {
        return $this->city;
    }

	/**
	 * @param \BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language $language
	 *
	 * @return $this
	 */
    public function setLanguage(Language $language) {
        if(!$this->setModified('language', $language)->isModified()) {
            return $this;
        }
		$this->language = $language;
		return $this;
    }

	/**
	 * @return mixed
	 */
    public function getLanguage() {
        return $this->language;
    }

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
    public function setName(\string $name) {
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
	 * @param string $url_key
	 *
	 * @return $this
	 */
    public function setUrlKey(\string $url_key) {
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
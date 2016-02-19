<?php
/**
 * @name        CountryLocalization
 * @package		BiberLtd\Bundle\CoreBundle\LocationManagementBundle
 *
 * @author		Murat Ünal
 * @version     1.0.0
 * @date        10.09.2013
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;
use BiberLtd\Bundle\CoreBundle\CoreEntity;

/** 
 * @ORM\Entity
 * @ORM\Table(
 *     name="country_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idx_u_country_localization_name", columns={"name","country","language"})},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_u_country_localization", columns={"country","language"}),
 *         @ORM\UniqueConstraint(name="idx_u_country_localization_url_key", columns={"url_key","country","language"})
 *     }
 * )
 */
class CountryLocalization extends CoreEntity
{
    /** 
     * @ORM\Column(type="string", length=45, nullable=false)
     */
    private $name;

    /** 
     * @ORM\Column(type="string", length=155, nullable=false)
     */
    private $url_key;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $language;

    /** 
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Country",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="country", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $country;

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
     * @name                  setLanguage ()
     *                                    Sets the language property.
     *                                    Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $language
     *
     * @return          object                $this
     */
    public function setLanguage($language) {
        if(!$this->setModified('language', $language)->isModified()) {
            return $this;
        }
		$this->language = $language;
		return $this;
    }

    /**
     * @name            getLanguage ()
     *                              Returns the value of language property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @name                  setName ()
     *                                Sets the name property.
     *                                Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $name
     *
     * @return          object                $this
     */
    public function setName($name) {
        if(!$this->setModified('name', $name)->isModified()) {
            return $this;
        }
		$this->name = $name;
		return $this;
    }

    /**
     * @name            getName ()
     *                          Returns the value of name property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @name                  setUrlKey ()
     *                                  Sets the url_key property.
     *                                  Updates the data only if stored value and value to be set are different.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @use             $this->setModified()
     *
     * @param           mixed $url_key
     *
     * @return          object                $this
     */
    public function setUrlKey($url_key) {
        if(!$this->setModified('url_key', $url_key)->isModified()) {
            return $this;
        }
		$this->url_key = $url_key;
		return $this;
    }

    /**
     * @name            getUrlKey ()
     *                            Returns the value of url_key property.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     * @return          mixed           $this->url_key
     */
    public function getUrlKey() {
        return $this->url_key;
    }
    /******************************************************************
     * PUBLIC SET AND GET FUNCTIONS                                   *
     ******************************************************************/

}
/**
 * Change Log:
 * **************************************
 * v1.0.0                      Murat Ünal
 * 10.09.2013
 * **************************************
 * A getCountry()
 * A getLanguage()
 * A getName()
 * A getUrlKey()
 *
 * A setCountry()
 * A setLanguage()
 * A setName()
 * A setUrlKey()
 *
 */
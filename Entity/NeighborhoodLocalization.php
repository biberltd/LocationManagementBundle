<?php
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="neighborhood_localization",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"}
 * )
 */
class NeighborhoodLocalization
{
    /**
     * @ORM\Column(type="string", length=155, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $url_key;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Neighborhood",
     *     inversedBy="localizations"
     * )
     * @ORM\JoinColumn(name="neighborhood", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $neighborhood;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MultiLanguageSupportBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $language;
}

/**
 * 
 * 
 */
class NeigjborhoodLocalization
{
    /**
     * 
     */
    private $name;

    /**
     * 
     */
    private $url_key;

    /**
     * 
     * 
     * 
     */
    private $neighborhood;

    /**
     * 
     * 
     * 
     */
    private $language;
}
<?php
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="neighborhood",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     indexes={@ORM\Index(name="idxNNeighborhoodZips", columns={"zip"})}
 * )
 */
class Neighborhood
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $zip;

    /**
     * @ORM\OneToMany(targetEntity="BiberLtd\Bundle\AddressManagementBundle\Entity\Address", mappedBy="neighborhood")
     */
    private $address;

    /**
     * @ORM\OneToMany(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\NeighborhoodLocalization",
     *     mappedBy="neighborhood"
     * )
     */
    private $localizations;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\District",
     *     inversedBy="neighborhood"
     * )
     * @ORM\JoinColumn(name="district", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $district;
}
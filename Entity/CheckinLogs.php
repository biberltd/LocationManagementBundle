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
use BiberLtd\Bundle\MemberManagementBundle\Entity\Member;
use Doctrine\ORM\Mapping AS ORM;
/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="checkin_logs",
 *     options={"charset":"utf8","collate":"utf8_turkish_ci","engine":"innodb"},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idxUMemberCheckins", columns={"date_checkin","date_checkout","office","member"}),
 *         @ORM\UniqueConstraint(name="idxUCheckinID", columns={"id"})
 *     }
 * )
 */
class CheckinLogs extends CoreEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $date_checkin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $date_checkout;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     * @var float
     */
    private $lat_checkin;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     * @var float
     */
    private $lon_checkin;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     * @var float
     */
    private $lat_checkout;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     * @var float
     */
    private $lon_checkout;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_added;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    public $date_updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    public $date_removed;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"s"})
     * @var string
     */
    private $checkout_type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $extra_info;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Office")
     * @ORM\JoinColumn(name="office", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var Office
     */
    private $office;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MemberManagementBundle\Entity\Member")
     * @ORM\JoinColumn(name="member", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var Member
     */
    private $member;

    /**
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id){
        if(!$this->setModified('id', $id)->isModified()){
            return $this;
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCheckin(){
        return $this->date_checkin;
    }

    /**
     * @param \DateTime $date_checkin
     *
     * @return $this
     */
    public function setDateCheckin(\DateTime $date_checkin){
        if(!$this->setModified('date_checkin', $date_checkin)->isModified()){
            return $this;
        }
        $this->date_checkin = $date_checkin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCheckout(){
        return $this->date_checkout;
    }

    /**
     * @param \DateTime $date_checkout
     *
     * @return $this
     */
    public function setDateCheckout(\DateTime $date_checkout){
        if(!$this->setModified('date_checkout', $date_checkout)->isModified()){
            return $this;
        }
        $this->date_checkout = $date_checkout;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatCheckin(){
        return $this->lat_checkin;
    }

    /**
     * @param float $lat_checkin
     *
     * @return $this
     */
    public function setLatCheckin(float $lat_checkin){
        if(!$this->setModified('lat_checkin', $lat_checkin)->isModified()){
            return $this;
        }
        $this->lat_checkin = $lat_checkin;

        return $this;
    }

    /**
     * @return float
     */
    public function getLonCheckin(){
        return $this->lon_checkin;
    }

    /**
     * @param float $lon_checkin
     *
     * @return $this
     */
    public function setLonCheckin(float $lon_checkin){
        if(!$this->setModified('lon_checkin', $lon_checkin)->isModified()){
            return $this;
        }
        $this->lon_checkin = $lon_checkin;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatCheckout(){
        return $this->lat_checkout;
    }

    /**
     * @param float $lat_checkout
     *
     * @return $this
     */
    public function setLatCheckout(float $lat_checkout){
        if(!$this->setModified('lat_checkout', $lat_checkout)->isModified()){
            return $this;
        }
        $this->lat_checkout = $lat_checkout;

        return $this;
    }

    /**
     * @return float
     */
    public function getLonCheckout(){
        return $this->lon_checkout;
    }

    /**
     * @param float $lon_checkout
     *
     * @return $this
     */
    public function setLonCheckout(float $lon_checkout){
        if(!$this->setModified('lon_checkout', $lon_checkout)->isModified()){
            return $this;
        }
        $this->lon_checkout = $lon_checkout;

        return $this;
    }

    /**
     * @return \BiberLtd\Bundle\LocationManagementBundle\Entity\Office
     */
    public function getOffice(){
        return $this->office;
    }

    /**
     * @param \BiberLtd\Bundle\LocationManagementBundle\Entity\Office $office
     *
     * @return $this
     */
    public function setOffice(Office $office){
        if(!$this->setModified('office', $office)->isModified()){
            return $this;
        }
        $this->office = $office;

        return $this;
    }

    /**
     * @return \BiberLtd\Bundle\MemberManagementBundle\Entity\Member
     */
    public function getMember(){
        return $this->member;
    }

    /**
     * @param \BiberLtd\Bundle\MemberManagementBundle\Entity\Member $member
     *
     * @return $this
     */
    public function setMember(Member $member){
        if(!$this->setModified('member', $member)->isModified()){
            return $this;
        }
        $this->member = $member;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutType(){
        return $this->checkout_type;
    }

    /**
     * @param string $checkoutType
     *
     * @return $this
     */
    public function setCheckoutType(string $checkoutType){
        if(!$this->setModified('checkoutType', $checkoutType)->isModified()){
            return $this;
        }
        $this->checkout_type = $checkoutType;

        return $this;
    }
    /**
     * @name        setEctraInfo ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getExtraInfo(){
        return $this->extra_info;
    }

    /**
     * @name        setEctraInfo ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $info
     *
     * @return      $this
     */
    public function setEctraInfo($info){
        if(!$this->setModified('extra_info', $info)->isModified()){
            return $this;
        }
        $this->extra_info = $info;

        return $this;
    }

}

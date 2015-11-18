<?php
/**
 * @name        CheckinLogs
 * @package		BiberLtd\Bundle\CoreBundle\LocationManagementBundle
 *
 * @author      Can Berkol
 *
 * @version     1.0.0
 * @date        21.10.2015
 *
 * @copyright   Biber Ltd. (http://www.biberltd.com)
 * @license     GPL v3.0
 *
 * @description Model / Entity class.
 *
 */
namespace BiberLtd\Bundle\LocationManagementBundle\Entity;
use BiberLtd\Bundle\CoreBundle\CoreEntity;
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
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date_checkin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_checkout;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $lat_checkin;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $lon_checkin;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $lat_checkout;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $lon_checkout;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $date_added;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $date_updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $date_removed;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"s"})
     */
    private $checkout_type;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\LocationManagementBundle\Entity\Office")
     * @ORM\JoinColumn(name="office", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $office;

    /**
     * @ORM\ManyToOne(targetEntity="BiberLtd\Bundle\MemberManagementBundle\Entity\Member")
     * @ORM\JoinColumn(name="member", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $member;

    /**
     * @name        getId ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @name        setId ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $id
     *
     * @return      $this
     */
    public function setId($id){
        if(!$this->setModified('id', $id)->isModified()){
            return $this;
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @name        getDateCheckin ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getDateCheckin(){
        return $this->date_checkin;
    }

    /**
     * @name        setDateCheckin ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $date_checkin
     *
     * @return      $this
     */
    public function setDateCheckin($date_checkin){
        if(!$this->setModified('date_checkin', $date_checkin)->isModified()){
            return $this;
        }
        $this->date_checkin = $date_checkin;

        return $this;
    }

    /**
     * @name        getDateCheckout ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getDateCheckout(){
        return $this->date_checkout;
    }

    /**
     * @name        setDateCheckout ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $date_checkout
     *
     * @return      $this
     */
    public function setDateCheckout($date_checkout){
        if(!$this->setModified('date_checkout', $date_checkout)->isModified()){
            return $this;
        }
        $this->date_checkout = $date_checkout;

        return $this;
    }

    /**
     * @name        getLatCheckin ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getLatCheckin(){
        return $this->lat_checkin;
    }

    /**
     * @name        setLatCheckin ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $lat_checkin
     *
     * @return      $this
     */
    public function setLatCheckin($lat_checkin){
        if(!$this->setModified('lat_checkin', $lat_checkin)->isModified()){
            return $this;
        }
        $this->lat_checkin = $lat_checkin;

        return $this;
    }

    /**
     * @name        getLonCheckin ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getLonCheckin(){
        return $this->lon_checkin;
    }

    /**
     * @name              setLonCheckin ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $lon_checkin
     *
     * @return      $this
     */
    public function setLonCheckin($lon_checkin){
        if(!$this->setModified('lon_checkin', $lon_checkin)->isModified()){
            return $this;
        }
        $this->lon_checkin = $lon_checkin;

        return $this;
    }

    /**
     * @name        getLatCheckout ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getLatCheckout(){
        return $this->lat_checkout;
    }

    /**
     * @name        setLatCheckout ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $lat_checkout
     *
     * @return      $this
     */
    public function setLatCheckout($lat_checkout){
        if(!$this->setModified('lat_checkout', $lat_checkout)->isModified()){
            return $this;
        }
        $this->lat_checkout = $lat_checkout;

        return $this;
    }

    /**
     * @name        getLonCheckout ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getLonCheckout(){
        return $this->lon_checkout;
    }

    /**
     * @name              setLonCheckout ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $lon_checkout
     *
     * @return      $this
     */
    public function setLonCheckout($lon_checkout){
        if(!$this->setModified('lon_checkout', $lon_checkout)->isModified()){
            return $this;
        }
        $this->lon_checkout = $lon_checkout;

        return $this;
    }

    /**
     * @name        getOffice ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getOffice(){
        return $this->office;
    }

    /**
     * @name              setOffice ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $office
     *
     * @return      $this
     */
    public function setOffice($office){
        if(!$this->setModified('office', $office)->isModified()){
            return $this;
        }
        $this->office = $office;

        return $this;
    }

    /**
     * @name        getMember ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getMember(){
        return $this->member;
    }

    /**
     * @name              setMember ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $member
     *
     * @return      $this
     */
    public function setMember($member){
        if(!$this->setModified('member', $member)->isModified()){
            return $this;
        }
        $this->member = $member;

        return $this;
    }

    /**
     * @name        getCheckoutType ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return      mixed
     */
    public function getCheckoutType(){
        return $this->checkout_type;
    }

    /**
     * @name        setCheckoutType ()
     *
     * @author      Can Berkol
     *
     * @since       1.0.0
     * @version     1.0.0
     *
     * @param       mixed $checkoutType
     *
     * @return      $this
     */
    public function setCheckoutType($checkoutType){
        if(!$this->setModified('checkoutType', $checkoutType)->isModified()){
            return $this;
        }
        $this->checkout_type = $checkoutType;

        return $this;
    }


}

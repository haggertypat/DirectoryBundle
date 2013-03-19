<?php

namespace CCETC\DirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Orm\MappedSuperclass
 */
class BaseListing extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var date $datetimeCreated
     *
     * @ORM\Column(name="datetimeCreated", type="datetime", nullable=true)
     */
    private $datetimeCreated;

    /**
     * @var date $datetimeLastUpdated
     *
     * @ORM\Column(name="datetimeLastUpdated", type="datetime", nullable=true)
     */
    private $datetimeLastUpdated;    
    
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contactName", type="string", length=255, nullable=true)
     */
    private $contactName;    
    /**
     * @var string
     *
     * @ORM\Column(name="secondaryContactName", type="string", length=255, nullable=true)
     */
    private $secondaryContactName;    

    /**
     * @var string
     *
     * @ORM\Column(name="addressLabel", type="string", length=255, nullable=true)
     */
    private $addressLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=2, nullable=true)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="zip", type="integer", nullable=true)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="county", type="string", length=255, nullable=true)
     */
    private $county;
    
    /**
     * @var string
     *
     * @ORM\Column(name="addressLabel2", type="string", length=255, nullable=true)
     */
    private $addressLabel2;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city2", type="string", length=255, nullable=true)
     */
    private $city2;

    /**
     * @var string
     *
     * @ORM\Column(name="state2", type="string", length=2, nullable=true)
     */
    private $state2;

    /**
     * @var integer
     *
     * @ORM\Column(name="zip2", type="integer", nullable=true)
     */
    private $zip2;

    /**
     * @var string
     *
     * @ORM\Column(name="county2", type="string", length=255, nullable=true, nullable=true)
     */
    private $county2;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=500, nullable=true)
     */
    private $website;

    /**
     * @var boolean $spam
     *
     * @ORM\Column(name="spam", type="boolean", nullable=true)
     */
    private $spam = false;
    
    /**
     * @var boolean $approved
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved = true;   
    
    /**
     * @var string
     *
     * @ORM\Column(name="primaryEmail", type="string", length=255, nullable=true)
     */
    private $primaryEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryEmailType", type="string", length=255, nullable=true)
     */
    private $primaryEmailType;

    /**
     * @var string
     *
     * @ORM\Column(name="secondaryEmail", type="string", length=255, nullable=true)
     */
    private $secondaryEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="secondaryEmailType", type="string", length=255, nullable=true)
     */
    private $secondaryEmailType;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryPhone", type="string", length=255, nullable=true)
     */
    private $primaryPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryPhoneType", type="string", length=255, nullable=true)
     */
    private $primaryPhoneType;

    /**
     * @var string
     *
     * @ORM\Column(name="secondaryPhone", type="string", length=255, nullable=true)
     */
    private $secondaryPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="secondaryPhoneType", type="string", length=255, nullable=true)
     */
    private $secondaryPhoneType;
    
    /**
     * @var string
     *
     * @ORM\Column(name="preferredMethodOfContact", type="string", length=255, nullable=true)
     */
    private $preferredMethodOfContact; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="photoFilename", type="string", length=255, nullable=true)
     */
    private $photoFilename;
    protected $photoFile;        
    
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Name
     *
     * @param string $Name
     * @return Listing
     */
    public function setName($Name)
    {
        $this->name = $Name;
    
        return $this;
    }

    /**
     * Get Name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Listing
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function getGoogleMapsAddress()
    {
        return $this->address.' '.$this->city.' '.$this->state.' '.$this->zip;
    }
    
    /**
     * Set city
     *
     * @param string $city
     * @return Listing
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Listing
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param integer $zip
     * @return Listing
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    
        return $this;
    }

    /**
     * Get zip
     *
     * @return integer 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set county
     *
     * @param string $county
     * @return Listing
     */
    public function setCounty($county)
    {
        $this->county = $county;
    
        return $this;
    }

    /**
     * Get county
     *
     * @return string 
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Listing
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->addHTTP($this->website);
    }

    public function addHTTP($url) {
        if (trim($url) != "" && !preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
    
    /**
     * Set primaryEmail
     *
     * @param string $primaryEmail
     * @return Listing
     */
    public function setPrimaryEmail($primaryEmail)
    {
        $this->primaryEmail = $primaryEmail;
    
        return $this;
    }

    /**
     * Get primaryEmail
     *
     * @return string 
     */
    public function getPrimaryEmail()
    {
        return $this->primaryEmail;
    }

    /**
     * Set primaryEmailType
     *
     * @param string $primaryEmailType
     * @return Listing
     */
    public function setPrimaryEmailType($primaryEmailType)
    {
        $this->primaryEmailType = $primaryEmailType;
    
        return $this;
    }

    /**
     * Get primaryEmailType
     *
     * @return string 
     */
    public function getPrimaryEmailType()
    {
        return $this->primaryEmailType;
    }

    /**
     * Set secondaryEmail
     *
     * @param string $secondaryEmail
     * @return Listing
     */
    public function setSecondaryEmail($secondaryEmail)
    {
        $this->secondaryEmail = $secondaryEmail;
    
        return $this;
    }

    /**
     * Get secondaryEmail
     *
     * @return string 
     */
    public function getSecondaryEmail()
    {
        return $this->secondaryEmail;
    }

    /**
     * Set secondaryEmailType
     *
     * @param string $secondaryEmailType
     * @return Listing
     */
    public function setSecondaryEmailType($secondaryEmailType)
    {
        $this->secondaryEmailType = $secondaryEmailType;
    
        return $this;
    }

    /**
     * Get secondaryEmailType
     *
     * @return string 
     */
    public function getSecondaryEmailType()
    {
        return $this->secondaryEmailType;
    }

    /**
     * Set primaryPhone
     *
     * @param string $primaryPhone
     * @return Listing
     */
    public function setPrimaryPhone($primaryPhone)
    {
        $this->primaryPhone = $primaryPhone;
    
        return $this;
    }

    /**
     * Get primaryPhone
     *
     * @return string 
     */
    public function getPrimaryPhone()
    {
        return $this->primaryPhone;
    }

    /**
     * Set primaryPhoneType
     *
     * @param string $primaryPhoneType
     * @return Listing
     */
    public function setPrimaryPhoneType($primaryPhoneType)
    {
        $this->primaryPhoneType = $primaryPhoneType;
    
        return $this;
    }

    /**
     * Get primaryPhoneType
     *
     * @return string 
     */
    public function getPrimaryPhoneType()
    {
        return $this->primaryPhoneType;
    }

    /**
     * Set secondaryPhone
     *
     * @param string $secondaryPhone
     * @return Listing
     */
    public function setSecondaryPhone($secondaryPhone)
    {
        $this->secondaryPhone = $secondaryPhone;
    
        return $this;
    }

    /**
     * Get secondaryPhone
     *
     * @return string 
     */
    public function getSecondaryPhone()
    {
        return $this->secondaryPhone;
    }

    /**
     * Set secondaryPhoneType
     *
     * @param string $secondaryPhoneType
     * @return Listing
     */
    public function setSecondaryPhoneType($secondaryPhoneType)
    {
        $this->secondaryPhoneType = $secondaryPhoneType;
    
        return $this;
    }

    /**
     * Get secondaryPhoneType
     *
     * @return string 
     */
    public function getSecondaryPhoneType()
    {
        return $this->secondaryPhoneType;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Listing
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getDescriptionPreview()
    {
        $max = 200;
        
        if(strlen($this->description) < $max) {
            return $this->description;
        } else {
            return substr($this->description, 0, $max).'...';
        }
    }
    
    /**
     * Set photoFilename
     *
     * @param string $photoFilename
     * @return Listing
     */
    public function setPhotoFilename($photoFilename)
    {
        $this->photoFilename = $photoFilename;
    
        return $this;
    }

    /**
     * Get photoFilename
     *
     * @return string 
     */
    public function getPhotoFilename()
    {
        return $this->photoFilename;
    }
    /**
     * Set photoFile
     *
     * @param string $photoFile
     * @return Listing
     */
    public function setPhotoFile($photoFile)
    {
        $this->photoFile = $photoFile;
    
        return $this;
    }

    /**
     * Get photoFile
     *
     * @return string 
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return Listing
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    
        return $this;
    }

    /**
     * Get contactName
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contactName;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->photoFilename ? null : $this->getUploadRootDir() . '/' . $this->photoFilename;
    }

    public function getWebPath()
    {
        return null === $this->photoFilename ? null : $this->getUploadDir() . '/' . $this->photoFilename;
    }

    protected function getUploadDir()
    {
        return 'uploads/';
    }
    
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }    

    public function uploadPhoto()
    {
        // the file property can be empty if the field is not required
        if(null === $this->photoFile)
        {
            return;
        }

        $filename = rand().$this->photoFile->getClientOriginalName();
        
        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the target filename to move to
        $this->photoFile->move($this->getUploadDir(), $filename);

        // set the path property to the filename where you'ved saved the file
        $this->setPhotoFilename($filename);

        // clean up the file property as you won't need it anymore
        $this->photoFile = null;
    }    

    /**
     * Set spam
     *
     * @param boolean $spam
     * @return Listing
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
    
        return $this;
    }

    /**
     * Get spam
     *
     * @return boolean 
     */
    public function getSpam()
    {
        return $this->spam;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return Listing
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    
        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set datetimeCreated
     *
     * @param \DateTime $datetimeCreated
     * @return Listing
     */
    public function setDatetimeCreated($datetimeCreated)
    {
        $this->datetimeCreated = $datetimeCreated;
    
        return $this;
    }

    /**
     * Get datetimeCreated
     *
     * @return \DateTime 
     */
    public function getDatetimeCreated()
    {
        return $this->datetimeCreated;
    }

    /**
     * Set datetimeLastUpdated
     *
     * @param \DateTime $datetimeLastUpdated
     * @return Listing
     */
    public function setDatetimeLastUpdated($datetimeLastUpdated)
    {
        $this->datetimeLastUpdated = $datetimeLastUpdated;
    
        return $this;
    }

    /**
     * Get datetimeLastUpdated
     *
     * @return \DateTime 
     */
    public function getDatetimeLastUpdated()
    {
        return $this->datetimeLastUpdated;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return Listing
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    
        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city2
     *
     * @param string $city2
     * @return Listing
     */
    public function setCity2($city2)
    {
        $this->city2 = $city2;
    
        return $this;
    }

    /**
     * Get city2
     *
     * @return string 
     */
    public function getCity2()
    {
        return $this->city2;
    }

    /**
     * Set state2
     *
     * @param string $state2
     * @return Listing
     */
    public function setState2($state2)
    {
        $this->state2 = $state2;
    
        return $this;
    }

    /**
     * Get state2
     *
     * @return string 
     */
    public function getState2()
    {
        return $this->state2;
    }

    /**
     * Set zip2
     *
     * @param integer $zip2
     * @return Listing
     */
    public function setZip2($zip2)
    {
        $this->zip2 = $zip2;
    
        return $this;
    }

    /**
     * Get zip2
     *
     * @return integer 
     */
    public function getZip2()
    {
        return $this->zip2;
    }

    /**
     * Set county2
     *
     * @param string $county2
     * @return Listing
     */
    public function setCounty2($county2)
    {
        $this->county2 = $county2;
    
        return $this;
    }

    /**
     * Get county2
     *
     * @return string 
     */
    public function getCounty2()
    {
        return $this->county2;
    }

    /**
     * Set addressLabel
     *
     * @param string $addressLabel
     * @return Listing
     */
    public function setAddressLabel($addressLabel)
    {
        $this->addressLabel = $addressLabel;
    
        return $this;
    }

    /**
     * Get addressLabel
     *
     * @return string 
     */
    public function getAddressLabel()
    {
        return $this->addressLabel;
    }

    /**
     * Set addressLabel2
     *
     * @param string $addressLabel2
     * @return Listing
     */
    public function setAddressLabel2($addressLabel2)
    {
        $this->addressLabel2 = $addressLabel2;
    
        return $this;
    }

    /**
     * Get addressLabel2
     *
     * @return string 
     */
    public function getAddressLabel2()
    {
        return $this->addressLabel2;
    }
    
     /**
     * Set preferredMethodOfContact
     *
     * @param string $preferredMethodOfContact
     * @return Listing
     */
    public function setPreferredMethodOfContact($preferredMethodOfContact)
    {
        $this->preferredMethodOfContact = $preferredMethodOfContact;
    
        return $this;
    }

    /**
     * Get preferredMethodOfContact
     *
     * @return string 
     */
    public function getPreferredMethodOfContact()
    {
        return $this->preferredMethodOfContact;
    }
}
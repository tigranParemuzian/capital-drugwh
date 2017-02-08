<?php

namespace AppBundle\Entity;

use AppBundle\Traits\File;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserSettings
 *
 * @ORM\Table(name="user_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserSettingsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserSettings
{
    /**
     * const of Ownership Types
     */
    const PROPRIETORSHIP = 1;
    const PARTNERSHIP= 2;
    const CORPORATION= 3;
    const LIMITED_LIABILITY_COMPANY = 4;
    const STATE_OF_INCORPORATION = 5;


    use  File;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", mappedBy="userSettings")
     */
    private $user;

    /**
     * @var
     * @ORM\Column(name="trade_name", type="string", length=100)
     * @Assert\NotBlank(message="Business trade name can't be null")
     * @Assert\Length(min = 5,
     *      max = 100,
     *      minMessage = "Your business trade name must be at least {{ limit }} characters long",
     *      maxMessage = "Your Business trade name cannot be longer than {{ limit }} characters")
     */
    private $tradeName;

    /**
     * @var
     * @ORM\Column(name="trade_address", type="string", length=100)
     * @Assert\NotBlank(message="Business trade address can't be null")
     * @Assert\Length(min = 5,
     *      max = 100,
     *      minMessage = "Your business trade address must be at least {{ limit }} characters long",
     *      maxMessage = "Your Business trade address cannot be longer than {{ limit }} characters")
     */
    private $tradeAddress;

    /**
     * @var $ownershipType
     * @ORM\Column(name="ownership_type", type="smallint")
     * @Assert\NotBlank(message="Ownership Type can't be null")
     */
    private $ownershipType;

    /**
     * @var $corporateName
     * @ORM\Column(name="corporate_name", type="string")
     */
    private $corporateName;

    /**
     *
     * @var $taxId
     * @ORM\Column(name="tax_id", type="string", length=50, nullable=true)
     */
    private $taxId;

    /**
     *
     * @var $statePharmacyLicense
     * @ORM\Column(name="state_pharmacy_license", type="string", length=50)
     */
    private $statePharmacyLicense;

    /**
     *
     * @var $deaLicense
     * @ORM\Column(name="dea_license", type="string", length=50, nullable=true)
     */
    private $deaLicense;

    /**
     *
     * @var $stateControlledSubstanceLicense
     * @ORM\Column(name="state_controlled_substance_license", type="string", length=50, nullable=true)
     */
    private $stateControlledSubstanceLicense;

    /**
     * @var $shippingAddressStreet
     * @ORM\Column(name="shipping_address_street", type="string")
     */
    private $shippingAddressStreet;
    /**
     * @var $shippingAddressCity
     * @ORM\Column(name="shipping_address_city", type="string")
     */
    private $shippingAddressCity;

    /**
     * @var $shippingAddressState
     * @ORM\Column(name="shipping_address_state", type="string")
     */
    private $shippingAddressState;

    /**
     * @var $shippingAddressZip
     * @ORM\Column(name="shipping_address_zip", type="string", length=5)
     */
    private $shippingAddressZip;

    /**
     * @var $primeryBusinessContact
     * @ORM\Column(name="primery_business_contact", type="string")
     */
    private $primeryBusinessContact;

    /**
     * @var $primeryBusinessContactTitle
     * @ORM\Column(name="primery_business_contact_title", type="string")
     */
    private $primeryBusinessContactTitle;

    /**
     * @var $primeryBusinessContactEmail
     * @ORM\Column(name="primery_business_contact_email", type="string")
     */
    private $primeryBusinessContactEmail;

    /**
     * @var $primeryPurchasingContact
     * @ORM\Column(name="primery_purchasing_contact", type="string")
     */
    private $primeryPurchasingContact;

    /**
     * @var $primeryPurchasingContactTitle
     * @ORM\Column(name="primery_purchasing_contact_title", type="string")
     */
    private $primeryPurchasingContactTitle;

    /**
     * @var $primeryPurchasingContactEmail
     * @ORM\Column(name="primery_purchasing_contact_email", type="string")
     */
    private $primeryPurchasingContactEmail;

    /**
     * @var $businessTelephone
     * @ORM\Column(name="business_telephone", type="string")
     */
    private $businessTelephone;

    /**
     * @var $businessFax
     * @ORM\Column(name="business_fax", type="string")
     */
    private $businessFax;

    /**
     * @var $supplierWholesalers
     * @ORM\Column(name="supplier_wholesalers", type="string", nullable=true)
     */
    private $supplierWholesalers;

    /**
     * @var $supplierWholesalersAddress
     * @ORM\Column(name="supplier_wholesalers_address", type="string", nullable=true)
     */
    private $supplierWholesalersAddress;

    /**
     * @var $supplierWholesalersPhone
     * @ORM\Column(name="supplier_wholesalers_phone", type="string", nullable=true)
     */
    private $supplierWholesalersPhone;

    /**
     * @var $supplierWholesalersContact
     * @ORM\Column(name="supplier_wholesalers_contact", type="string", nullable=true)
     */
    private $supplierWholesalersContact;

    /**
     * @var $supplierWholesalers2
     * @ORM\Column(name="supplier_wholesalers_2", type="string", nullable=true)
     */
    private $supplierWholesalers2;

    /**
     * @var $supplierWholesalersAddress2
     * @ORM\Column(name="supplier_wholesalers_address_2", type="string", nullable=true)
     */
    private $supplierWholesalersAddress2;

    /**
     * @var $supplierWholesalersPhone2
     * @ORM\Column(name="supplier_wholesalers_phone_2", type="string", nullable=true)
     */
    private $supplierWholesalersPhone2;

    /**
     * @var $supplierWholesalersContact2
     * @ORM\Column(name="supplier_wholesalers_contact_2", type="string", nullable=true)
     */
    private $supplierWholesalersContact2;

    /**
     * @var $supplierWholesalers3
     * @ORM\Column(name="supplier_wholesalers_3", type="string", nullable=true)
     */
    private $supplierWholesalers3;

    /**
     * @var $supplierWholesalersAddress3
     * @ORM\Column(name="supplier_wholesalers_address_3", type="string", nullable=true)
     */
    private $supplierWholesalersAddress3;

    /**
     * @var $supplierWholesalersPhone3
     * @ORM\Column(name="supplier_wholesalers_phone_3", type="string", nullable=true)
     */
    private $supplierWholesalersPhone3;

    /**
     * @var $supplierWholesalersContact3
     * @ORM\Column(name="supplier_wholesalers_contact_3", type="string", nullable=true)
     */
    private $supplierWholesalersContact3;

    /**
     *
     * @var $bankName
     * @ORM\Column(name="bank_name", type="string", nullable=true)
     */
    private $bankName;

    /**
     *
     * @var $bankCity
     * @ORM\Column(name="bank_city", type="string", nullable=true)
     */
    private $bankCity;

    /**
     * @var $bankState
     * @ORM\Column(name="bank_state", type="string", nullable=true)
     */
    private $bankState;

    /**
     *
     * @var $bankPhone
     * @ORM\Column(name="bank_phone", type="string", nullable=true)
     */
    private $bankPhone;

    /**
     * @var $bankAccount
     * @ORM\Column(name="bank_account", type="string", nullable=true)
     */
    private $bankAccount;

    /**
     *
     * @var $bankName2
     * @ORM\Column(name="bank_name_2", type="string", length=50, nullable=true)
     */
    private $bankName2;

    /**
     *
     * @var $bankCity2
     * @ORM\Column(name="bank_city_2", type="string", length=50, nullable=true)
     */
    private $bankCity2;

    /**
     * @var $bankState2
     * @ORM\Column(name="bank_state_2", type="string", length=50, nullable=true)
     */
    private $bankState2;

    /**
     *
     * @var $bankPhone2
     * @ORM\Column(name="bank_phone_2", type="string", length=50, nullable=true)
     */
    private $bankPhone2;

    /**
     * @var $bankAccount2
     * @ORM\Column(name="bank_account_2", type="string", length=50, nullable=true)
     */
    private $bankAccount2;

    /**
     * @var $businessType
     * @ORM\Column(name="business_type", type="text")
     */
    private $businessType;

    /**
     * @var $businessYear
     * @ORM\Column(name="business_year", type="string")
     */
    private $businessYear;

    /**
     * @var $businessMonthlyVolume
     * @ORM\Column(name="business_monthly_volume", type="string")
     */
    private $businessMonthlyVolume;

    /**
     * @var $businessMonthlyGenericSales
     * @ORM\Column(name="business_monthly_generic_sales", type="string")
     */
    private $businessMonthlyGenericSales;

    /**
     * @var $businessMonthlyBrandSales
     * @ORM\Column(name="business_monthly_brand_sales", type="string")
     */
    private $businessMonthlyBrandSales;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserSettings
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tradeName
     *
     * @param string $tradeName
     *
     * @return UserSettings
     */
    public function setTradeName($tradeName)
    {
        $this->tradeName = $tradeName;

        return $this;
    }

    /**
     * Get tradeName
     *
     * @return string
     */
    public function getTradeName()
    {
        return $this->tradeName;
    }

    /**
     * Set tradeAddress
     *
     * @param string $tradeAddress
     *
     * @return UserSettings
     */
    public function setTradeAddress($tradeAddress)
    {
        $this->tradeAddress = $tradeAddress;

        return $this;
    }

    /**
     * Get tradeAddress
     *
     * @return string
     */
    public function getTradeAddress()
    {
        return $this->tradeAddress;
    }

    /**
     * Set corporateName
     *
     * @param string $corporateName
     *
     * @return UserSettings
     */
    public function setCorporateName($corporateName)
    {
        $this->corporateName = $corporateName;

        return $this;
    }

    /**
     * Get corporateName
     *
     * @return string
     */
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * Set taxId
     *
     * @param string $taxId
     *
     * @return UserSettings
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;

        return $this;
    }

    /**
     * Get taxId
     *
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * Set statePharmacyLicense
     *
     * @param string $statePharmacyLicense
     *
     * @return UserSettings
     */
    public function setStatePharmacyLicense($statePharmacyLicense)
    {
        $this->statePharmacyLicense = $statePharmacyLicense;

        return $this;
    }

    /**
     * Get statePharmacyLicense
     *
     * @return string
     */
    public function getStatePharmacyLicense()
    {
        return $this->statePharmacyLicense;
    }

    /**
     * Set deaLicense
     *
     * @param string $deaLicense
     *
     * @return UserSettings
     */
    public function setDeaLicense($deaLicense)
    {
        $this->deaLicense = $deaLicense;

        return $this;
    }

    /**
     * Get deaLicense
     *
     * @return string
     */
    public function getDeaLicense()
    {
        return $this->deaLicense;
    }

    /**
     * Set stateControlledSubstanceLicense
     *
     * @param string $stateControlledSubstanceLicense
     *
     * @return UserSettings
     */
    public function setStateControlledSubstanceLicense($stateControlledSubstanceLicense)
    {
        $this->stateControlledSubstanceLicense = $stateControlledSubstanceLicense;

        return $this;
    }

    /**
     * Get stateControlledSubstanceLicense
     *
     * @return string
     */
    public function getStateControlledSubstanceLicense()
    {
        return $this->stateControlledSubstanceLicense;
    }

    /**
     * Set primeryBusinessContact
     *
     * @param string $primeryBusinessContact
     *
     * @return UserSettings
     */
    public function setPrimeryBusinessContact($primeryBusinessContact)
    {
        $this->primeryBusinessContact = $primeryBusinessContact;

        return $this;
    }

    /**
     * Get primeryBusinessContact
     *
     * @return string
     */
    public function getPrimeryBusinessContact()
    {
        return $this->primeryBusinessContact;
    }

    /**
     * Set primeryBusinessContactTitle
     *
     * @param string $primeryBusinessContactTitle
     *
     * @return UserSettings
     */
    public function setPrimeryBusinessContactTitle($primeryBusinessContactTitle)
    {
        $this->primeryBusinessContactTitle = $primeryBusinessContactTitle;

        return $this;
    }

    /**
     * Get primeryBusinessContactTitle
     *
     * @return string
     */
    public function getPrimeryBusinessContactTitle()
    {
        return $this->primeryBusinessContactTitle;
    }

    /**
     * Set primeryBusinessContactEmail
     *
     * @param string $primeryBusinessContactEmail
     *
     * @return UserSettings
     */
    public function setPrimeryBusinessContactEmail($primeryBusinessContactEmail)
    {
        $this->primeryBusinessContactEmail = $primeryBusinessContactEmail;

        return $this;
    }

    /**
     * Get primeryBusinessContactEmail
     *
     * @return string
     */
    public function getPrimeryBusinessContactEmail()
    {
        return $this->primeryBusinessContactEmail;
    }

    /**
     * Set primeryPurchasingContact
     *
     * @param string $primeryPurchasingContact
     *
     * @return UserSettings
     */
    public function setPrimeryPurchasingContact($primeryPurchasingContact)
    {
        $this->primeryPurchasingContact = $primeryPurchasingContact;

        return $this;
    }

    /**
     * Get primeryPurchasingContact
     *
     * @return string
     */
    public function getPrimeryPurchasingContact()
    {
        return $this->primeryPurchasingContact;
    }

    /**
     * Set primeryPurchasingContactTitle
     *
     * @param string $primeryPurchasingContactTitle
     *
     * @return UserSettings
     */
    public function setPrimeryPurchasingContactTitle($primeryPurchasingContactTitle)
    {
        $this->primeryPurchasingContactTitle = $primeryPurchasingContactTitle;

        return $this;
    }

    /**
     * Get primeryPurchasingContactTitle
     *
     * @return string
     */
    public function getPrimeryPurchasingContactTitle()
    {
        return $this->primeryPurchasingContactTitle;
    }

    /**
     * Set primeryPurchasingContactEmail
     *
     * @param string $primeryPurchasingContactEmail
     *
     * @return UserSettings
     */
    public function setPrimeryPurchasingContactEmail($primeryPurchasingContactEmail)
    {
        $this->primeryPurchasingContactEmail = $primeryPurchasingContactEmail;

        return $this;
    }

    /**
     * Get primeryPurchasingContactEmail
     *
     * @return string
     */
    public function getPrimeryPurchasingContactEmail()
    {
        return $this->primeryPurchasingContactEmail;
    }

    /**
     * Set businessTelephone
     *
     * @param string $businessTelephone
     *
     * @return UserSettings
     */
    public function setBusinessTelephone($businessTelephone)
    {
        $this->businessTelephone = $businessTelephone;

        return $this;
    }

    /**
     * Get businessTelephone
     *
     * @return string
     */
    public function getBusinessTelephone()
    {
        return $this->businessTelephone;
    }

    /**
     * Set businessFax
     *
     * @param string $businessFax
     *
     * @return UserSettings
     */
    public function setBusinessFax($businessFax)
    {
        $this->businessFax = $businessFax;

        return $this;
    }

    /**
     * Get businessFax
     *
     * @return string
     */
    public function getBusinessFax()
    {
        return $this->businessFax;
    }

    /**
     * Set supplierWholesalersAddress
     *
     * @param string $supplierWholesalersAddress
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersAddress($supplierWholesalersAddress)
    {
        $this->supplierWholesalersAddress = $supplierWholesalersAddress;

        return $this;
    }

    /**
     * Get supplierWholesalersAddress
     *
     * @return string
     */
    public function getSupplierWholesalersAddress()
    {
        return $this->supplierWholesalersAddress;
    }

    /**
     * Set supplierWholesalersPhone
     *
     * @param string $supplierWholesalersPhone
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersPhone($supplierWholesalersPhone)
    {
        $this->supplierWholesalersPhone = $supplierWholesalersPhone;

        return $this;
    }

    /**
     * Get supplierWholesalersPhone
     *
     * @return string
     */
    public function getSupplierWholesalersPhone()
    {
        return $this->supplierWholesalersPhone;
    }

    /**
     * Set supplierWholesalersContact
     *
     * @param string $supplierWholesalersContact
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersContact($supplierWholesalersContact)
    {
        $this->supplierWholesalersContact = $supplierWholesalersContact;

        return $this;
    }

    /**
     * Get supplierWholesalersContact
     *
     * @return string
     */
    public function getSupplierWholesalersContact()
    {
        return $this->supplierWholesalersContact;
    }

    /**
     * Set supplierWholesalersAddress2
     *
     * @param string $supplierWholesalersAddress2
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersAddress2($supplierWholesalersAddress2)
    {
        $this->supplierWholesalersAddress2 = $supplierWholesalersAddress2;

        return $this;
    }

    /**
     * Get supplierWholesalersAddress2
     *
     * @return string
     */
    public function getSupplierWholesalersAddress2()
    {
        return $this->supplierWholesalersAddress2;
    }

    /**
     * Set supplierWholesalersPhone2
     *
     * @param string $supplierWholesalersPhone2
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersPhone2($supplierWholesalersPhone2)
    {
        $this->supplierWholesalersPhone2 = $supplierWholesalersPhone2;

        return $this;
    }

    /**
     * Get supplierWholesalersPhone2
     *
     * @return string
     */
    public function getSupplierWholesalersPhone2()
    {
        return $this->supplierWholesalersPhone2;
    }

    /**
     * Set supplierWholesalersContact2
     *
     * @param string $supplierWholesalersContact2
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersContact2($supplierWholesalersContact2)
    {
        $this->supplierWholesalersContact2 = $supplierWholesalersContact2;

        return $this;
    }

    /**
     * Get supplierWholesalersContact2
     *
     * @return string
     */
    public function getSupplierWholesalersContact2()
    {
        return $this->supplierWholesalersContact2;
    }

    /**
     * Set supplierWholesalersAddress3
     *
     * @param string $supplierWholesalersAddress3
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersAddress3($supplierWholesalersAddress3)
    {
        $this->supplierWholesalersAddress3 = $supplierWholesalersAddress3;

        return $this;
    }

    /**
     * Get supplierWholesalersAddress3
     *
     * @return string
     */
    public function getSupplierWholesalersAddress3()
    {
        return $this->supplierWholesalersAddress3;
    }

    /**
     * Set supplierWholesalersPhone3
     *
     * @param string $supplierWholesalersPhone3
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersPhone3($supplierWholesalersPhone3)
    {
        $this->supplierWholesalersPhone3 = $supplierWholesalersPhone3;

        return $this;
    }

    /**
     * Get supplierWholesalersPhone3
     *
     * @return string
     */
    public function getSupplierWholesalersPhone3()
    {
        return $this->supplierWholesalersPhone3;
    }

    /**
     * Set supplierWholesalersContact3
     *
     * @param string $supplierWholesalersContact3
     *
     * @return UserSettings
     */
    public function setSupplierWholesalersContact3($supplierWholesalersContact3)
    {
        $this->supplierWholesalersContact3 = $supplierWholesalersContact3;

        return $this;
    }

    /**
     * Get supplierWholesalersContact3
     *
     * @return string
     */
    public function getSupplierWholesalersContact3()
    {
        return $this->supplierWholesalersContact3;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     *
     * @return UserSettings
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set bankCity
     *
     * @param string $bankCity
     *
     * @return UserSettings
     */
    public function setBankCity($bankCity)
    {
        $this->bankCity = $bankCity;

        return $this;
    }

    /**
     * Get bankCity
     *
     * @return string
     */
    public function getBankCity()
    {
        return $this->bankCity;
    }

    /**
     * Set bankState
     *
     * @param string $bankState
     *
     * @return UserSettings
     */
    public function setBankState($bankState)
    {
        $this->bankState = $bankState;

        return $this;
    }

    /**
     * Get bankState
     *
     * @return string
     */
    public function getBankState()
    {
        return $this->bankState;
    }

    /**
     * Set bankPhone
     *
     * @param string $bankPhone
     *
     * @return UserSettings
     */
    public function setBankPhone($bankPhone)
    {
        $this->bankPhone = $bankPhone;

        return $this;
    }

    /**
     * Get bankPhone
     *
     * @return string
     */
    public function getBankPhone()
    {
        return $this->bankPhone;
    }

    /**
     * Set bankAccount
     *
     * @param string $bankAccount
     *
     * @return UserSettings
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount
     *
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * Set bankName2
     *
     * @param string $bankName2
     *
     * @return UserSettings
     */
    public function setBankName2($bankName2)
    {
        $this->bankName2 = $bankName2;

        return $this;
    }

    /**
     * Get bankName2
     *
     * @return string
     */
    public function getBankName2()
    {
        return $this->bankName2;
    }

    /**
     * Set bankCity2
     *
     * @param string $bankCity2
     *
     * @return UserSettings
     */
    public function setBankCity2($bankCity2)
    {
        $this->bankCity2 = $bankCity2;

        return $this;
    }

    /**
     * Get bankCity2
     *
     * @return string
     */
    public function getBankCity2()
    {
        return $this->bankCity2;
    }

    /**
     * Set bankState2
     *
     * @param string $bankState2
     *
     * @return UserSettings
     */
    public function setBankState2($bankState2)
    {
        $this->bankState2 = $bankState2;

        return $this;
    }

    /**
     * Get bankState2
     *
     * @return string
     */
    public function getBankState2()
    {
        return $this->bankState2;
    }

    /**
     * Set bankPhone2
     *
     * @param string $bankPhone2
     *
     * @return UserSettings
     */
    public function setBankPhone2($bankPhone2)
    {
        $this->bankPhone2 = $bankPhone2;

        return $this;
    }

    /**
     * Get bankPhone2
     *
     * @return string
     */
    public function getBankPhone2()
    {
        return $this->bankPhone2;
    }

    /**
     * Set bankAccount2
     *
     * @param string $bankAccount2
     *
     * @return UserSettings
     */
    public function setBankAccount2($bankAccount2)
    {
        $this->bankAccount2 = $bankAccount2;

        return $this;
    }

    /**
     * Get bankAccount2
     *
     * @return string
     */
    public function getBankAccount2()
    {
        return $this->bankAccount2;
    }

    /**
     * Set businessType
     *
     * @param string $businessType
     *
     * @return UserSettings
     */
    public function setBusinessType($businessType)
    {
        if(is_array($businessType)){
            $businessType = json_encode($businessType);
        }
        $this->businessType = $businessType;

        return $this;
    }

    /**
     * Get businessType
     *
     * @return string
     */
    public function getBusinessType()
    {
        return json_decode($this->businessType, true);
    }

    /**
     * Set businessYear
     *
     * @param string $businessYear
     *
     * @return UserSettings
     */
    public function setBusinessYear($businessYear)
    {
        $this->businessYear = $businessYear;

        return $this;
    }

    /**
     * Get businessYear
     *
     * @return string
     */
    public function getBusinessYear()
    {
        return $this->businessYear;
    }

    /**
     * Set businessMonthlyVolume
     *
     * @param string $businessMonthlyVolume
     *
     * @return UserSettings
     */
    public function setBusinessMonthlyVolume($businessMonthlyVolume)
    {
        $this->businessMonthlyVolume = $businessMonthlyVolume;

        return $this;
    }

    /**
     * Get businessMonthlyVolume
     *
     * @return string
     */
    public function getBusinessMonthlyVolume()
    {
        return $this->businessMonthlyVolume;
    }

    /**
     * Set businessMonthlyGenericSales
     *
     * @param string $businessMonthlyGenericSales
     *
     * @return UserSettings
     */
    public function setBusinessMonthlyGenericSales($businessMonthlyGenericSales)
    {
        $this->businessMonthlyGenericSales = $businessMonthlyGenericSales;

        return $this;
    }

    /**
     * Get businessMonthlyGenericSales
     *
     * @return string
     */
    public function getBusinessMonthlyGenericSales()
    {
        return $this->businessMonthlyGenericSales;
    }

    /**
     * Set businessMonthlyBrandSales
     *
     * @param string $businessMonthlyBrandSales
     *
     * @return UserSettings
     */
    public function setBusinessMonthlyBrandSales($businessMonthlyBrandSales)
    {
        $this->businessMonthlyBrandSales = $businessMonthlyBrandSales;

        return $this;
    }

    /**
     * Get businessMonthlyBrandSales
     *
     * @return string
     */
    public function getBusinessMonthlyBrandSales()
    {
        return $this->businessMonthlyBrandSales;
    }

    /**
     * Set ownershipType
     *
     * @param integer $ownershipType
     *
     * @return UserSettings
     */
    public function setOwnershipType($ownershipType)
    {
        $this->ownershipType = $ownershipType;

        return $this;
    }

    /**
     * Get ownershipType
     *
     * @return integer
     */
    public function getOwnershipType()
    {
        return $this->ownershipType;
    }

    /**
     * Set shippingAddressStreet
     *
     * @param string $shippingAddressStreet
     *
     * @return UserSettings
     */
    public function setShippingAddressStreet($shippingAddressStreet)
    {
        $this->shippingAddressStreet = $shippingAddressStreet;

        return $this;
    }

    /**
     * Get shippingAddressStreet
     *
     * @return string
     */
    public function getShippingAddressStreet()
    {
        return $this->shippingAddressStreet;
    }

    /**
     * Set shippingAddressCity
     *
     * @param string $shippingAddressCity
     *
     * @return UserSettings
     */
    public function setShippingAddressCity($shippingAddressCity)
    {
        $this->shippingAddressCity = $shippingAddressCity;

        return $this;
    }

    /**
     * Get shippingAddressCity
     *
     * @return string
     */
    public function getShippingAddressCity()
    {
        return $this->shippingAddressCity;
    }

    /**
     * Set shippingAddressState
     *
     * @param string $shippingAddressState
     *
     * @return UserSettings
     */
    public function setShippingAddressState($shippingAddressState)
    {
        $this->shippingAddressState = $shippingAddressState;

        return $this;
    }

    /**
     * Get shippingAddressState
     *
     * @return string
     */
    public function getShippingAddressState()
    {
        return $this->shippingAddressState;
    }

    /**
     * Set shippingAddressZip
     *
     * @param string $shippingAddressZip
     *
     * @return UserSettings
     */
    public function setShippingAddressZip($shippingAddressZip)
    {
        $this->shippingAddressZip = $shippingAddressZip;

        return $this;
    }

    /**
     * Get shippingAddressZip
     *
     * @return string
     */
    public function getShippingAddressZip()
    {
        return $this->shippingAddressZip;
    }

    /**
     * Set supplierWholesalers
     *
     * @param string $supplierWholesalers
     *
     * @return UserSettings
     */
    public function setSupplierWholesalers($supplierWholesalers)
    {
        $this->supplierWholesalers = $supplierWholesalers;

        return $this;
    }

    /**
     * Get supplierWholesalers
     *
     * @return string
     */
    public function getSupplierWholesalers()
    {
        return $this->supplierWholesalers;
    }

    /**
     * Set supplierWholesalers2
     *
     * @param string $supplierWholesalers2
     *
     * @return UserSettings
     */
    public function setSupplierWholesalers2($supplierWholesalers2)
    {
        $this->supplierWholesalers2 = $supplierWholesalers2;

        return $this;
    }

    /**
     * Get supplierWholesalers2
     *
     * @return string
     */
    public function getSupplierWholesalers2()
    {
        return $this->supplierWholesalers2;
    }

    /**
     * Set supplierWholesalers3
     *
     * @param string $supplierWholesalers3
     *
     * @return UserSettings
     */
    public function setSupplierWholesalers3($supplierWholesalers3)
    {
        $this->supplierWholesalers3 = $supplierWholesalers3;

        return $this;
    }

    /**
     * Get supplierWholesalers3
     *
     * @return string
     */
    public function getSupplierWholesalers3()
    {
        return $this->supplierWholesalers3;
    }
}

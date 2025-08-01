<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Model\Shipping;

use Contao\Model;
use Contao\Cache;
use Isotope\Interfaces\IsotopeProductCollection;
use Isotope\Isotope;
use Isotope\Model\ProductCollectionItem;
use Isotope\Model\Shipping as Iso_Shipping;
use Ups\Rate as Ups_Rate;
use Ups\Entity\Address as Ups_Address;
use Ups\Entity\Dimensions as Ups_Dimensions;
use Ups\Entity\InsuredValue as Ups_InsuredValue;
use Ups\Entity\Package as Ups_Package;
use Ups\Entity\PackageWeight as Ups_PackageWeight;
use Ups\Entity\PackagingType as Ups_PackagingType;
use Ups\Entity\PackageServiceOptions as Ups_PackageServiceOptions;
use Ups\Entity\Service as Ups_Service;
use Ups\Entity\Shipment as Ups_Shipment;
use Ups\Entity\ShipFrom as Ups_ShipFrom;
use Ups\Entity\ShipTo as Ups_ShipTo;
use Ups\Entity\Shipper as Ups_Shipper;
use Ups\Entity\UnitOfMeasurement as Ups_UnitOfMeasurement;
use stdClass;
use Rhymedigital\IsotopeUpsShipping\Helper\ShippingHelper;

class UPS extends Iso_Shipping
{

    /**
     * Return calculated price for this shipping method
     *
     * @param ?IsotopeProductCollection $objCollection
     * @return float
     */
    public function getPrice(?IsotopeProductCollection $objCollection = null): float
    {
        $objCollection = $objCollection ?? Isotope::getCart();
        $fltPrice = parent::getPrice($objCollection);

        return $this->getLiveRateQuote($objCollection) + $fltPrice;
    }


    /**
     * Return calculated price for this shipping method
     *
     * @param ?IsotopeProductCollection $objCollection
     * @return float
     */
    protected function getLiveRateQuote(?IsotopeProductCollection $objCollection = null): float
    {
        $fltPrice = 0.00;
        $objCollection = $objCollection ?? Isotope::getCart();

        // Get a hash for the cache
        $strHash = static::makeHash($objCollection, array($this->rhyme_ups_enabled_service));

        if (!Cache::has($strHash)) {

            // Build shipment
            $upsShipment = $this->buildShipmentFromCollection($objCollection);

            // UPS Rate Object
            $rate = new Ups_Rate(
                $this->rhyme_ups_access_key,
                $this->rhyme_ups_username,
                $this->rhyme_ups_password,
                $this->rhyme_ups_mode === 'test'
            );

            //try{
                $objResponse = $rate->getRate($upsShipment);
                $fltPrice = (float) $objResponse->RatedShipment[0]->TotalCharges->MonetaryValue;
//            } catch (\Exception $e){
//                //@!TODO post error message
//            }

            Cache::set($strHash, $fltPrice);
        }

        return Cache::get($strHash);
    }

    /**
     * Build a shipment from an IsotopeCollection
     *
     * @param IsotopeProductCollection $objCollection
     * @return ?Ups_Shipment
     */
    protected function buildShipmentFromCollection(IsotopeProductCollection $objCollection): ?Ups_Shipment
    {
        // Get the Iso Config
        $config = Isotope::getConfig();

        // Create the shipment
        $shipment = new Ups_Shipment();

        // Create the service
        $service = new Ups_Service();
        $service->setCode($this->rhyme_ups_enabled_service);
        $service->setDescription($GLOBALS['TL_LANG']['tl_iso_shipping']['rhyme_ups_services'][$this->rhyme_ups_enabled_service]);
        $shipment->setService($service);

        // Build Shipper information
        $shipper = new Ups_Shipper();
        $shipper->setShipperNumber($this->rhyme_ups_account_number);

        // ShipFrom Address
        $shipFromAddress = static::buildAddress($config);

        // Assign to Shipper
        $shipper->setAddress($shipFromAddress);
        $shipment->setShipper($shipper);

        // ShipFrom Object
        $shipFrom = new Ups_ShipFrom();
        $shipFrom->setAddress($shipFromAddress);
        $shipFrom->setCompanyName($config->company);
        $shipment->setShipFrom($shipFrom);

        // ShipTo Address
        $addressModel = $objCollection->getShippingAddress();
        $shipToAddress = static::buildAddress($addressModel);

        // ShipTo Object
	    $shipTo = new Ups_ShipTo();
        $shipTo->setAddress($shipToAddress);
        $shipTo->setAttentionName($addressModel->firstname . ' ' . $addressModel->lastname);
        $shipment->setShipTo($shipTo);
        $shipment->setPackages($this->buildPackages($objCollection));

        return $shipment;
    }

    /**
     * Build a UPS Compatible Address Object from a Model
     *
     * @param Model $addressModel
     * @return Ups_Address
     */
    protected static function buildAddress(Model $addressModel): Ups_Address
    {
        $address = new Ups_Address();
        $arrSubdivision = explode('-', $addressModel->subdivision);
        $address->setAddressLine1($addressModel->street_1);
        $address->setAddressLine2($addressModel->street_2);
        $address->setAddressLine3($addressModel->street_3);
        $address->setCity($addressModel->city);
        $address->setStateProvinceCode(strtoupper($arrSubdivision[1]));
        $address->setPostalCode($addressModel->postal);
        $address->setCountryCode(strtoupper($arrSubdivision[0]));

        return $address;
    }


    /**
     * Build a UPS Compatible Package Object
     *
     * @param IsotopeProductCollection $objCollection
     * @return array
     */
    protected function buildPackages(IsotopeProductCollection $objCollection): array
    {
    	$arrPackages = array();

    	foreach ($objCollection->getItems() as $objItem)
    	{
			$product = $objItem->getProduct();
			$arrDimensions = $product->package_dimensions;
	        $package = new Ups_Package();
			$strWeight = (string)ShippingHelper::getShippingWeight($objItem, 'lb');

            for ($i = 0; $i < $objItem->quantity; $i++) {

		        //Packaging Type
		        $packagingType = new Ups_PackagingType();
		        $packagingType->setCode('02'); //Box for now
		        $packagingType->setDescription('');
		        $package->setPackagingType($packagingType);

		        //Package Dimensions
		        $dimensions = new Ups_Dimensions();
		        $dimensionsUom = new Ups_UnitOfMeasurement();
		        $dimensionsUom->setCode('IN');
		        $dimensions->setUnitOfMeasurement($dimensionsUom);
		        $dimensions->setLength(round($arrDimensions[0]));
		        $dimensions->setWidth(round($arrDimensions[1]));
		        $dimensions->setHeight(round($arrDimensions[2]));
		        $package->setDimensions($dimensions);

		        //Package Weight
		        $packageWeight = new Ups_PackageWeight();
		        $weightUom = new Ups_UnitOfMeasurement();
		        $weightUom->setCode('LBS');
		        $packageWeight->setUnitOfMeasurement($weightUom);
		        $packageWeight->setWeight(!$strWeight ? '1' : $strWeight);
		        $package->setPackageWeight($packageWeight);

		        //Insured Value
				if ($this->rhyme_ups_insure_packages)
				{
			        $insuredValue = new Ups_InsuredValue();
			        $insuredValue->setCurrencyCode('USD'); //For now
			        $insuredValue->setMonetaryValue($objItem->getProduct()->getPrice()->getAmount());
			        $packageServiceOptions = new Ups_PackageServiceOptions();
			        $packageServiceOptions->setInsuredValue($insuredValue);
			        $package->setPackageServiceOptions($packageServiceOptions);
				}

		        $arrPackages[] = $package;
	        }
    	}

        return $arrPackages;
    }


    /**
     * Build a Hash string based on the shipping address
     *
     * @param ?IsotopeProductCollection $objCollection
     * @param null|array                $arrExtras
     * @return string
     */
     protected static function makeHash(?IsotopeProductCollection $objCollection = null, ?array $arrExtras=array()): string
     {
         $objCollection = $objCollection ?? Isotope::getCart();

         $strBase = static::class;
         $strBase .= !empty($arrExtras) ? \implode(',', $arrExtras) : '';

         if (($address = $objCollection->getShippingAddress()) !== null) {
             $strBase .= $address->street_1;
             $strBase .= $address->street_2;
             $strBase .= $address->street_3;
             $strBase .= $address->city;
             $strBase .= $address->subdivision;
             $strBase .= $address->postal;
             $strBase .= $address->country;
         }

         // Hash the cart too
         foreach ($objCollection->getItems() as $item)
         {
	         $strBase .= $item->quantity;
	         $strBase .= $item->id;
	         $strBase .= implode(',', $item->getOptions());
         }

         return \md5($strBase);
     }

}


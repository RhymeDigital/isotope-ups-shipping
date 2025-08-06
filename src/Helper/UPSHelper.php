<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Helper;

use Exception;
use Isotope\Interfaces\IsotopeProductCollection;
use Isotope\Interfaces\IsotopeShipping;
use Isotope\Interfaces\IsotopeOrderableCollection;
use Isotope\Model\Shipping;
use RahulGodiyal\PhpUpsApiWrapper\Entity\BillShipper;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Dimensions;
use RahulGodiyal\PhpUpsApiWrapper\Entity\LabelImageFormat;
use RahulGodiyal\PhpUpsApiWrapper\Entity\LabelSpecification;
use RahulGodiyal\PhpUpsApiWrapper\Entity\LabelStockSize;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Package;
use RahulGodiyal\PhpUpsApiWrapper\Entity\PackageWeight;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Packaging;
use RahulGodiyal\PhpUpsApiWrapper\Entity\PaymentInformation;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ReferenceNumber;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Request;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Service;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipFrom;
use Contao\Controller;
use Contao\StringUtil;
use Haste\Units\Mass\Scale;
use Haste\Units\Mass\Weighable;
use Haste\Units\Mass\WeightAggregate;
use Isotope\Isotope;
use Isotope\Model\Config;
use Isotope\Model\Address as IsoAddress;
use Isotope\Model\ProductCollectionItem;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Address;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Phone;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Shipment;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipmentCharge;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipmentRequest;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Shipper;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShippingRatingOptions;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipQuery;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipTo;
use RahulGodiyal\PhpUpsApiWrapper\Entity\UnitOfMeasurement;
use RahulGodiyal\PhpUpsApiWrapper\Ship;
use Rhymedigital\IsotopeUpsShipping\Entity\UpsApi\Packages;
use Rhymedigital\IsotopeUpsShipping\Event\PreProductCollectionRateEvent;
use Rhymedigital\IsotopeUpsShipping\Event\PostProductCollectionRateEvent;
use Rhymedigital\IsotopeUpsShipping\Event\ValidateProductCollectionRateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UPSHelper
{

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Gets the shipping rate for a given collection using an optionally provided shipping method and options.
     * If no collection or shipping method is provided, the method uses default values.
     *
     * @param IsotopeOrderableCollection|null $collection The orderable collection to calculate the shipping rate for. Defaults to the current cart if null.
     * @param IsotopeShipping|null            $shipping   The specific shipping method to calculate the rate with. Defaults to the collection's shipping method if null.
     * @param array                           $options    Additional options or configuration for determining the rate.
     * @return float|null The calculated shipping rate as a float if successful, or null if the operation fails.
     * @throws Exception
     */
    public function getRateForCollection(?IsotopeOrderableCollection $collection=null, ?IsotopeShipping $shippingModel=null, array $options=[]): ?float
    {
        $fltRate = 0.0;
        $collection = $collection ?? Isotope::getCart();

        // Allow for some customization
        $validateEvent = new ValidateProductCollectionRateEvent($collection, $shippingModel, $options);
        $this->eventDispatcher->dispatch($validateEvent, ValidateProductCollectionRateEvent::NAME);

        // Ensure we have all of the objects we need in order to build the shipment and get a rate
        if (($collection = $validateEvent->getCollection()) === null
            || ($shippingModel = $validateEvent->getShipping()) === null
            || ($shipAddressModel = $collection->getShippingAddress() ?? $collection->getBillingAddress()) === null
            || ($configModel = $collection->getConfig() ?? Isotope::getConfig()) === null
            || ($shipper = $this->getShipperForStoreConfig($configModel)) === null
            || ($shipTo = $this->getShipToForAddress($shipAddressModel)) === null
            || ($shipFrom = $this->getShipFromForStoreConfig($configModel)) === null
        ) {
            return null;
        }

        $shipper->setShippingNumber($shippingModel->rhyme_ups_account_number ?? '');

        // PaymentInformation
        $billShipper = new BillShipper();
        $billShipper->setAccountNumber($shippingModel->rhyme_ups_account_number ?? '');
        $shipmentCharge = new ShipmentCharge();
        $shipmentCharge->setBillShipper($billShipper);
        $paymentInfo = new PaymentInformation();
        $paymentInfo->setShipmentCharge($shipmentCharge);

        // Service
        Controller::loadLanguageFile(Shipping::getTable());
        $service = new Service();
        $service->setCode($shippingModel->rhyme_ups_enabled_service ?? '');
        $service->setDescription($GLOBALS['TL_LANG'][Shipping::getTable()]['rhyme_ups_services'][$service->getCode()] ?? $service->getCode());

        // Package
        if (($packages = $this->buildPackagesForCollection($collection)) === null) {
            return null;
        }

        // Shipment
        $shipment = new Shipment();
        $shipment->setDescription($options['shipment_description'] ?? 'ISOTOPE COLLECTION ID "'.$collection->id.'"');
        $shipment->setShipper($shipper);
        $shipment->setShipTo($shipTo);
        $shipment->setShipFrom($shipFrom);
        $shipment->setPaymentInformation($paymentInfo);
        $shipment->setService($service);
        $shipment->setPackage($packages);

        // ShippingRatingOptions
        $ratingOptions = new ShippingRatingOptions(); // optional
        $ratingOptions->setNegotiatedRatesIndicator(true); // optional
        $shipment->setShippingRatingOptions($ratingOptions); // optional

        // Label
        $labelImageFormat = new LabelImageFormat();
        $labelImageFormat->setCode(LabelImageFormat::GIF); // Todo: allow for this to be set on a per-product basis
        $labelImageFormat->setDescription('GIF'); // optional

        $labelStockSize = new LabelStockSize();
        $labelStockSize->setHeight(LabelStockSize::H_8); // Todo: allow for this to be set on a per-product basis
        $labelStockSize->setWidth(LabelStockSize::W_4); // Todo: allow for this to be set on a per-product basis

        $labelSpec = new LabelSpecification();
        $labelSpec->setLabelImageFormat($labelImageFormat);
        $labelSpec->setLabelStockSize($labelStockSize);
        $labelSpec->setHttpUserAgent("Mozilla/4.5"); // optional

        // Shipment Request
        $shipmentRequest = new ShipmentRequest();
        $shipmentRequest->setRequest(new Request);
        $shipmentRequest->setShipment($shipment);
        $shipmentRequest->setLabelSpecification($labelSpec);

        // Query - Double check the city
        $query = new ShipQuery(); // optional
        $query->setAdditionalAddressValidation('city'); // optional

        // Ship
        $ship = new Ship();
        $ship->setQuery($query); // optional
        $ship->setShipmentRequest($shipmentRequest);
        $ship->setOnlyLabel(false); // optional
        $ship->setMode($shippingModel->rhyme_ups_mode); // Optional | only used for prod

        // Allow for some customization
        $preEvent = new PreProductCollectionRateEvent($collection, $shippingModel, $ship, $options);
        $this->eventDispatcher->dispatch($preEvent, PreProductCollectionRateEvent::NAME);

        // Send
        $response = $ship->createShipment(
            $shippingModel->rhyme_ups_client_id,
            $shippingModel->rhyme_ups_client_secret
        );

        // Allow for some customization
        $postEvent = new PostProductCollectionRateEvent($collection, $shippingModel, $ship, $response, $options);
        $this->eventDispatcher->dispatch($postEvent, PostProductCollectionRateEvent::NAME);

        // Check the response
        if (($response['status'] ?? null) !== 'success'
            || !($response['data'] ?? null)
            || !isset($response['data']->ShipmentResponse->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue)
        ) {
            return null;
        }

        try {
            $fltRate = (float)$response['data']->ShipmentResponse->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue;
        }
        catch (\Exception $e) {}

        return $fltRate;
    }


    /**
     * Creates packages for a given product collection based on its items, optionally using a specified shipping method and other configuration options.
     *
     * @param IsotopeProductCollection $collection The product collection for which packages are to be created.
     * @param Shipping|null            $shipping   The shipping method to use. Defaults to the collection's shipping method if null.
     * @param array                    $options    Additional options for package creation, such as packaging type or description.
     * @return Packages|null A Packages object containing the created packages, or null if no packages were created.
     * @throws Exception
     */
    public function buildPackagesForCollection(IsotopeProductCollection $collection, ?Shipping $shipping=null, array $options=[]): ?Packages
    {
        $packages = new Packages();
        $shipping = $shipping ?? $collection->getShippingMethod();

        foreach ($collection->getItems() as $item) {

            if (!$item->hasProduct()) {
                continue;
            }

            $product = $item->getProduct();
            //$arrDimensions = StringUtil::deserialize($product->package_dimensions, true);
            $package = new Package();
            $strWeight = (string)$this->getShippingWeightForCollectionItem($item, 'lb');

            for ($i = 0; $i < $item->quantity; $i++) {

                // Packaging
                $packaging = new Packaging();
                $packaging->setCode($options['packaging_type'] ?? Packaging::CUSTOMER_SUPPLIED_PACKAGE); // Todo: allow for this to be set on a per-product basis
                $packaging->setDescription($options['packaging_description'] ?? Packaging::CUSTOMER_SUPPLIED_PACKAGE); // Todo: allow for this to be set on a per-product basis
                $package->setPackaging($packaging);

//                // Dimensions can be optional
//                $unitOfMeasurement = new UnitOfMeasurement();
//                $unitOfMeasurement->setCode(UnitOfMeasurement::INCHES);
//                $unitOfMeasurement->setDescription("Inches"); // optional
//
//                $dimensions = new Dimensions();
//                $dimensions->setUnitOfMeasurement($unitOfMeasurement);
//                $dimensions->setLength((string)(float)($arrDimensions[0] ?? 1.0));
//                $dimensions->setWidth((string)(float)($arrDimensions[1] ?? 1.0));
//                $dimensions->setHeight((string)(float)($arrDimensions[2] ?? 1.0));

                // Package weight can be optional
                $weightUom = new UnitOfMeasurement();
                $weightUom->setCode(UnitOfMeasurement::POUNDS);
                $weightUom->setDescription(UnitOfMeasurement::POUNDS); // optional

                $packageWeight = new PackageWeight();
                $packageWeight->setUnitOfMeasurement($weightUom);
                $packageWeight->setWeight($strWeight);

                $packageRef = new ReferenceNumber(); // optional
                $packageRef->setValue('Item ID '.$item->id.' ('.($i+1).' of '.$item->quantity.')'); // optional

                $package = new Package();
                $package->setDescription('Item ID '.$item->id.' ('.($i+1).' of '.$item->quantity.')'); // optional
                $package->setPackaging($packaging);
                //$package->setDimensions($dimensions); // optional
                $package->setPackageWeight($packageWeight);
                $package->setReferenceNumber($packageRef); //optional

                $packages->addPackage($package);
            }
        }

        return $packages->hasPackages() ? $packages : null;
    }



    /**
     * Retrieves a Shipper object based on the provided store configuration and options.
     *
     * @param Config|null $configModel The configuration model to use. If null, the default configuration will be used.
     * @param array       $options     An array of options that may contain:
     *                                 - 'address' (Address|null): A custom address object for the shipper.
     *                                 - 'phone' (Phone|null): A custom phone object for the shipper.
     *                                 - 'phone_ext' (string): The phone extension for the shipper.
     *                                 - 'shipper_attn' (string): The attention line name for the shipper.
     *                                 - 'shipper_fax' (string): The fax number for the shipper.
     * @return Shipper|null Returns the generated Shipper object, or null if configuration is invalid.
     */
    public function getShipperForStoreConfig(?Config $configModel=null, array $options=[]): ?Shipper
    {
        if (($configModel = $configModel ?? Isotope::getConfig()) === null) {
            return null;
        }

        if (($options['address'] ?? null) && $options['address'] instanceof Address) {
            $address = $options['address'];
        }
        else {
            $address = $this->getUPSAddressForObject($configModel);
        }

        if (($options['phone'] ?? null) && $options['phone'] instanceof Phone) {
            $phone = $options['phone'];
        }
        else
        {
            $phone = new Phone();
            $phone->setNumber((string)$configModel->phone);
            $phone->setExtension($options['phone_ext'] ?? '');
        }

        $shipper = new Shipper();
        $shipper->setName((string)$configModel->company);
        $shipper->setAttentionName($options['shipper_attn'] ?? '');
        $shipper->setTaxIdentificationNumber($configModel->taxNumber ?? '');
        $shipper->setPhone($phone);
        $shipper->setEmail((string)$configModel->email);
        $shipper->setShippingNumber("0WD271");
        $shipper->setFaxNumber($options['shipper_fax'] ?? '');
        $shipper->setAddress($address);

        return $shipper;
    }


    /**
     * Creates and returns a ShipFrom object based on the provided configuration and options.
     *
     * @param Config|null $configModel The configuration model used to retrieve company, phone, and address data.
     *                                 If null, the default configuration is used.
     * @param array       $options     An array of options used to configure the ShipFrom object:
     *                                 - 'address' (Address|null): An Address object for the ShipFrom location.
     *                                 - 'phone' (Phone|null): A Phone object for the ShipFrom contact.
     *                                 - 'phone_ext' (string|null): An extension for the phone number.
     *                                 - 'ship_from_attn' (string|null): An attention name for the ShipFrom contact.
     *                                 - 'ship_from_fax' (string|null): A fax number for the ShipFrom contact.
     * @return ShipFrom|null           The constructed ShipFrom object or null if configuration is not available.
     */
    public function getShipFromForStoreConfig(?Config $configModel=null, array $options=[]): ?ShipFrom
    {
        if (($configModel = $configModel ?? Isotope::getConfig()) === null) {
            return null;
        }

        if (($options['address'] ?? null) && $options['address'] instanceof Address) {
            $address = $options['address'];
        }
        else {
            $address = $this->getUPSAddressForObject($configModel);
        }

        if (($options['phone'] ?? null) && $options['phone'] instanceof Phone) {
            $phone = $options['phone'];
        }
        else
        {
            $phone = new Phone();
            $phone->setNumber((string)$configModel->phone);
            $phone->setExtension($options['phone_ext'] ?? '');
        }

        $shipFrom = new ShipFrom();
        $shipFrom->setName((string)$configModel->company);
        $shipFrom->setAttentionName($options['ship_from_attn'] ?? '');
        $shipFrom->setPhone($phone);
        $shipFrom->setFaxNumber($options['ship_from_fax'] ?? '');
        $shipFrom->setAddress($address);

        return $shipFrom;
    }


    /**
     * Creates and returns a ShipTo object based on the provided address model and options.
     *
     * @param IsoAddress $addressModel  The address model containing address details.
     * @param array      $options       Additional options, including pre-built address, phone, phone extension,
     *                                  residential status, and other configuration details.
     *                                  - `address`: An instance of Address if provided, otherwise calculated based on the address model.
     *                                  - `phone`: An instance of Phone if provided, otherwise constructed from the address model's phone data.
     *                                  - `phone_ext`: The phone extension to use if a new Phone instance is created.
     *                                  - `name`: The name to use for the address.
     *                                  - `residential`: Specifies whether the address is residential.
     * @return ShipTo|null The constructed ShipTo object or null if an error occurs.
     */
    public function getShipToForAddress(IsoAddress $addressModel, array $options=[]): ?ShipTo
    {
        if (($options['address'] ?? null) && $options['address'] instanceof Address) {
            $address = $options['address'];
        }
        else {
            $address = $this->getUPSAddressForObject($addressModel);
        }

        if (($options['phone'] ?? null) && $options['phone'] instanceof Phone) {
            $phone = $options['phone'];
        }
        else
        {
            $phone = new Phone();
            $phone->setNumber((string)$addressModel->phone);
            $phone->setExtension($options['phone_ext'] ?? '');
        }

        if (($options['name'] ?? null)) {
            $name = $options['name'];
        }
        else
        {
            // Build name
            $name = [];
            if (!empty($addressModel->salutation)) {
                $name[] = (string)$addressModel->salutation;
            }
            if (!empty($addressModel->firstname)) {
                $name[] = (string)$addressModel->firstname;
            }
            if (!empty($addressModel->lastname)) {
                $name[] = (string)$addressModel->lastname;
            }
            $name = \implode(' ', $name);
        }

        $shipTo = new ShipTo();
        $shipTo->setName($name);
        $shipTo->setAttentionName((string)$addressModel->company);
        $shipTo->setPhone($phone);
        $shipTo->setEmail((string)$addressModel->email);
        $shipTo->setAddress($address);
        $shipTo->setResidential($options['residential'] ?? '');

        return $shipTo;
    }


    /**
     * Constructs a UPS address object from the given item.
     *
     * @param mixed $objItem An object containing address details such as streets, city, subdivision, postal code, and country code.
     * @return Address The constructed Address object with the provided address details.
     */
    public function getUPSAddressForObject($objItem): Address
    {
        if (!\is_object($objItem)) {
            $objItem = (object)$objItem;
        }

        // Build address lines
        $addressLines = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($objItem->{'street_'.$i})) {
                $addressLines[] = $objItem->{'street_'.$i};
            }
        }

        // Build state
        $subdivision = StringUtil::trimsplit('-', (string)$objItem->subdivision);

        $address = new Address();
        $address->setAddressLines($addressLines);
        $address->setCity((string)$objItem->city);
        $address->setStateProvinceCode(\count($subdivision) > 1 ? (string)$subdivision[1] : '');
        $address->setPostalCode((string)$objItem->postal);
        $address->setCountryCode((string)$objItem->country);

        return $address;
    }

    /**
     * Calculate the weight of all products in a collection in a specific weight unit
     * @param ProductCollectionItem $item
     * @param string                $unit
     * @return float
     */
    public function getShippingWeightForCollectionItem(ProductCollectionItem $item, string $unit): float
    {
        if (!$item->hasProduct()) {
            return 0.0;
        }

        $objScale = new Scale();
        $objProduct = $item->getProduct();

        if ($objProduct instanceof WeightAggregate) {
            $objWeight = $objProduct->getWeight();

            if ($objWeight !== null) {
                $objScale->add($objWeight);
            }
        }
        elseif ($objProduct instanceof Weighable) {
            $objScale->add($objProduct);
        }

        return (float)$objScale->amountIn($unit);
    }
}

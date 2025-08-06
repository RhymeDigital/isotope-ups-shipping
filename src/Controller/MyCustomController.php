<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Controller;

use Isotope\Model\Config;
use Isotope\Model\ProductCollection;
use Isotope\Model\Shipping;
use Isotope\Model\Address;
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
use RahulGodiyal\PhpUpsApiWrapper\Entity\Shipment;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipmentCharge;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipmentRequest;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShippingRatingOptions;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipQuery;
use RahulGodiyal\PhpUpsApiWrapper\Entity\UnitOfMeasurement;
use RahulGodiyal\PhpUpsApiWrapper\Ship;
use Rhymedigital\IsotopeUpsShipping\Helper\UPSHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

use RahulGodiyal\PhpUpsApiWrapper\Entity\ValidateAddressQuery;
use RahulGodiyal\PhpUpsApiWrapper\ValidateAddress;

/**
 * @Route("/my_custom", name="rhymedigital_isotope_ups_shipping_my_custom", defaults={"_scope" = "frontend", "_token_check" = true})
 */
class MyCustomController extends AbstractController
{

    private TwigEnvironment $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke()
    {
        return new Response('ok');

//        $collection = ProductCollection::findByPk(1000012);
//        $shippingModel = Shipping::findByPk(1000000);
//        \dd(UPSHelper::getRateForCollection($collection, $shippingModel));
//
//        return new Response('ok');
//
//        // Testing
//        $configModel = Config::findByFallback();
//        $shippingModel = Shipping::findByPk(1000000);
//        $addressModel = Address::findByPk(2);
//
//        if (($shipper = UPSHelper::getShipperForStoreConfig($configModel)) === null) {
//            return new Response('Error');
//        }
//
//        $shipper->setShippingNumber($shippingModel->rhyme_ups_account_number);
//        $shipTo = UPSHelper::getShipToForAddress($addressModel);
//        $shipFrom = UPSHelper::getShipFromForStoreConfig($configModel);
//
//        // PaymentInformation
//        $billShipper = new BillShipper();
//        $billShipper->setAccountNumber($shippingModel->rhyme_ups_account_number);
//        $shipmentCharge = new ShipmentCharge();
//        $shipmentCharge->setBillShipper($billShipper);
//        $paymentInfo = new PaymentInformation();
//        $paymentInfo->setShipmentCharge($shipmentCharge);
//
//        /************ Service **********/
//        $service = new Service();
//        $service->setCode(Service::GROUND);
//        $service->setDescription("Ground"); // optional
//        /************ End Service **********/
//
//        /************ Package **********/
//        $packaging = new Packaging();
//        $packaging->setCode(Packaging::CUSTOMER_SUPPLIED_PACKAGE);
//        $packaging->setDescription("Ups Letter"); // optional
//
//        // Dimensions can be optional
//        $unitOfMeasurement = new UnitOfMeasurement();
//        $unitOfMeasurement->setCode(UnitOfMeasurement::INCHES);
//        $unitOfMeasurement->setDescription("Inches"); // optional
//
//        $dimensions = new Dimensions();
//        $dimensions->setUnitOfMeasurement($unitOfMeasurement);
//        $dimensions->setLength("10");
//        $dimensions->setWidth("10");
//        $dimensions->setHeight("10");
//        // End Dimensions
//
//        // Package Weight can be optional
//        $unitOfMeasurement = new UnitOfMeasurement();
//        $unitOfMeasurement->setCode(UnitOfMeasurement::POUNDS);
//        $unitOfMeasurement->setDescription("POUNDS"); // optional
//
//        $packageWeight = new PackageWeight();
//        $packageWeight->setUnitOfMeasurement($unitOfMeasurement);
//        $packageWeight->setWeight("3");
//        // End Package Weight
//
//        $packageReferenceNumber = new ReferenceNumber(); // optional
//        $packageReferenceNumber->setValue("XXXXXXXXX"); // optional
//
//        $package = new Package();
//        $package->setDescription(""); // optional
//        $package->setPackaging($packaging);
//        $package->setDimensions($dimensions); // optional
//        $package->setPackageWeight($packageWeight); // optional
//        $package->setReferenceNumber($packageReferenceNumber); //optional
//        /************ End Package **********/
//
//        /************ Shipment **********/
//        $shipmentReferenceNumber = new ReferenceNumber(); // optional
//        $shipmentReferenceNumber->setValue("XXXXXXXXX"); // optional
//
//        $shipment = new Shipment();
//        $shipment->setDescription("Ship WS test");
//        $shipment->setShipper($shipper);
//        $shipment->setShipTo($shipTo);
//        $shipment->setShipFrom($shipFrom);
//        $shipment->setPaymentInformation($paymentInfo);
//        $shipment->setService($service);
//        $shipment->setPackage($package);
//        //$shipment->setReferenceNumber($shipmentReferenceNumber); // optional
//
//        /************ ShippingRatingOptions **********/
//        $shippingRatingOptions = new ShippingRatingOptions(); // optional
//        $shippingRatingOptions->setNegotiatedRatesIndicator(true); // optional
//        $shipment->setShippingRatingOptions($shippingRatingOptions); // optional
//        /************ End ShippingRatingOptions **********/
//
//        /************ End Shipment **********/
//
//
//        /************ Label Specification **********/
//        $labelImageFormat = new LabelImageFormat();
//        $labelImageFormat->setCode(LabelImageFormat::GIF);
//        $labelImageFormat->setDescription("GIF"); // optional
//
//        $labelStockSize = new LabelStockSize();
//        $labelStockSize->setHeight(LabelStockSize::H_8);
//        $labelStockSize->setWidth(LabelStockSize::W_4);
//
//        $labelSpecification = new LabelSpecification();
//        $labelSpecification->setLabelImageFormat($labelImageFormat);
//        $labelSpecification->setLabelStockSize($labelStockSize);
//        $labelSpecification->setHttpUserAgent("Mozilla/4.5"); // optional
//        /************ End Label Specification **********/
//
//
//        /************ Shipment Request **********/
//        $shipmentRequest = new ShipmentRequest();
//        $shipmentRequest->setRequest(new Request);
//        $shipmentRequest->setShipment($shipment);
//        $shipmentRequest->setLabelSpecification($labelSpecification);
//        /************ End Shipment Request **********/
//
//
//        /************ Query **********/
//        $query = new ShipQuery(); // optional
//        $query->setAdditionalAddressValidation("city"); // optional
//        /************ End Query **********/
//
//        /************ Create Ship **********/
//        $ship = new Ship();
//        $ship->setQuery($query); // optional
//        $ship->setShipmentRequest($shipmentRequest);
//        $ship->setOnlyLabel(false); // optional
//        $ship->setMode($shippingModel->rhyme_ups_mode); // Optional | only used for prod
//        $shipRes = $ship->createShipment(
//            $shippingModel->rhyme_ups_client_id,
//            $shippingModel->rhyme_ups_client_secret
//        );
//        /************ End Create Ship **********/
//
//        if (($shipRes['status'] ?? null) === 'success' && ($shipRes['data'] ?? null)) {
//            return new Response($shipRes['data']->ShipmentResponse->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue);
//        }
//        else {
//            $fnDump = function($x){
//                ob_start();
//                var_dump($x);
//                $result = ob_get_clean();
//                return $result;
//            };
//            return new Response($fnDump($shipRes));
//        }
//
//        return new Response('Error');


        //        /********* Query *********/
//        $query = new ValidateAddressQuery(); // optional
//        $query->setRegionalRequestIndicator("False"); // optional
//        $query->setMaximumCandidateListSize("5"); //optional
//        /********* End Query *********/
//
//        /******** Set Address ********/
//        $validateAddress = new ValidateAddress();
//        $validateAddress->setQuery($query); // optional
//        $validateAddress->setAddressLines([
//            "161 Northampton St", // address line 1
//            //"SPACE K113iokio" // address line 2
//        ]);
//        $validateAddress->setPoliticalDivision2("Westfield"); // City
//        $validateAddress->setPoliticalDivision1("MA"); // State Code
//        $validateAddress->setPostcodePrimaryLow("01085"); // Postal Code
//        $validateAddress->setCountryCode("US");
//        /******** End Set Address ********/
//
//        $validateAddress->setMode('PROD'); // optional
//
//        \dd($validateAddress->validate('RvNAx4y13G79N51OhRpdpMghJ3QAfQqj5dTF6KKT3EqfwBdO', 'AtQRrFbF8EMTGUByuNNb5tCIO8y3Q18ELgqZBlp1vdFkhq9H5inXwAq0CxM2301x'));

    }
}

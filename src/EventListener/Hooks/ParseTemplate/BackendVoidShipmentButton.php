<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 * @link            https://rhyme.digital
 * @license         https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\EventListener\Hooks\ParseTemplate;

use Contao\Controller;
use Contao\CoreBundle\Exception\AjaxRedirectResponseException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Environment;
use Contao\Input;
use Contao\Message;
use Contao\System;
use Contao\Template;
use GuzzleHttp\Exception\ClientException;
use Isotope\Model\Shipping;
use Rhyme\Model\Isotope\Shipment;
use Rhymedigital\IsotopeUpsShipping\Helper\UPSHelper;
use Rhymedigital\IsotopeUpsShipping\Interfaces\UPSShipping;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Hook("parseTemplate")
 */
class BackendVoidShipmentButton
{
    private ContainerInterface $container;
    private ScopeMatcher $scopeMatcher;

    public function __construct(ContainerInterface $container, ScopeMatcher $scopeMatcher)
    {
        $this->container = $container;
        $this->scopeMatcher = $scopeMatcher;
    }

    /**
     * Handles the invocation of the method for processing backend template
     * modifications related to UPS shipping.
     * This method checks various conditions to determine whether the UPS Void
     * Shipment button should be added to the backend template. It validates the
     * current request, template name, shipping module, and related shipment data.
     * If all conditions are met, a global flag is set to enable the button.
     *
     * @param Template $template The template being processed.
     * @return void
     */
    public function __invoke(Template $template): void
    {
        if (!($request = $this->container->get('request_stack')->getCurrentRequest())
            || !$this->scopeMatcher->isBackendRequest($request)
            || \strpos($template->getName(), 'be_iso_shipment_') !== 0
            || empty($shippingRow = !empty($template->shipping_module) && \is_array($template->shipping_module) ? $template->shipping_module : [])
            || ($shippingMethod = Shipping::findByPk($shippingRow['id'])) === null
            || !($shippingMethod instanceof UPSShipping)
            || empty($template->data['id'])
            || ($shipmentModel = Shipment::findByPk($template->data['id'])) === null
            || !$shipmentModel->rhyme_ups_shipment_identification_number
            || $shipmentModel->rhyme_ups_shipment_voided_at
        ) {
            return;
        }

        // This will be checked in the "parseBackendTemplate" hook
        $GLOBALS['RHYME_UPS_ADD_SHIPMENT_VOID'] = (int)$shipmentModel->id;

        // See if we need to void the current shipment
        if (($shipmentId = (int)Input::get('voidShipment'))
            && $shipmentId === $GLOBALS['RHYME_UPS_ADD_SHIPMENT_VOID']
            && ($helper = System::getContainer()->get('rhyme.isotope.ups_shipping.ups_helper')) !== null
        ) {
            $redirectBack = true;

            try
            {
                // Send request to void the shipment
                $response = $helper->voidShipment(
                    $shipmentModel->rhyme_ups_shipment_identification_number,
                    $shippingMethod->rhyme_ups_client_id,
                    $shippingMethod->rhyme_ups_client_secret,
                    $shippingMethod->rhyme_ups_mode === 'DEV'
                );

                // Check the response
                if (($response['status'] ?? null) !== 'success') {
                    throw new \Exception($response['msg'] ?? '');
                }

                // Don't allow the user to try again if it was successful
                $shipmentModel->rhyme_ups_shipment_voided_at = \time();
                $shipmentModel->save();

                unset($GLOBALS['RHYME_UPS_ADD_SHIPMENT_VOID']);

                Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['shipmentVoidedSuccess']);
            }
            catch (RedirectResponseException | AjaxRedirectResponseException $e) {
                $redirectBack = false;
            }
            catch (\Exception $e) {
                Message::addError(\sprintf($GLOBALS['TL_LANG']['ERR']['upsBeShipmentVoid'] ?? 'There was a problem voiding the UPS shipment.%s', ' "'.$e->getMessage().'"'));
            }

            if ($redirectBack) {
                Controller::redirect(\str_replace('&voidShipment='.$shipmentId, '', Environment::get('request')));
            }
        }
    }
}

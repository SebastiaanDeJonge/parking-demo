<?php
namespace SebastiaanDeJonge\Pm\Controller;

use SebastiaanDeJonge\Pm\DataType\Garage;

/**
 * Garage controller
 *
 * @package SebastiaanDeJonge\Pm\Controller
 */
class GarageController extends AbstractController
{
    /**
     * @return string
     */
    public function get()
    {
        $localRoutePath = $this->getLocalRoutePath();
        $pathParts = explode('/', $localRoutePath);
        $action = !empty($pathParts[1]) ? $pathParts[1] : 'all';
        /** @var Garage $dataType */
        $dataType = Garage::getInstance();

        // Switch between base actions
        switch ($action)
        {
            // Search by ID
            case 'id':
                $data = $dataType->findById(!empty($pathParts[2]) ? (int)$pathParts[2] : '');
                break;
            // Search by name
            case 'name':
                $data = $dataType->findByName(!empty($pathParts[2]) ? $pathParts[2] : '');
                break;
            // Search by country name
            case 'country':
                $data = $dataType->findByCountry(!empty($pathParts[2]) ? $pathParts[2] : '');
                break;
            // Search in radius
            case 'nearby':
                $data = [];

                // Latitude & longitude
                $latitudeIndex = array_search('latitude', $pathParts);
                $longitudeIndex = array_search('longitude', $pathParts);

                // Fetch the results
                if (!empty($pathParts[(int)$latitudeIndex + 1] && !empty($pathParts[(int)$longitudeIndex + 1]))) {
                    $data = $dataType->findNearby(
                        floatval($pathParts[$latitudeIndex + 1]),
                        floatval($pathParts[$longitudeIndex + 1])
                    );
                }
                break;
            // Default, show all garages
            default:
                $data = $dataType->findAll();
                break;
        }

        // Output
        header('Content-type: application/json');
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
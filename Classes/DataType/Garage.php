<?php
namespace SebastiaanDeJonge\Pm\DataType;

use SebastiaanDeJonge\Pm\Database\Database;
use SebastiaanDeJonge\Pm\Database\Query;


/**
 * Garage data type
 *
 * @package SebastiaanDeJonge\Pm\DataType
 */
class Garage extends AbstractDataType
{
    /**
     * @var string
     */
    protected $tableName = 'garage';



    /**
     * @var array
     */
    protected $defaultProperties = [
        'garage.id AS garage_id',
        'garage.name',
        'garage.hourly_rate AS hourly_price',
        'garage.contact_email',
        'CONCAT(garage.latitude, \' \', garage.longitude) AS point',
        'garage.owner_id',
    ];

    /**
     * @var array
     */
    protected $childMappingConfiguration = [
        'owner_id' => Owner::class,
        'currency_id' => Currency::class,
        'country_id' => Country::class
    ];

    /**
     * Find garages with the given name (exact search)
     *
     * @param string $name
     * @return array
     */
    public function findByName(string $name): array
    {
        return $this->findByProperty('name', $name);
    }

    /**
     * @param int $id
     * @return array
     */
    public function findById(int $id): array
    {
        return $this->findByProperty('id', $id);
    }

    /**
     * Finds all garages by country (with country string)
     *
     * @param string $country
     * @return array
     */
    public function findByCountry(string $country)
    {
        $result = [];

        /** @var Country $countryDataType */
        $countryDataType = Country::getInstance();
        $countries = $countryDataType->findByName($country);
        if (!empty($countries)) {
            $country = array_shift($countries);
            if (!empty($country['id'])) {
                $result = $this->findByProperty('country_id', $country['id']);
            }
        }

        return $result;
    }

    /**
     * Finds nearby garages, the given distance is in KM
     *
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function findNearby(float $latitude, float $longitude): array
    {
        /** @var Database $database */
        $database = Database::getInstance();
        $query = $this->createQuery()
            ->addSelectField(
                '(
			        6371 * ACOS(
			            COS(
			                RADIANS( :latitude )
			            ) * COS(
			                RADIANS( garage.latitude )
			            ) * COS(
			                RADIANS( garage.longitude ) - RADIANS( :longitude )
			            ) + SIN(
			                RADIANS( :latitude )
			            ) * SIN(
			                RADIANS( garage.latitude )
			            )
			        )
			    ) AS distance'
            )
            ->addOrdering('distance', Query::ORDER_ASCENDING)
            ->setLimit($this->limit);
        $result = $database->executeSelectQuery(
            $query->build(),
            [
                'latitude' => $latitude,
                'longitude' => $longitude
            ]
        );
        return $result;
    }

    /**
     * @param int $page
     * @return array
     */
    public function findAll(int $page = 1): array
    {
        /** @var Database $database */
        $database = Database::getInstance();
        $query = $this->createQuery()
            ->setOffset(($page - 1) * $this->limit)
            ->setLimit($this->limit);
        $result = $database->executeSelectQuery($query->build());
        return $result;
    }
}
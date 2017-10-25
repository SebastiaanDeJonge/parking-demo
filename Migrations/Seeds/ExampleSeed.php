<?php
use Phinx\Seed\AbstractSeed;

class ExampleSeed extends AbstractSeed
{
    /**
     * Runs the example seed, this will clear any existing data
     *
     * @return void
     */
    public function run()
    {
        // Add the owners
        $ownerTable = $this->table('owner');
        $ownerTable->truncate();
        $owners = [
            ['id' => 1, 'name' => 'AutoPark'],
            ['id' => 2, 'name' => 'Q-Park']
        ];
        $this->insert('owner', $owners);

        // Add the currencies
        $currencyTable = $this->table('currency');
        $currencyTable->truncate();
        $currencies = [
            ['id' => 1, 'name' => 'Euro', 'symbol' => '€']
        ];
        $this->insert('currency', $currencies);

        // Add the countries
        $countryTable = $this->table('country');
        $countryTable->truncate();
        $countries = [
            ['id' => 1, 'name' => 'Finland']
        ];
        $this->insert('country', $countries);

        // Add the garages
        $garageTable = $this->table('garage');
        $garageTable->truncate();
        $garages = [
            [
                'name' => 'Tampere Rautatientori',
                'latitude' => 60.168607847624095,
                'longitude' => 24.932371066131623,
                'hourly_rate' => 2,
                'contact_email' => 'testemail@testautopark.fi',
                'owner_id' => 1,
                'country_id' => 1,
                'currency_id' => 1
            ],
            [
                'name' => 'Punavuori Garage',
                'latitude' => 60.162562,
                'longitude' => 24.939453,
                'hourly_rate' => 1.5,
                'contact_email' => 'testemail@testautopark.fi',
                'owner_id' => 1,
                'country_id' => 1,
                'currency_id' => 1
            ],
            [
                'name' => 'Unknown',
                'latitude' => 60.16444996645511,
                'longitude' => 24.938178168200714,
                'hourly_rate' => 3,
                'contact_email' => 'testemail@testautopark.fi',
                'owner_id' => 1,
                'country_id' => 1,
                'currency_id' => 1
            ],
            [
                'name' => 'Fitnesstukku',
                'latitude' => 60.165219358852795,
                'longitude' => 24.93537425994873,
                'hourly_rate' => 3,
                'contact_email' => 'testemail@testautopark.fi',
                'owner_id' => 1,
                'country_id' => 1,
                'currency_id' => 1
            ],
            [
                'name' => 'Kauppis',
                'latitude' => 60.17167429490068,
                'longitude' => 24.921585662024363,
                'hourly_rate' => 3,
                'contact_email' => 'testemail@testautopark.fi',
                'owner_id' => 1,
                'country_id' => 1,
                'currency_id' => 1
            ],
            [
                'name' => 'Q-Park1',
                'latitude' => 60.16867390148751,
                'longitude' => 24.930162952045407,
                'hourly_rate' => 2,
                'contact_email' => 'testemail@testautopark.fi',
                'owner_id' => 2,
                'country_id' => 1,
                'currency_id' => 1
            ]
        ];
        $this->insert('garage', $garages);
    }
}

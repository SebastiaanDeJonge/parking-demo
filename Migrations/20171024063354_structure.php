<?php
use Phinx\Migration\AbstractMigration;

/**
 * Class Structure
 */
class Structure extends AbstractMigration
{
    /**
     * Up (runs migration)
     *
     * - Coordinates are stored as decimal, I figure 16cm accuracy is close enough for whatever these are used.
     * - I can imagine that rates can be more flexible (for example during peak hours, or in weekends), in that case the
     *   setup for rates would be a different of course. These were however not mentioned inside the example.
     *
     * @return void
     */
    public function up()
    {
        $ownerTable = $this->table('owner', ['id' => false, 'primary_key' => 'id']);
        $ownerTable->addColumn('id', 'integer', ['signed' => false, 'identity' => true]);
        $ownerTable->addColumn('name', 'string', ['null' => false]);
        $ownerTable->save();

        $garageTable = $this->table('garage', ['id' => false, 'primary_key' => 'id']);
        $garageTable->addColumn('id', 'integer', ['signed' => false, 'identity' => true]);
        $garageTable->addColumn('name', 'string', ['null' => false]);
        $garageTable->addColumn('latitude', 'decimal', ['precision' => 8, 'scale' => 6]);
        $garageTable->addColumn('longitude', 'decimal', ['precision' => 9, 'scale' => 6]);
        $garageTable->addColumn('hourly_rate', 'decimal', ['precision' => 15, 'scale' => 2]);
        $garageTable->addColumn('contact_email', 'string', ['null' => false]);
        $garageTable->addColumn('owner_id', 'integer', ['signed' => false]);
        $garageTable->addColumn('country_id', 'integer', ['signed' => false]);
        $garageTable->addColumn('currency_id', 'integer', ['signed' => false]);
        $garageTable->addIndex(['latitude', 'longitude']);
        $garageTable->addIndex(['country_id']);
        $garageTable->save();

        $currencyTable = $this->table('currency', ['id' => false, 'primary_key' => 'id']);
        $currencyTable->addColumn('id', 'integer', ['signed' => false, 'identity' => true]);
        $currencyTable->addColumn('name', 'string', ['null' => false]);
        $currencyTable->addColumn('symbol', 'string', ['null' => false, 'length' => 8]);
        $currencyTable->save();

        $countryTable = $this->table('country', ['id' => false, 'primary_key' => 'id']);
        $countryTable->addColumn('id', 'integer', ['signed' => false, 'identity' => true]);
        $countryTable->addColumn('name', 'string', ['null' => false]);
        $countryTable->save();
    }
}

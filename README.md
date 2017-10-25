# PM
This small demo application displays a simple parking garage API.

## Requirements
* PHP 7.0.x
* MySQL 5.7.x
* Apache2 or Nginx
* Composer

## Installation
After cloning or extracting the project, you can run 
```
composer install
``` 
from the project root to install the 
dependencies.

Now you can finalize the setup by running Phinx migrations to setup the database. Run the following command inside the 
command line interface: 
```
./Binaries/phinx migrate
```

You probably also want to go along and populate the database with the dummy data. You can do so with the following 
command inside the command line interface: 
```
./Binaries/phinx seed:run ExampleSeed
```

The executable which should publicly available is the `index.php` file inside the `Web` folder in the project root.

## Configuration
Other configuration options can be found inside the `Configuration` folder. You may simply copy the 
`Development.dist.yaml` file to `Development.yaml` and add your data there (database credentials should be sufficient).

If you are running the application from a sub-directory, be sure to set the correct path.

You can change environment variable `PM_ENVIRONMENT`. The following values are allowed:
* `Production`
* `Staging`
* `Testing`
* `Development`

In case none is set, `Development` is used as a default.

## Example URLs
The following example URLs are based on the ExampleSeed.

__Find all garages:__
```
http://localhost/garages
```

__Find closest garages to given coordinates, results are ordered by the distance they are from the input (in KM):__
```
http://localhost/garages/nearby/latitude/60.165219/longitude/24.938178
```

__Find a garage by name:__
```
http://localhost/garages/name/Unknown
```

__Find a garage by identifier:__
```
http://localhost/garages/id/1
```

__Find a garage by country name:__
```
http://localhost/garages/country/Finland
```

## Logging
Any errors, warnings, notices etc. are logged into a single log file:
```
./Logs/Application.log
```
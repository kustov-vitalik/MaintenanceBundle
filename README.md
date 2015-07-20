# MaintenanceBundle

Bundle to move separate actions/controller in maintenance mode (return 503 Service unavailable with Retry-After header for example in prodaction env) 

## Installation

Add this in your `composer.json`

    "require-dev": {
        [...]
        "kustov-vitalik/maintenance-bundle" : "1.0.*",
    },

Then run `php composer.phar update -o`

Next step is to register the bundle in AppKernel (`app/AppKernel.php`)

    ...
    new KustovVitalik\MaintenanceBundle\KustovVitalikMaintenanceBundle(),
    ...

After this mark some action or controller with @Maintenance annotation.

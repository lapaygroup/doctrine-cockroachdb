<a href="https://lapay.group/"><img align="left" width="200" src="https://lapay.group/lglogo.jpg"></a>
<a href="https://www.cockroachlabs.com/"><img align="right" width="200" src="https://d33wubrfki0l68.cloudfront.net/1c17b3053b29646cdddc53965186a02179b59842/3ead0/img/cockroachlabs-logo-170.png"></a>

<br /><br /><br />
[![Latest Stable Version](https://poser.pugx.org/lapaygroup/doctrine-cockroachdb/v/stable)](https://packagist.org/packages/lapaygroup/doctrine-cockroachdb)
[![Total Downloads](https://poser.pugx.org/lapaygroup/doctrine-cockroachdb/downloads)](https://packagist.org/packages/lapaygroup/doctrine-cockroachdb)
[![License](https://poser.pugx.org/lapaygroup/doctrine-cockroachdb/license)](https://packagist.org/packages/lapaygroup/doctrine-cockroachdb)

# CockroachDB Driver

Driver for supports CockroachDB in Doctrine DBAL. This library fixes errors related doctrine:migrations when using PostgreSQL driver.

Symfony configuration example:
```yaml
# doctrine.yaml
doctrine:
    dbal:
        user: root
        port: 26257
        host: localhost
        dbname: database_name
        platform_service: LapayGroup\DoctrineCockroach\Platforms\CockroachPlatform
        driver_class: LapayGroup\DoctrineCockroach\Driver\CockroachDriver
```

Connection url style:
```yaml
# doctrine.yaml
doctrine:
    dbal:
        url: //root:@localhost:26257/database_name
        platform_service: LapayGroup\DoctrineCockroach\Platforms\CockroachPlatform
        driver_class: LapayGroup\DoctrineCockroach\Driver\CockroachDriver
```

```yaml
# services.yaml
services:
  LapayGroup\DoctrineCockroach\Platforms\CockroachPlatform:
    autowire: true

  LapayGroup\DoctrineCockroach\Driver\CockroachDriver:
    autowire: true

  LapayGroup\DoctrineCockroach\Schema\CockroachSchemaManager:
    autowire: true
```

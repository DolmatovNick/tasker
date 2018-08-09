<?php

$entityManager = require "entity.manager.php";
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);

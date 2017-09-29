<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;

$metadata->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
$metadata->customRepositoryClassName = 'AppBundle\Repository\ShortenUrlRepository';
$metadata->setChangeTrackingPolicy(ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT);
$metadata->mapField(array(
   'fieldName' => 'id',
   'type' => 'integer',
   'id' => true,
   'columnName' => 'id',
  ));
$metadata->mapField(array(
   'fieldName' => 'original_url',
   'type' => 'string',
   'length' => 255,
   'columnName' => 'original_url',
  ));
$metadata->mapField(array(
   'fieldName' => 'short_url',
   'type' => 'string',
   'length' => 255,
   'columnName' => 'short_url',
  ));
$metadata->mapField(array(
   'fieldName' => 'use_count',
   'type' => 'integer',
   'columnName' => 'use_count',
  ));
$metadata->mapField(array(
   'fieldName' => 'create_date',
   'type' => 'date',
   'columnName' => 'create_date',
  ));
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);
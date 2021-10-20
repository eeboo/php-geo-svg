<?php

namespace PrinsFrank\PhpGeoSVG\Geometry;

use JsonException;
use PrinsFrank\PhpGeoSVG\Exception\NotImplementedException;
use PrinsFrank\PhpGeoSVG\Geometry\GeometryObject\GeometryObjectFactory;

class GeometryCollectionFactory
{
    /**
     * @throws NotImplementedException
     */
    public static function createFromGeoJSONArray(array $geoJSONArray): GeometryCollection
    {
        $geometryCollection = new GeometryCollection();
        if ($geoJSONArray['type'] !== 'FeatureCollection') {
            throw new NotImplementedException('Only FeatureCollections are currently supported');
        }

        foreach ($geoJSONArray['features'] ?? [] as $feature) {
            if ($feature['type'] !== 'Feature') {
                throw new NotImplementedException('Only features of type "Feature" are supported.');
            }

            $geometryCollection->addGeometryObject(GeometryObjectFactory::createForGeoJsonFeatureGeometry($feature['geometry']));
        }

        return $geometryCollection;
    }

    /**
     * @throws JsonException|NotImplementedException
     */
    public static function createFromGeoJsonString(string $geoJsonString): GeometryCollection
    {
        return self::createFromGeoJSONArray(json_decode($geoJsonString, true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * @throws JsonException|NotImplementedException
     */
    public static function createFromGeoJSONFilePath(string $path): GeometryCollection
    {
        return self::createFromGeoJsonString(file_get_contents($path));
    }
}
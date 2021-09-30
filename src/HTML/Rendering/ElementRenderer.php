<?php

namespace PrinsFrank\PhpGeoSVG\HTML\Rendering;

use PrinsFrank\PhpGeoSVG\Exception\RecursionException;
use PrinsFrank\PhpGeoSVG\HTML\Elements\Element;

class ElementRenderer
{
    private const RECURSION_LIMIT = 100;

    /**
     * @throws RecursionException
     */
    public static function renderElement(Element $element, int $currentDepth = 0): string
    {
        if ($currentDepth++ > self::RECURSION_LIMIT) {
            throw new RecursionException('Recursion limit of ' . self::RECURSION_LIMIT . ' Reached');
        }

        $elementContent = null;
        foreach ($element->getChildElements() as $childElement) {
            $elementContent .= self::renderElement($childElement, $currentDepth);
        }

        $attributeString = AttributeRenderer::renderAttributes($element->getAttributes());
        return '<' . $element->getTagName() . ($attributeString !== null ? ' ' . $attributeString : null) . '>' .
            $elementContent .
        '</' . $element->getTagName() . '>';
    }
}

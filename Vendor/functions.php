<?php

use Helpers\StringValue;

/**
 * Dump and die
 * @param mixed ...$mixins
 */
function dd (... $mixins) {

    foreach ($mixins as $mixin) {
        print "<pre>";
        var_dump($mixin);
        print "</pre>";
    }
    die;
}

function replaceParentClassName ($className, $parentClassName) {

}

function classNameToNameTableEntity (string $className) {
    $parents = class_parents($className);
    $getClassNameWithoundNamespace = function ($className) {
        $explode = explode('\\', $className);
        return array_pop($explode);
    };
    $className = $getClassNameWithoundNamespace($className);
    $lastParent = array_pop($parents);
    $lastParent = $getClassNameWithoundNamespace($lastParent);
    $lastParent = (new StringValue($lastParent))->toSnakeCase();
    $stringClassName =
        (new StringValue($className))
            ->toLow()
            ->replace($lastParent->getResult() ,'')
            ->toSnakeCase()
    ;

    $chars = $stringClassName->getChars();
    $lastChar = array_pop($chars);
    $result = $stringClassName->getResult();
    if ($lastChar->toLow()->getResult() !== "s") {
        $result .= "s";
    }
    return $result;
}

function classNameToNameTableModel (string $className) {
    $getClassNameWithountNamespace = function () use ($className) {
        $explode = explode('\\', $className);
        $result = array_pop($explode);
        return $result;
    };

    $modelName = $getClassNameWithountNamespace();
    $modelStringValue = (new StringValue($modelName))->toSnakeCase();
    $lastChar = array_pop($modelStringValue->getChars());
    $result = $modelStringValue->getResult();
    if ($lastChar->toLow()->getResult() !== "s") {
        $result .= "s";
    }
    return $result;
}
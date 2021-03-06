<?php namespace App\Json\Validators\User;

use App\Json\Schemes\UserScheme as Scheme;
use App\Json\Validators\User\UserRules as r;
use Limoncello\Flute\Contracts\Validation\JsonApiRuleSetInterface;
use Limoncello\Validation\Contracts\Rules\RuleInterface;

/**
 * @package App
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class UserUpdate implements JsonApiRuleSetInterface
{
    /**
     * @inheritdoc
     */
    public static function getTypeRule(): RuleInterface
    {
        return r::userType();
    }

    /**
     * @inheritdoc
     */
    public static function getIdRule(): RuleInterface
    {
        return r::userId();
    }

    /**
     * @inheritdoc
     */
    public static function getAttributeRules(): array
    {
        return [
            Scheme::ATTR_FIRST_NAME => r::firstName(),
            Scheme::ATTR_LAST_NAME  => r::lastName(),
            Scheme::ATTR_EMAIL      => r::email(),
            Scheme::V_ATTR_PASSWORD => r::password(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getToOneRelationshipRules(): array
    {
        return [
            Scheme::REL_ROLE => r::roleRelationship(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getToManyRelationshipRules(): array
    {
        return [];
    }
}

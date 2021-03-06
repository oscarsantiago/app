<?php namespace App\Authorization;

use App\Json\Schemes\RoleScheme as Scheme;
use Limoncello\Application\Contracts\Authorization\ResourceAuthorizationRulesInterface;
use Limoncello\Auth\Contracts\Authorization\PolicyInformation\ContextInterface;
use Settings\Passport;

/**
 * @package App
 */
class RoleRules implements ResourceAuthorizationRulesInterface
{
    use RulesTrait;

    /** Action name */
    const ACTION_VIEW_ROLES = 'canViewRoles';

    /** Action name */
    const ACTION_ADMIN_ROLES = 'canAdminRoles';

    /**
     * @inheritdoc
     */
    public static function getResourcesType(): string
    {
        return Scheme::TYPE;
    }

    /**
     * @param ContextInterface $context
     *
     * @return bool
     */
    public static function canViewRoles(ContextInterface $context): bool
    {
        return self::hasScope($context, Passport::SCOPE_VIEW_ROLES);
    }

    /**
     * @param ContextInterface $context
     *
     * @return bool
     */
    public static function canAdminRoles(ContextInterface $context): bool
    {
        return self::hasScope($context, Passport::SCOPE_ADMIN_ROLES);
    }
}

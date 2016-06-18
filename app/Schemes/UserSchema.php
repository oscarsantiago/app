<?php namespace App\Schemes;

use App\Database\Models\User as Model;

/**
 * @package App
 */
class UserSchema extends BaseSchema
{
    /** Type */
    const TYPE = 'users';

    /** Model class name */
    const MODEL = Model::class;

    /** Attribute name */
    const ATTR_TITLE = 'title';

    /** Attribute name */
    const ATTR_FIRST_NAME = 'first-name';

    /** Attribute name */
    const ATTR_LAST_NAME = 'last-name';

    /** Attribute name */
    const ATTR_EMAIL = 'email';

    /** Attribute name */
    const ATTR_LANGUAGE = 'language';

    /** Relationship name */
    const REL_ROLE = 'role';

    /** Relationship name */
    const REL_POSTS = 'posts';

    /** Relationship name */
    const REL_COMMENTS = 'comments';

    /**
     * @inheritdoc
     */
    public static function getMappings()
    {
        return [
            self::SCHEMA_ATTRIBUTES => [
                self::ATTR_TITLE      => Model::FIELD_TITLE,
                self::ATTR_FIRST_NAME => Model::FIELD_FIRST_NAME,
                self::ATTR_LAST_NAME  => Model::FIELD_LAST_NAME,
                self::ATTR_EMAIL      => Model::FIELD_EMAIL,
                self::ATTR_LANGUAGE   => Model::FIELD_LANGUAGE,
                self::ATTR_CREATED_AT => Model::FIELD_CREATED_AT,
                self::ATTR_UPDATED_AT => Model::FIELD_UPDATED_AT,
            ],
            self::SCHEMA_RELATIONSHIPS => [
                self::REL_ROLE     => Model::REL_ROLE,
                self::REL_POSTS    => Model::REL_POSTS,
                self::REL_COMMENTS => Model::REL_COMMENTS,
            ],
        ];
    }
}

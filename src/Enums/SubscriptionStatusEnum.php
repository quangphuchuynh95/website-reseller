<?php

namespace QuangPhuc\WebsiteReseller\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static SubscriptionStatusEnum PENDING()
 * @method static SubscriptionStatusEnum ACTIVE()
 * @method static SubscriptionStatusEnum EXPIRED()
 * @method static SubscriptionStatusEnum CANCELLED()
 * @method static SubscriptionStatusEnum SUSPENDED()
 */
class SubscriptionStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const ACTIVE = 'active';

    public const EXPIRED = 'expired';

    public const CANCELLED = 'cancelled';

    public const SUSPENDED = 'suspended';

    public static $langPath = 'plugins/website-reseller::subscription.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::ACTIVE => 'success',
            self::EXPIRED => 'secondary',
            self::CANCELLED => 'danger',
            self::SUSPENDED => 'info',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color, icon: $this->getIcon());
    }

    public function getIcon(): string
    {
        return match ($this->value) {
            self::PENDING => 'ti ti-clock',
            self::ACTIVE => 'ti ti-circle-check',
            self::EXPIRED => 'ti ti-clock-off',
            self::CANCELLED => 'ti ti-circle-x',
            self::SUSPENDED => 'ti ti-player-pause',
            default => 'ti ti-circle',
        };
    }
}

<?php

declare(strict_types=1);

namespace common\rbac;

use Yii;
use yii\rbac\Rule;

class OwnEventRule extends Rule
{
    public $name = 'ownEvent';

    public function execute($user, $item, $params): bool
    {
        if (Yii::$app->authManager->getAssignment('admin', (string) $user) !== null) {
            return true;
        }

        $eventId = $params['eventId'] ?? null;
        if (!is_scalar($eventId) || !is_numeric($eventId)) {
            return false;
        }

        return (new \yii\db\Query())
            ->from('{{%eventos}}')
            ->where([
                'id' => $eventId,
                'cliente_id' => $user,
            ])
            ->exists();
    }
}

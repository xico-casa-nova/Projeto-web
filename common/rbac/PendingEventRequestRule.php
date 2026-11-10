<?php

declare(strict_types=1);

namespace common\rbac;

use common\models\Evento;
use Yii;
use yii\rbac\Rule;

class PendingEventRequestRule extends Rule
{
    public $name = 'pendingEventRequest';

    public function execute($user, $item, $params): bool
    {
        if (Yii::$app->authManager->getAssignment('admin', (string) $user) !== null) {
            return true;
        }

        $eventId = $params['eventId'] ?? null;
        if (!is_scalar($eventId) || !is_numeric($eventId)) {
            return false;
        }

        return Evento::find()
            ->where([
                'id' => $eventId,
                'estado' => Evento::ESTADO_RASCUNHO,
            ])
            ->exists();
    }
}

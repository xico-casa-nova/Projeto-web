<?php

use common\models\User;
use yii\db\Migration;

class m261115_152224_seed_gestor_user extends Migration
{
    public function safeUp(): void
    {
        $username = 'gestor';
        $email = 'gestor@gmail.com';
        $password = 'gestorgestor';

        $user = User::findOne(['username' => $username]);
        if ($user === null) {
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->status = User::STATUS_ACTIVE;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->save(false);
        }

        // Criar ou atualizar user_details
        $userDetails = (new \yii\db\Query())
            ->from('{{%user_details}}')
            ->where(['user_id' => $user->id])
            ->one($this->db);

        if ($userDetails === null) {
            $this->insert('{{%user_details}}', [
                'user_id' => $user->id,
                'nome_completo' => 'Gestor do Sistema',
                'role' => 'gestor',
            ]);
        } else {
            $this->update('{{%user_details}}', ['role' => 'gestor'], ['user_id' => $user->id]);
        }

        // Atribuir Role RBAC 'gestor'
        $auth = Yii::$app->getAuthManager();
        if ($auth !== null) {
            $role = $auth->getRole('gestor');
            if ($role !== null && $auth->getAssignment('gestor', (string) $user->id) === null) {
                $auth->assign($role, (string) $user->id);
            }
        }
    }

    public function safeDown(): void
    {
        $user = User::findOne(['username' => 'gestor']);
        if ($user !== null) {
            $auth = Yii::$app->getAuthManager();
            if ($auth !== null) {
                $role = $auth->getRole('gestor');
                if ($role !== null) {
                    $auth->revoke($role, (string) $user->id);
                }
            }

            $this->delete('{{%user_details}}', ['user_id' => $user->id]);
            $user->delete();
        }
    }
}

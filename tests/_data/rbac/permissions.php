<?php

/**
 * AFTER CHANGES YOU MUST RUN THE FOLLOWING `php yii rbac/up`
 */

return [
    'AdminModule' => Yii::t('app', 'Access to the Control Panel'),
    // for actions
    'ACTION_AdminRoles' => Yii::t('app', 'Control Panel / Roles'),
    'ACTION_AdminUsers' => Yii::t('app', 'Control Panel / Users'),
    'ACTION_AdminSettings' => Yii::t('app', 'Control Panel / Settings'),
    'ACTION_AdminTest' => 'Control Panel / Test',
];

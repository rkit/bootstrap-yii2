<?php

/**
 * AFTER CHANGES YOU MUST RUN THE FOLLOWING `composer reconfig`
 */

$textControlPanel = Yii::t('app', 'Control Panel');

return [
    'AdminModule' => Yii::t('app', 'Access to the Control Panel'),
    // for actions
    'ACTION_AdminRoles' => $textControlPanel . ' / ' . Yii::t('app', 'Roles'),
    'ACTION_AdminUsers' => $textControlPanel . ' / ' . Yii::t('app', 'Users'),
    'ACTION_AdminSettings' => $textControlPanel . ' / ' . Yii::t('app', 'Settings'),
];

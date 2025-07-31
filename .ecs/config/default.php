<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ECSConfig): void {
    // Contao
    $ECSConfig->import(__DIR__ . '../../../../../contao/easy-coding-standard/config/contao.php');

    // Custom
    $ECSConfig->import(__DIR__.'/set/header_comment_fixer.php');
    $ECSConfig->import(__DIR__.'/set/skip_configuration.php');
};

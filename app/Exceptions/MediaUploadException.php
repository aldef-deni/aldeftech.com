<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * A rejected upload, carrying a message that is safe to show to an editor.
 */
class MediaUploadException extends RuntimeException
{
}

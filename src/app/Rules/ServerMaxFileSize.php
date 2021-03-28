<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ServerMaxFileSize implements Rule
{
    private $allowedSize;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        $this->allowedSize = file_upload_max_size();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if($value instanceof UploadedFile && !$value->isValid()){
            return false;
        }
        return $this->allowedSize > $value->getSize();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.max.file', ['max' => $this->allowedSizeConvertToKilobytes()]);
    }

    private function allowedSizeConvertToKilobytes(): int
    {
        return intval($this->allowedSize / 1024);
    }
}

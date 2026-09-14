<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): array
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $ruleList = is_array($ruleString) ? $ruleString : explode('|', $ruleString);
            $value    = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                $param = null;
                if (strpos($rule, ':') !== false) {
                    [$rule, $param] = explode(':', $rule, 2);
                }
                if (!$this->check($field, $value, $rule, $param)) {
                    break;
                }
            }
        }

        return $this->errors;
    }

    private function check(string $field, $value, string $rule, ?string $param): bool
    {
        $empty = ($value === null || $value === '');

        switch ($rule) {
            case 'required':
                if ($empty) {
                    $this->addError($field, 'Kolom ini wajib diisi.');
                    return false;
                }
                return true;

            case 'email':
                if ($empty) {
                    return true;
                }
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'Format email tidak valid.');
                    return false;
                }
                return true;

            case 'numeric':
                if ($empty) {
                    return true;
                }
                if (!is_numeric($value)) {
                    $this->addError($field, 'Harus berupa angka.');
                    return false;
                }
                return true;

            case 'min':
                if ($empty) {
                    return true;
                }
                if (is_numeric($value)) {
                    if ((float) $value < (float) $param) {
                        $this->addError($field, 'Nilai minimal ' . $param . '.');
                        return false;
                    }
                } elseif (mb_strlen((string) $value) < (int) $param) {
                    $this->addError($field, 'Minimal ' . $param . ' karakter.');
                    return false;
                }
                return true;

            case 'max':
                if ($empty) {
                    return true;
                }
                if (mb_strlen((string) $value) > (int) $param) {
                    $this->addError($field, 'Maksimal ' . $param . ' karakter.');
                    return false;
                }
                return true;

            case 'in':
                if ($empty) {
                    return true;
                }
                $options = explode(',', (string) $param);
                if (!in_array((string) $value, $options, true)) {
                    $this->addError($field, 'Nilai tidak valid.');
                    return false;
                }
                return true;

            case 'date':
                if ($empty) {
                    return true;
                }
                if (strtotime((string) $value) === false) {
                    $this->addError($field, 'Format tanggal tidak valid.');
                    return false;
                }
                return true;

            default:
                return true;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}
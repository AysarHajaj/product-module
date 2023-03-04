<?php

namespace App\Formatters;

class LoggingFormatter extends Formatter
{
    public function prepareLoggingData($action, $product, $user)
    {
        return [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => $action
        ];
    }

    public function prepareAddLoggingFields($logging, $input)
    {
        $result = [];
        foreach ($input as $key => $value) {
            $result[] = [
                'logging_id' => $logging->id,
                'field_name' => $key,
                'field_new_value' => $value
            ];
        }

        return $result;
    }

    public function prepareUpdateLoggingFields($logging, $input, $product)
    {
        $result = [];
        foreach ($input as $key => $value) {
            $oldValue = $product[$key];
            $product[$key] = $value;
            if ($product->isDirty($key)) {
                $result[] = [
                    'logging_id' => $logging->id,
                    'field_name' => $key,
                    'field_old_value' => $oldValue,
                    'field_new_value' => $value
                ];
            }
        }

        return $result;
    }
}

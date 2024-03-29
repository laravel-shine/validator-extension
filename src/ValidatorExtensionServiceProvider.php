<?php

namespace LaravelShine\ValidatorExtension;

use Illuminate\Support\ServiceProvider;

class ValidatorExtensionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $translator = $this->app->get('translator');
        $validator = $this->app->get('validator');

        $validator->extend('x_alpha', function ($attribute, $value) {
            return is_string($value) && preg_match('/^[A-Za-z]+$/', $value);
        }, $translator->get('validation.alpha'));

        $validator->extend('x_alpha_num', function ($attribute, $value) {
            return (is_string($value) || is_numeric($value)) && preg_match('/^[A-Za-z0-9]+$/', $value);
        }, $translator->get('validation.alpha_num'));

        $validator->extend('x_alpha_dash', function ($attribute, $value) {
            return (is_string($value) || is_numeric($value)) && preg_match('/^[A-Za-z0-9_-]+$/', $value);
        }, $translator->get('validation.alpha_dash'));

        $validator->extend('x_hex', function ($attribute, $value) {
            return (is_string($value) || is_numeric($value)) && preg_match('/^[A-Fa-f0-9]+$/', $value);
        }, $translator->get('validation.alpha_num'));

        $this->extendDigits($validator, $translator);
        $this->extendFloat($validator, $translator);
    }

    public function extendDigits($validator, $translator)
    {
        $validator->extend('x_digits', function ($attribute, $value, $parameters) {
            if (!is_string($value) && !is_numeric($value) || !preg_match('/^[0-9]+$/', $value)) {
                return false;
            }

            switch (count($parameters)) {
                case 0:
                    return true;

                case 1:
                    return strlen($value) == $parameters[0];

                default:
                    $length = strlen($value);

                    return $length >= $parameters[0] && $length <= $parameters[1];
            }
        }, $translator->get('validation.numeric'));

        $validator->replacer('x_digits', function ($message, $attribute, $rule, $parameters) use ($translator) {
            switch (count($parameters)) {
                case 0:
                    return $translator->get('validation.numeric', ['attribute' => $attribute]);

                case 1:
                    return $translator->get('validation.digits', ['attribute' => $attribute, 'digits' => $parameters[0]]);

                default:
                    return $translator->get('validation.digits_between', ['attribute' => $attribute, 'min' => $parameters[0], 'max' => $parameters[1]]);
            }
        });
    }

    public function extendFloat($validator, $translator)
    {
        if (!$translator->has('validation.float')) {
            $translator->addLines([
                'validation.float' => 'The :attribute field must be a float number.',
            ], 'en');
        }

        $validator->extend('float', function ($attribute, $value) {
            return (is_string($value) || is_numeric($value)) && false !== filter_var($value, FILTER_VALIDATE_FLOAT);
        });
    }
}

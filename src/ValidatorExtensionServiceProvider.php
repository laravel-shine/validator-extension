<?php

namespace LaravelShine\ValidatorExtension;

use Illuminate\Support\ServiceProvider;

class ValidatorExtensionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $translator = $this->app['translator'];
        $validator = $this->app['validator'];

        $validator->extend('x_alpha', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && preg_match('/^[A-Za-z]+$/', $value);
        }, $translator->get('validation.alpha'));

        $validator->extend('x_alpha_num', function ($attribute, $value, $parameters, $validator) {
            return (is_string($value) || is_numeric($value)) && preg_match('/^[A-Za-z0-9]+$/', $value);
        }, $translator->get('validation.alpha_num'));

        $validator->extend('x_alpha_dash', function ($attribute, $value, $parameters, $validator) {
            return (is_string($value) || is_numeric($value)) && preg_match('/^[A-Za-z0-9_-]+$/', $value);
        }, $translator->get('validation.alpha_dash'));

        $validator->extend('x_hex', function ($attribute, $value, $parameters, $validator) {
            return (is_string($value) || is_numeric($value)) && preg_match('/^[A-Fa-f0-9]+$/', $value);
        }, $translator->get('validation.alpha_num'));

        $validator->extend('x_digits', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value) && !is_numeric($value)) {
                return false;
            }

            if (!preg_match('/^[0-9]+$/', $value)) {
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
}

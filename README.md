# Laravel Validator Extension

Extend Laravel Validation Rules

## Available Rules

| Rule | Format | Description
| - | - | - |
| x_alpha | `/^[A-Za-z]+$/` | Alphabetic characters only.
| x_alpha_num | `/^[A-Za-z0-9]+$/` | Alphabetic and numeric characters only.
| x_alpha_dash | `/^[A-Za-z0-9_-]+$/` | Alphabetic, numeric, dashes and underscores.
| x_hex | `/^[A-Fa-f0-9]+$/` | Hexadecimal charaters.
| x_digits | `/^[0-9]+$/` | Numeric charaters
| x_digits:length | `/^[0-9]{length}$/` | Numeric charaters that have an exact length.
| x_digits:min,max | `/^[0-9]{min,max}$/` | Numeric charaters that have a length between `min` and `max`.
| float | `is_float()` | Is float number

# How to verify input (front && back) ?

## Chart :

| Input name       | Type      | Requiered | Verif               | Hint          |
|------------------|-----------|-----------|---------------------|---------------|
| `gender`         | `string`  | Yes       | != undefined        | `if`          |
| `lastName`       | `string`  | Yes       | length >= 3         | `if`          |
| `firstName`      | `string`  | Yes       | length >= 3         | `if`          |
| `emailAddr`      | `string`  | Yes       | length >=3 && a@a.a | `if`          |
| `membershipType` | `string`  | Yes       | (??)                |               |
| `birthDate`      | `int`     | No        | jj/dd/aaaa          | use `split()`, then check length |
| `address`        | `string`  | No        | isString            |               |
| `city`           | `string`  | No        | isString            |               |
| `postCode`       | `int`     | No        | isInt               |               |
| `country`        | `string`  | No        | isString            |               |
| `phoneNum`       | `string`  | No        | (??)                |               |

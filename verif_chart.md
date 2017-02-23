# How to verify input (front && back) ?

## Chart :

| Input name       | Type      | Requiered | Verif               |
|------------------|-----------|-----------|---------------------|
| `gender`         | `string`  | Yes       | (??)                |
| `lastName`       | `string`  | Yes       | length >= 3         |
| `firstName`      | `string`  | Yes       | length >= 3         |
| `emailAddr`      | `string`  | Yes       | length >=3 && a@a.a |
| `membershipType` | `string`  | Yes       | ??                  |
| `birthDate`      | `int`     | No        | jj/dd/aaaa          |
| `address`        | `string`  | No        | isString            |
| `city`           | `string`  | No        | isString            |
| `postCode`       | `int`     | No        | isInt               |
| `country`        | `string`  | No        | isString            |
| `phoneNum`       | `int`     | No        | length == 10        |
